<?php

namespace App\Exports;

use App\Models\DiklatPlanning;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Auth;

class DiklatPlanningExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $year;

    public function __construct($year = null)
    {
        $this->year = $year;
    }

    public function collection()
    {
        $roleId = Auth::user()->role_id;
        $query = DiklatPlanning::with(['unit', 'vendor']);
        if ($this->year && $this->year !== 'all') {
            $query->where('year', $this->year);
        }
        
        // Apply role-based filtering similar to getData method
        $query->when($roleId == 1, function ($query) {
            return $query;
        })
        ->when($roleId == 2, function ($query) {
            return $query->where('vendor_id', Auth::user()->vendor_id);
        })
        ->when($roleId == 3, function ($query) {
            $unitId = Auth::user()->area->unit_id;
            return $query->where('unit_id', $unitId);
        })
        ->when($roleId == 4, function ($query) {
            return $query;
        });

        return $query->get()->map(function ($planning) {
            return [
                'name' => $planning->name,
                'year' => $planning->year,
                'estimate_start_date' => $planning->estimate_start_date,
                'estimate_end_date' => $planning->estimate_end_date,
                'vendor' => $planning->vendor->name,
                'count_of_participant' => $planning->count_of_participant,
                'unit' => $planning->unit->name,
                'total_cost' => $planning->total_cost,
                'approval_status' => $planning->approve_by_htd ? 'Approved' : 'Pending',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Year',
            'Estimate Start Date',
            'Estimate End Date',
            'Vendor',
            'Count of Participant',
            'Unit',
            'Total Cost',
            'Approval Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:I1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E2EFDA']
                ]
            ],
        ];
    }
}