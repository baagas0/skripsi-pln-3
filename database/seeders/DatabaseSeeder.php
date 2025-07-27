<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(Lv1QuestionSeeder::class);
        $this->call(Lv3QuestionSeeder::class);
        $this->call(ManagerSeeder::class);
        
        // Uncomment this line to seed sample tangible benefit data
        // $this->call(TangibleBenefitSeeder::class);
    }
}
