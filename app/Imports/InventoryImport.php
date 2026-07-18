<?php

namespace App\Imports;

use App\Models\JobOrdersPartServiceOption;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Smalot\PdfParser\Parser;

class InventoryImport
{
    public $updated = 0;
    public $notFound = [];
    public $unparsed = [];
    public $conflicts = [];
    public $debug = [];

    // Excel columns are 1-indexed: A = Product Categories (1), B = Part # (2).
    // Column B is matched against job_orders_part_service_options.part_number.
    // Column A breaks ties when several DB rows share the same part_number: it
    // is matched against job_orders_part_service_options.value.
    // The "END INV." column updates job_orders_part_service_options.stock; its
    // position varies per sheet (the number of daily OUT columns changes), so it
    // is located by header text, falling back to column M (13).
    const EXCEL_CATEGORY_COLUMN = 1;
    const EXCEL_PART_NUMBER_COLUMN = 2;
    const EXCEL_END_INV_FALLBACK_COLUMN = 13;

    public function importExcelFile(string $path)
    {
        $lookup = $this->buildLookup();

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();

        $endInvColumn = $this->findEndInvColumn($sheet);

        // Updates are queued per DB item and applied after the whole sheet is
        // read, so a later row can never silently overwrite an earlier one.
        $pending = [];

        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            if ($rowIndex === 1) {
                // Header row.
                continue;
            }

            $category = trim((string) $sheet->getCellByColumnAndRow(self::EXCEL_CATEGORY_COLUMN, $rowIndex)->getValue());
            $partNumber = trim((string) $sheet->getCellByColumnAndRow(self::EXCEL_PART_NUMBER_COLUMN, $rowIndex)->getValue());
            $endInvRaw = $sheet->getCellByColumnAndRow($endInvColumn, $rowIndex)->getCalculatedValue();

            if ($partNumber === '' || $endInvRaw === null || $endInvRaw === '') {
                continue;
            }

            if (!is_numeric($endInvRaw)) {
                $this->unparsed[] = "Row {$rowIndex}: {$partNumber} {$endInvRaw}";
                continue;
            }

            $endInv = (int) $endInvRaw;
            $normalizedPart = $this->normalize($partNumber);
            $normalizedCategory = $this->normalize($category);
            $candidates = $lookup[$normalizedPart] ?? null;
            $matchPercent = $candidates ? 100.0 : null;

            if (!$candidates) {
                [$candidates, $matchPercent] = $this->findFuzzyMatch($normalizedPart, $lookup);
            }

            $item = $candidates ? $this->resolveDuplicates($candidates, $normalizedCategory) : null;

            Log::info('InventoryImport: excel row processed', [
                'row' => $rowIndex,
                'partNumber' => $partNumber,
                'category' => $category,
                'endInv' => $endInv,
                'matched' => (bool) $item,
                'matchedId' => $item ? $item->id : null,
                'matchPercent' => $item ? $matchPercent : null,
                'duplicates' => $candidates ? count($candidates) : 0,
            ]);

            if ($item) {
                $this->queueUpdate($pending, $item, $endInv, $matchPercent, $rowIndex, $partNumber);
            } else {
                $this->notFound[] = $partNumber;
            }
        }

