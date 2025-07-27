<?php

namespace App\Exports\Sheets;

use App\Models\Area;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Auth;

class UnitsMasterSheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        $roleId = Auth::user()->role_id;
        return Area::with('unit')
            ->when($roleId == 7, function ($query) {
                // HTD melihat data dari unit yang mereka kelola
                $unitIdsString = Auth::user()->manage_unit_ids;
                $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
                return $query->whereIn('unit_id', $unitIds ?? []);
            })
            ->when($roleId == 8, function ($query) {
                // HTD melihat data dari unit yang mereka kelola
                $unitIdsString = Auth::user()->manage_unit_ids;
                $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
                return $query->whereIn('unit_id', $unitIds ?? []);
            })
            ->get()
            ->map(function ($area) {
                return [
                    'formatted_name' => $area->unit->name . ' | ' . $area->name
                ];
            });
    }

    public function headings(): array
    {
        return ['Kode Unit & Bidang'];
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
            'A' => [
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ]
            ]
        ];
    }
}