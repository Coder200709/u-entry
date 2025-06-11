<?php

namespace App\Exports;

use App\Models\Athlete;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class AthletesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $athletes;
    protected $competition;

    public function __construct($athletes = null, $competition = null)
    {
        $this->athletes = $athletes;
        $this->competition = $competition;
    }

    public function collection()
    {
        return $this->athletes->map(function($athlete) {
            return [
                'Name' => $athlete->given_name . ' ' . $athlete->family_name,
                'JSHSHIR' => strtoupper($athlete->adams_id),
                'Region' => $athlete->region,
                'Category' => $athlete->pivot->category,
                'Entry Total' => $athlete->pivot->entry_total,
                'Reserve' => $athlete->pivot->reserve ? 'Yes' : 'No'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'JSHSHIR',
            'Region',
            'Category',
            'Entry Total',
            'Reserve'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set default font
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        // Competition info styling
        $sheet->mergeCells('A1:F1');
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

        $sheet->mergeCells('A2:F2');
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
        $sheet->getStyle('A5:F5')->applyFromArray([
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
            $sheet->getStyle('A6:F' . $lastRow)->applyFromArray([
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
                $sheet->getStyle('A' . $row . ':F' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($fillColor);
            }

            // JSHSHIR column styling (monospace font)
            $sheet->getStyle('B6:B' . $lastRow)->getFont()->setName('Courier New');
        }

        // Auto-size columns
        foreach(range('A','F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30, // Name
            'B' => 15, // JSHSHIR
            'C' => 20, // Region
            'D' => 15, // Category
            'E' => 15, // Entry Total
            'F' => 15, // Reserve
        ];
    }
}
