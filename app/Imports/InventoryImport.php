<?php

namespace App\Imports;

use App\Models\JobOrdersPartServiceOption;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class InventoryImport implements ToCollection
{
    public $updated = 0;
    public $notFound = [];
    public $debug = [];

    public function collection(Collection $rows)
    {
        Log::info('InventoryImport: raw rows received', ['rowCount' => $rows->count(), 'first3' => $rows->take(3)->toArray()]);

        $header = $rows->first();
        if (!$header) {
            $this->debug[] = 'No rows found in file.';
            return;
        }

        $fixedPartCol = $this->columnIndex('B');
        $fixedEndInvCol = $this->columnIndex('M');

        if ($header->has($fixedPartCol) && $header->has($fixedEndInvCol)) {
            // Fixed layout: Part # in column B, End Inv. in column M.
            $partCol = $fixedPartCol;
            $endInvCol = $fixedEndInvCol;
        } else {
            // Fallback: detect columns by header label for shorter exports.
            $partCol = null;
            $endInvCol = null;
            $nameFallbackCol = null;
            $stockFallbackCol = null;

            foreach ($header as $index => $cell) {
                $label = strtolower(trim((string) $cell));

                if ($partCol === null && str_contains($label, 'part')) {
                    $partCol = $index;
                }

                if ($endInvCol === null && (str_contains($label, 'end inv') || str_contains($label, 'ending inv'))) {
                    $endInvCol = $index;
                }

                if ($nameFallbackCol === null && $label === 'name') {
                    $nameFallbackCol = $index;
                }

                if ($stockFallbackCol === null && $label === 'stock') {
                    $stockFallbackCol = $index;
                }
            }

            // Some exports use plain "name"/"stock" columns instead of "Part #"/"End Inv."
            if ($partCol === null) {
                $partCol = $nameFallbackCol;
            }
            if ($endInvCol === null) {
                $endInvCol = $stockFallbackCol;
            }
        }

        Log::info('InventoryImport: header parsed', ['header' => $header->toArray(), 'partCol' => $partCol, 'endInvCol' => $endInvCol]);

        if ($partCol === null || $endInvCol === null) {
            $this->debug[] = 'Could not find "Part #" and/or "End Inv." columns in the header row: ' . implode(' | ', $header->toArray());
            return;
        }

        $lookup = [];
        foreach (JobOrdersPartServiceOption::select('id', 'value', 'stock')->get() as $item) {
            $lookup[$this->normalize($item->value)] = $item;
        }

        foreach ($rows->skip(1) as $rowIndex => $row) {
            $partNumber = trim((string) ($row[$partCol] ?? ''));
            if ($partNumber === '') {
                continue;
            }

            $endInv = $row[$endInvCol] ?? null;
            if ($endInv === null || $endInv === '') {
                continue;
            }

            $item = $lookup[$this->normalize($partNumber)] ?? null;

            Log::info('InventoryImport: row processed', [
                'rowIndex' => $rowIndex,
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

    private function normalize($value)
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', (string) $value)));
    }

    private function columnIndex($letter)
    {
        $letter = strtoupper($letter);
        $index = 0;
        for ($i = 0; $i < strlen($letter); $i++) {
            $index = $index * 26 + (ord($letter[$i]) - ord('A') + 1);
        }
        return $index - 1;
    }
}
