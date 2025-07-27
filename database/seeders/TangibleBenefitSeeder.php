<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScoringLv4_tangible;
use App\Models\ScoringLv4TangibleDetail;

class TangibleBenefitSeeder extends Seeder
{
    /**
     * Sample tangible benefits data for testing
     *
     * @return void
     */
    public function run()
    {
        // Get an existing diklat ID and area ID from your database
        // You should replace these with actual IDs from your database
        $diklatId = 1; // Use the first diklat
        $areaId = 1;  // Use the first area
        
        // Deleting existing data
        ScoringLv4_tangible::where('diklat_id', $diklatId)
            ->where('area_id', $areaId)
            ->delete();
            
        // Create sample tangible benefit 1: Equipment Cost
        $tangible1 = ScoringLv4_tangible::create([
            'diklat_id' => $diklatId,
            'area_id' => $areaId,
            'category' => 'Penghematan Biaya Bahan',
            'cost' => 450000, // Will be calculated from components
        ]);
        
        // Create sample components for the first tangible
        $tangible1->details()->create([
            'component_name' => 'Equipment Cost',
            'sub_component_name' => 'Base Price',
            'price' => 100000,
            'operator' => '*'
        ]);
        
        $tangible1->details()->create([
            'component_name' => 'Equipment Cost',
            'sub_component_name' => 'Quantity',
            'price' => 5,
            'operator' => '*'
        ]);
        
        $tangible1->details()->create([
            'component_name' => 'Equipment Cost',
            'sub_component_name' => 'Discount',
            'price' => 0.9,
            'operator' => '*'
        ]);
        
        // Create sample tangible benefit 2: Installation Fee
        $tangible2 = ScoringLv4_tangible::create([
            'diklat_id' => $diklatId,
            'area_id' => $areaId,
            'category' => 'Pengurangan Biaya Project',
            'cost' => 125000, // Will be calculated from components
        ]);
        
        // Create sample components for the second tangible
        $tangible2->details()->create([
            'component_name' => 'Installation Fee',
            'sub_component_name' => 'Base Fee',
            'price' => 25000,
            'operator' => '+'
        ]);
        
        $tangible2->details()->create([
            'component_name' => 'Maintenance',
            'sub_component_name' => 'Annual Fee',
            'price' => 50000,
            'operator' => '*'
        ]);
        
        $tangible2->details()->create([
            'component_name' => 'Maintenance',
            'sub_component_name' => 'Years',
            'price' => 2,
            'operator' => '*'
        ]);
        
        // Create sample tangible benefit 3: Custom category
        $tangible3 = ScoringLv4_tangible::create([
            'diklat_id' => $diklatId,
            'area_id' => $areaId,
            'category' => 'Lainnya: Time Savings',
            'cost' => 72000, // Will be calculated from components
        ]);
        
        // Create sample components for the third tangible
        $tangible3->details()->create([
            'component_name' => 'Hourly Rate',
            'sub_component_name' => 'Standard Rate',
            'price' => 12000,
            'operator' => '*'
        ]);
        
        $tangible3->details()->create([
            'component_name' => 'Hourly Rate',
            'sub_component_name' => 'Hours Saved',
            'price' => 6,
            'operator' => '*'
        ]);
        
        $this->command->info('Sample tangible benefit data created successfully.');
    }
}
