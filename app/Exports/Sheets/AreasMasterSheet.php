<?php

namespace App\Exports\Sheets;

use App\Models\Area;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AreasMasterSheet implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Area::select('name')->get();
    }

    public function headings(): array
    {
        return ['Available Areas'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'BDD7EE']
                ]
            ],
        ];
    }
}