<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $r = Role::create([
            'code' => "Manager",
            'name' => 'Manager',
        ]);

        User::create([
            'name' => 'User Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('pln#573*'),
            'role_id' => $r->id, // Assuming 1 is admin role
        ]);
    }
}