        foreach ($pending as $update) {
            $update['item']->stock = $update['endInv'];
            $update['item']->save();
            $this->updated++;
        }
    }

    // Each DB item may only be updated by one Excel row per import. When a
    // second row resolves to an item that is already queued, the closer PART #
    // match (higher percentage, i.e. exact beats fuzzy) wins and the other row
    // is reported as a conflict instead of silently overwriting the stock.
    // Rows that agree on the same quantity are not treated as conflicts.
    private function queueUpdate(array &$pending, $item, int $endInv, float $matchPercent, int $rowIndex, string $partNumber): void
    {
        $existing = $pending[$item->id] ?? null;

        if (!$existing) {
            $pending[$item->id] = [
                'item' => $item,
                'endInv' => $endInv,
                'percent' => $matchPercent,
                'row' => $rowIndex,
                'partNumber' => $partNumber,
            ];
            return;
        }

        if ($existing['endInv'] === $endInv) {
            return;
        }

        if ($matchPercent > $existing['percent']) {
            $loser = $existing;
            $pending[$item->id] = [
                'item' => $item,
                'endInv' => $endInv,
                'percent' => $matchPercent,
                'row' => $rowIndex,
                'partNumber' => $partNumber,
            ];
        } else {
            $loser = [
                'row' => $rowIndex,
                'partNumber' => $partNumber,
                'endInv' => $endInv,
            ];
        }

        $winner = $pending[$item->id];
        $conflict = "Row {$loser['row']} \"{$loser['partNumber']}\" (qty {$loser['endInv']}) skipped: item \"{$item->part_number}\" already updated by row {$winner['row']} \"{$winner['partNumber']}\" (qty {$winner['endInv']})";
        $this->conflicts[] = $conflict;

        Log::warning('InventoryImport: conflicting rows resolved to the same item', [
            'itemId' => $item->id,
            'conflict' => $conflict,
        ]);
    }

    public function importFile(string $path)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($path);

        $lookup = $this->buildLookup();

        $pages = $pdf->getPages();
        if (!count($pages)) {
            $this->debug[] = 'No pages found in file.';
            return;
        }

        $pending = [];

        foreach ($pages as $pageIndex => $page) {
            $lines = preg_split('/\r\n|\r|\n/', $page->getText());

            foreach ($lines as $line) {
                $line = trim(preg_replace('/\s+/', ' ', (string) $line));
                if ($line === '') {
                    continue;
                }

                // Skip repeated "PART #" / "END INV." table headers.
                $upper = strtoupper($line);
                if ($upper === 'PART #' || $upper === 'END INV.' || $upper === 'PART # END INV.') {
                    continue;
                }

                // Each data row is "<part number> <end inv. quantity>".
                if (!preg_match('/^(.*\S)\s+(\d+)$/', $line, $matches)) {
                    $this->unparsed[] = $line;
                    continue;
                }

                $partNumber = trim($matches[1]);
                $endInv = (int) $matches[2];

                // PDF rows carry no product category, so duplicates fall back
                // to the first matching row.
                $candidates = $lookup[$this->normalize($partNumber)] ?? null;
                $item = $candidates ? $candidates[0] : null;

                Log::info('InventoryImport: row processed', [
                    'page' => $pageIndex,
                    'partNumber' => $partNumber,
                    'endInv' => $endInv,
                    'matched' => (bool) $item,
                    'matchedId' => $item ? $item->id : null,
                ]);

                if ($item) {
                    $this->queueUpdate($pending, $item, $endInv, 100.0, 0, $partNumber);
                } else {
                    $this->notFound[] = $partNumber;
                }
            }
        }

        foreach ($pending as $update) {
            $update['item']->stock = $update['endInv'];
            $update['item']->save();
            $this->updated++;
        }
    }

    // Keyed by normalized part_number; each entry is a list of items because
    // several DB rows can share the same part_number (resolved later against
    // the imported product category).
    private function buildLookup()
    {
        $lookup = [];
        foreach (JobOrdersPartServiceOption::select('id', 'part_number', 'value', 'stock')->get() as $item) {
            if (trim((string) $item->part_number) === '') {
                continue;
            }
            $lookup[$this->normalize($item->part_number)][] = $item;
        }

        return $lookup;
    }

    // Minimum similarity (in percent) between the imported PART # and a
    // part_number for a fuzzy match to count. Anything from 90% up to an
    // exact match (100%) updates the stock; below 90% is reported as not found.
    const FUZZY_MATCH_MIN_PERCENT = 90;

    // Locates the "END INV." column in the header row; its position shifts
    // depending on how many daily OUT columns the sheet has.
    private function findEndInvColumn($sheet): int
    {
        $highest = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn(1));
        for ($col = 1; $col <= $highest; $col++) {
            $header = strtoupper(trim((string) $sheet->getCellByColumnAndRow($col, 1)->getValue()));
            if (str_replace([' ', '.'], '', $header) === 'ENDINV') {
                return $col;
            }
        }

        return self::EXCEL_END_INV_FALLBACK_COLUMN;
    }

    // Falls back to a fuzzy match when no exact PART # match exists. The
    // imported part number and each candidate are compared word-order
    // insensitively (each side's words sorted first); the candidate with the
    // highest similar_text() percentage wins, provided it reaches at least
    // FUZZY_MATCH_MIN_PERCENT (90%). Returns the list of DB items sharing the
    // winning part_number.
    private function findFuzzyMatch(string $normalizedPart, array $lookup): array
    {
        $partSortedString = $this->sortedWords($normalizedPart);

        $bestItems = null;
        $bestPercent = 0.0;

        foreach ($lookup as $key => $candidates) {
            similar_text($partSortedString, $this->sortedWords($key), $percent);

            if ($percent >= self::FUZZY_MATCH_MIN_PERCENT && $percent > $bestPercent) {
                $bestItems = $candidates;
                $bestPercent = $percent;
            }
        }

        return $bestItems ? [$bestItems, round($bestPercent, 1)] : [null, null];
    }

    // Picks one item out of the DB rows sharing a matched part_number. With a
    // single candidate the part number alone is decisive. With duplicates, the
    // imported PRODUCT CATEGORIES (column A) is compared against each row's
    // `value` using the same 90-100% similarity rule; the closest match wins.
    // If no value matches the category, nothing is updated (the row is
    // reported as not found) rather than guessing between duplicates.
    private function resolveDuplicates(array $candidates, string $normalizedCategory)
    {
        if (count($candidates) === 1) {
            return $candidates[0];
        }

        if ($normalizedCategory === '') {
            return null;
        }

        $categorySortedString = $this->sortedWords($normalizedCategory);

        $bestItem = null;
        $bestPercent = 0.0;

        foreach ($candidates as $candidate) {
            similar_text($categorySortedString, $this->sortedWords($this->normalize($candidate->value)), $percent);

            if ($percent >= self::FUZZY_MATCH_MIN_PERCENT && $percent > $bestPercent) {
                $bestItem = $candidate;
                $bestPercent = $percent;
            }
        }

        return $bestItem;
    }

    private function sortedWords(string $value): string
    {
        $words = array_values(array_filter(explode(' ', $value), fn ($w) => $w !== ''));
        sort($words);

        return implode(' ', $words);
    }

    private function normalize($value)
    {
        // Excel exports often contain non-breaking spaces (U+00A0), which \s+
        // does not match — convert them to regular spaces first.
        $value = str_replace("\xC2\xA0", ' ', (string) $value);

        return strtolower(trim(preg_replace('/\s+/', ' ', $value)));
    }
}
