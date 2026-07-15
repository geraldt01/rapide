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
    public $debug = [];

    // Excel columns are 1-indexed: B = Part # (2), M = End Inv. (13).
    // Column B is matched against job_orders_part_service_options.part_number.
    // Column M updates job_orders_part_service_options.stock.
    const EXCEL_PART_NUMBER_COLUMN = 2;
    const EXCEL_END_INV_COLUMN = 13;

    public function importExcelFile(string $path)
    {
        $lookup = $this->buildLookup();

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            if ($rowIndex === 1) {
                // Header row.
                continue;
            }

            $partNumber = trim((string) $sheet->getCellByColumnAndRow(self::EXCEL_PART_NUMBER_COLUMN, $rowIndex)->getValue());
            $endInvRaw = $sheet->getCellByColumnAndRow(self::EXCEL_END_INV_COLUMN, $rowIndex)->getValue();

            if ($partNumber === '' || $endInvRaw === null || $endInvRaw === '') {
                continue;
            }

            if (!is_numeric($endInvRaw)) {
                $this->unparsed[] = "Row {$rowIndex}: {$partNumber} {$endInvRaw}";
                continue;
            }

            $endInv = (int) $endInvRaw;
            $normalizedPart = $this->normalize($partNumber);
            $item = $lookup[$normalizedPart] ?? null;
            $wordMatches = null;

            if (!$item) {
                [$item, $wordMatches] = $this->findFuzzyMatch($normalizedPart, $lookup);
            }

            Log::info('InventoryImport: excel row processed', [
                'row' => $rowIndex,
                'partNumber' => $partNumber,
                'endInv' => $endInv,
                'matched' => (bool) $item,
                'matchedId' => $item ? $item->id : null,
                'wordMatches' => $wordMatches,
            ]);

            if ($item) {
                $item->stock = $endInv;
                $item->save();
                $this->updated++;
            } else {
                $this->notFound[] = $partNumber;
            }
        }
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

                $item = $lookup[$this->normalize($partNumber)] ?? null;

                Log::info('InventoryImport: row processed', [
                    'page' => $pageIndex,
                    'partNumber' => $partNumber,
                    'endInv' => $endInv,
                    'matched' => (bool) $item,
                    'matchedId' => $item ? $item->id : null,
                ]);

                if ($item) {
                    $item->stock = $endInv;
                    $item->save();
                    $this->updated++;
                } else {
                    $this->notFound[] = $partNumber;
                }
            }
        }
    }

    private function buildLookup()
    {
        $lookup = [];
        foreach (JobOrdersPartServiceOption::select('id', 'part_number', 'stock')->get() as $item) {
            if (trim((string) $item->part_number) === '') {
                continue;
            }
            $lookup[$this->normalize($item->part_number)] = $item;
        }

        return $lookup;
    }

    // Falls back to a fuzzy match when no exact PART # match exists. A candidate
    // is considered a match when either:
    // - at least one whole word is shared with the imported part number, or
    // - the words are more than 60% similar overall (covers jumbled/reordered
    //   or slightly misspelled words) — compared after sorting each side's
    //   words so word order doesn't matter.
    private function findFuzzyMatch(string $normalizedPart, array $lookup): array
    {
        $partWords = $this->words($normalizedPart);
        $partSorted = $partWords;
        sort($partSorted);
        $partSortedString = implode(' ', $partSorted);

        $bestItem = null;
        $bestWordMatches = 0;
        $bestPercent = 0.0;

        foreach ($lookup as $key => $candidate) {
            $keyWords = $this->words($key);
            $wordMatches = count(array_intersect($partWords, $keyWords));

            $keySorted = $keyWords;
            sort($keySorted);
            similar_text($partSortedString, implode(' ', $keySorted), $percent);

            $isMatch = $wordMatches >= 1 || $percent > 60;
            $isBetter = $wordMatches > $bestWordMatches
                || ($wordMatches === $bestWordMatches && $percent > $bestPercent);

            if ($isMatch && ($isBetter || !$bestItem)) {
                $bestItem = $candidate;
                $bestWordMatches = $wordMatches;
                $bestPercent = $percent;
            }
        }

        return $bestItem ? [$bestItem, $bestWordMatches] : [null, null];
    }

    private function words(string $value): array
    {
        return array_values(array_filter(explode(' ', $value), fn ($w) => $w !== ''));
    }

    private function normalize($value)
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', (string) $value)));
    }
}
