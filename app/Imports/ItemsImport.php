<?php
namespace App\Imports;

use Throwable;
use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;

class ItemsImport implements ToModel, WithMappedCells, WithStartRow, SkipsOnError
{
    use SkipsErrors;

    public function mapping(): array
    {
        return [
            'item_name' => 'A3',    // Start from row 3, column A
            'item_number' => 'B3',  // Start from row 3, column B
            'brand_name' => 'C3',   // Start from row 3, column C
            'description' => 'D3',  // Start from row 3, column D
            'cost_price' => 'E3',   // Start from row 3, column E
            'unit_price' => 'F3',   // Start from row 3, column F
            'is_serialized' => 'G3',// Start from row 3, column G
            'stock_type' => 'H3',   // Start from row 3, column H
        ];
    }

    public function model(array $row)
    {
        // Filter out null or empty values
        $filteredRow = array_filter($row, function ($value) {
            return $value !== null && $value !== '';
        });

        // Skip the row if all values are null or empty
        if (empty($filteredRow)) {
            return null;
        }

        return [
            'item_name' => $filteredRow['item_name'] ?? null,
            'item_number' => $filteredRow['item_number'] ?? null,
            'brand_name' => $filteredRow['brand_name'] ?? null,
            'description' => $filteredRow['description'] ?? null,
            'cost_price' => $filteredRow['cost_price'] ?? null,
            'unit_price' => $filteredRow['unit_price'] ?? null,
            'is_serialized' => isset($filteredRow['is_serialized']) && $filteredRow['is_serialized'] == 1 ? true : false,
            'stock_type' => isset($filteredRow['stock_type']) && $filteredRow['stock_type'] == 1 ? 'Stock' : 'Not',
        ];
    }

    public function startRow(): int
    {
        return 3; // Start from row 3
    }

   
}