<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProductExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Product::with('brand')->get()->map(function ($product) {
            return [
                $product->name,
                $product->productNum,
                optional($product->brand)->name,
                $product->sellingPrice,
            ];
        });
    }

    public function headings(): array
    {
        return [
            // Row 1: Requirement labels
            [
                'required',
                'required',
                'required',
                'required',
            ],
            // Row 2: Column names
            [
                'item_name',
                'item_number',
                'brand_name',
                'unit_price',

            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestColumn = 'D'; // Adjust if more columns are added

        // Auto column widths
        foreach (range('A', $highestColumn) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Style Row 1 (Requirement labels)
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FF0000'], // Red text
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style Row 2 (Column names)
        $sheet->getStyle('A2:' . $highestColumn . '2')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFCCFFCC'], // Light green background
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Style data rows
        $sheet->getStyle('A3:' . $highestColumn . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
    }
}
