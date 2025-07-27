<?php

namespace App\Imports;

use App\Models\ScoringLv2;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ScoringLv2Import implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $average_score = ($row['pre_test_score'] + $row['post_test_score']) / 2;

        return ScoringLv2::updateOrCreate(
            [
                'diklat_participant_id' => $row['participant_id']
            ],
            [
                'pretest_score' => $row['pre_test_score'],
                'posttest_score' => $row['post_test_score'],
                'diff_score' => $row['post_test_score'] - $row['pre_test_score'],
                'average_score' => $average_score,
            ]
        );
    }

    public function rules(): array
    {
        return [
            'participant_id' => 'required|exists:diklat_participants,id',
            'pre_test_score' => 'required|numeric|min:0|max:100',
            'post_test_score' => 'required|numeric|min:0|max:100',
        ];
    }
}