<?php

namespace Database\Seeders;

use App\Models\ScoringLv1Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Lv1QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $q = [
            [
                'group' => 'I. Pelaksanaan Pelatihan',
                'name' => 'Pengorganisasian Program Pelatihan (Jadwal/Durasi/Waktu)'
            ],
            [
                'group' => 'I. Pelaksanaan Pelatihan',
                'name' => 'Kesesuaian isi pelatihan dengan kebutuhan'
            ],
            [
                'group' => 'I. Pelaksanaan Pelatihan',
                'name' => 'Pencapaian tujuan program/kurikulum'
            ],
            [
                'group' => 'I. Pelaksanaan Pelatihan',
                'name' => ' Manfaat dari handout dan visual aids'
            ],
            [
                'group' => 'I. Pelaksanaan Pelatihan',
                'name' => 'Manfaat dari aktivitas selama pelatihan'
            ],
            [
                'group' => 'I. Pelaksanaan Pelatihan',
                'name' => 'Fasilitas dukungan (jaringan/ruangan)'
            ],
            [
                'group' => 'I. Pelaksanaan Pelatihan',
                'name' => 'Dukungan administrasi saat pendaftaran'
            ],
            [
                'group' => 'I. Pelaksanaan Pelatihan',
                'name' => 'Akomodasi (bila ada)'
            ],
            [
                'group' => 'I. Pelaksanaan Pelatihan',
                'name' => 'Konsumsi - makanan dan minuman (bila ada)'
            ],
            [
                'group' => 'II. Narasumber Pelatihan',
                'name' => 'Pengetahuan Fasilitator/Narasumber mengenai materi pelatihan'
            ],
            [
                'group' => 'II. Narasumber Pelatihan',
                'name' => 'Pengetahuan Fasilitator/Narasumber dalam menerangkan secara jelas'
            ],
            [
                'group' => 'II. Narasumber Pelatihan',
                'name' => 'Pengetahuan Fasilitator/Narasumber dalam menjawab pertanyaan peserta'
            ],
            [
                'group' => 'II. Narasumber Pelatihan',
                'name' => 'Pengetahuan Fasilitator/Narasumber dalam mengatur waktu yang efektif'
            ],
        ];

        ScoringLv1Question::insert($q);
    }
}
