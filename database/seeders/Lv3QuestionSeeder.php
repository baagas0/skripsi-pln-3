<?php

namespace Database\Seeders;

use App\Models\ScoringLv3Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Lv3QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            ['name' => 'Peningkatan dalam pengetahuan'],
            ['name' => 'Pengingkatan dalam keterampilan'],
            ['name' => 'Peningkatan dalam inisiatif'],
            ['name' => 'Peningkatan dalam komunikasi'],
            ['name' => 'Peningkatan dalam kepemimpinan'],
            ['name' => 'Peningkatan dalam kerja sama'],
            ['name' => 'Peningkatan kualitas pekerjaan (kecepatan, ketepatan dan  ketelitian)'],
        ];

        ScoringLv3Question::insert($questions);
    }
}
