<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'code' => 'htd_admin',
                'name' => 'HTD Admin',
            ],
            [
                'code' => 'vendor',
                'name' => 'Vendor',
            ],
            [
                'code' => 'pic_bidang',
                'name' => 'PIC  Bidang',
            ],
            [
                'code' => 'srm',
                'name' => 'SRM (Senior Manager)',
            ],
            [
                'code' => 'employee',
                'name' => 'Employee',
            ],
            [
                'code' => 'hoe',
                'name' => 'Head of Employee',
            ],
        ]);
        // Create admin user
        User::create([
            'name' => 'User HTD Admin',
            'email' => 'htd_admin@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 1, // Assuming 1 is admin role
        ]);

        // Create vendor user
        // User::create([
        //     'name' => 'User Vendor',
        //     'email' => 'vendor@gmail.com',
        //     'password' => Hash::make('password'),
        //     'role_id' => 2, // Assuming 1 is admin role
        // ]);

        // Create vendor user
        // User::create([
        //     'name' => 'User PIC Bidang',
        //     'email' => 'pic_bidang@gmail.com',
        //     'password' => Hash::make('password'),
        //     'role_id' => 3, // Assuming 1 is admin role
        // ]);

        // Create vendor user
        User::create([
            'name' => 'User SRM',
            'email' => 'srm@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 4, // Assuming 1 is admin role
        ]);
    }
}
