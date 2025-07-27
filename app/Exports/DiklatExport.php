<?php

namespace App\Exports;

use App\Models\Diklat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DiklatExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $year;

    public function __construct($year = null)
    {
        $this->year = $year;
    }

    public function collection()
    {
        $query = Diklat::with(['unit', 'vendor']);
        
        if ($this->year && $this->year !== 'all') {
            $query->where('year', $this->year);
        }

        return $query->get()->map(function ($diklat) {
            return [
                'name' => $diklat->name,
                'letter_number' => $diklat->letter_number,
                'year' => $diklat->year,
                'estimate_start_date' => $diklat->estimate_start_date,
                'estimate_end_date' => $diklat->estimate_end_date,
                'vendor' => $diklat->vendor->name,
                'count_of_participant' => $diklat->count_of_participant,
                'unit' => $diklat->unit->name,
                'total_cost' => $diklat->total_cost,
                'payment_status' => $diklat->payment_status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Letter Number',
            'Year',
            'Estimate Start Date',
            'Estimate End Date',
            'Vendor',
            'Count of Participant',
            'Unit',
            'Total Cost',
            'Payment Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:J1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E2EFDA']
                ]
            ],
        ];
    }
}