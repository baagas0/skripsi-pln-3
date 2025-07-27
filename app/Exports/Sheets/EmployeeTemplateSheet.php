<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class EmployeeTemplateSheet implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            // Example row
            [
                '12345678',
                'John Doe',
                'Manager',
                'Pusertif | RPM', // Reference from Master Units sheet
                'john@example.com',
                '1990-01-01'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Jabatan',
            'Kode Unit & Area',
            'Email',
            'Tanggal Lahir'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:F1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E2EFDA']
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // NIP
            'B' => 25, // Name
            'C' => 20, // Position
            'D' => 20, // Unit
            'E' => 20, // Area
            'F' => 25, // Email
        ];
    }
}