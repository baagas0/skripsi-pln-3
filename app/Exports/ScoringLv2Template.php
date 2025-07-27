<?php

namespace App\Exports;

use App\Models\DiklatParticipant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScoringLv2Template implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $diklat_id;

    public function __construct($diklat_id)
    {
        $this->diklat_id = $diklat_id;
    }

    public function collection()
    {
        return DiklatParticipant::where('diklat_id', $this->diklat_id)
            ->with('employee')
            ->get()
            ->map(function ($participant) {
                return [
                    'diklat_participant_id' => $participant->id,
                    'name' => $participant->employee->name,
                    'pretest_score' => '',
                    'posttest_score' => ''
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Participant ID',
            'Name',
            'Pre Test Score',
            'Post Test Score'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['font' => ['bold' => true]],
            'B' => ['font' => ['bold' => true]],
            'A1:D1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E2EFDA']
                ]
            ],
        ];
    }
}