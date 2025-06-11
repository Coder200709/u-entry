<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CompetitionAthletesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $competition;
    protected $athletes;

    public function __construct($competition, $athletes)
    {
        $this->competition = $competition;
        $this->athletes = $athletes;
    }

    public function collection()
    {
        return collect($this->athletes)->map(function ($athlete) {
            return [
                $athlete->adams_id,
                $athlete->region,
                $athlete->pivot->category,
                $athlete->pivot->entry_total,
                $athlete->pivot->reserve ? 'Yes' : 'No',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ADAMS ID',
            'Region',
            'Category',
            'Entry Total',
            'Reserve',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set default font
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        // Competition info styling
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', $this->competition->name);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '2C3E50'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', $this->competition->date . ' | ' . $this->competition->location);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'size' => 12,
                'color' => ['rgb' => '2C3E50'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Add space
        $sheet->setCellValue('A3', '');
        $sheet->setCellValue('A4', '');

        // Table header styling
        $sheet->getStyle('A5:E5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Set row height for header
        $sheet->getRowDimension(5)->setRowHeight(30);

        // Data styling
        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 5) {
            $sheet->getStyle('A6:E' . $lastRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);

            // Alternating row colors
            for ($row = 6; $row <= $lastRow; $row++) {
                $fillColor = $row % 2 == 0 ? 'F8F9FA' : 'FFFFFF';
                $sheet->getStyle('A' . $row . ':E' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($fillColor);
            }

            // ADAMS ID column styling (monospace font)
            $sheet->getStyle('A6:A' . $lastRow)->getFont()->setName('Courier New');
        }

        // Auto-size columns
        foreach(range('A','E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Ensure headers are visible and properly formatted
        $sheet->getStyle('A5:E5')->getFont()->setBold(true);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // ADAMS ID
            'B' => 20,  // Region
            'C' => 15,  // Category
            'D' => 15,  // Entry Total
            'E' => 15,  // Reserve
        ];
    }
} 