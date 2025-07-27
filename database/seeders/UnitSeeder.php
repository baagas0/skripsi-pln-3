<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        // Pusertif (5 Bidang)
        $pusertif = Unit::create([
            'name' => 'Pusertif',
        ]);

        // Buat user HTD dan HoE untuk unit Pusertif
        User::create([
            'name' => 'HTD Pusertif',
            'email' => 'pusertif@pln.co.id',
            'password' => bcrypt('password'),
            'role_id' => 1, // HTD role
            'unit_id' => $pusertif->id,
        ]);
        User::create([
            'name' => 'HoE Pusertif',
            'email' => 'hoe.pusertif@pln.co.id',
            'password' => bcrypt('password'),
            'role_id' => 6, // HoE role
            'unit_id' => $pusertif->id,
        ]);

        Area::insert([
            [
                'name' => 'RPM',
                'unit_id' => $pusertif->id,
            ],
            [
                'name' => 'SKTND',
                'unit_id' => $pusertif->id,
            ],
            [
                'name' => 'PROMSKAL',
                'unit_id' => $pusertif->id,
            ],
            [
                'name' => 'PENGUJIAN',
                'unit_id' => $pusertif->id,
            ],
            [
                'name' => 'KKU',
                'unit_id' => $pusertif->id,
            ],
        ]);

        // Puslitbang (4 Bidang)
        $puslitbang = Unit::create([
            'name' => 'Puslitbang',
        ]);

        User::create([
            'name' => 'HTD Puslitbang',
            'email' => 'puslitbang@pln.co.id',
            'password' => bcrypt('password'),
            'role_id' => 1, // HTD role
            'unit_id' => $puslitbang->id,
        ]);
        User::create([
            'name' => 'HoE puslitbang',
            'email' => 'hoe.puslitbang@pln.co.id',
            'password' => bcrypt('password'),
            'role_id' => 6, // HoE role
            'unit_id' => $puslitbang->id,
        ]);

        Area::insert([
            [
                'name' => 'RENPRO',
                'unit_id' => $puslitbang->id,
            ],
            [
                'name' => 'KKU',
                'unit_id' => $puslitbang->id,
            ],
            [
                'name' => 'RISTEK KIT',
                'unit_id' => $puslitbang->id,
            ],
            [
                'name' => 'RISTEK TND',
                'unit_id' => $puslitbang->id,
            ],
        ]);

        // UIP JBT (9 Bidang)
        $uip_jbt = Unit::create([
            'name' => 'UIP JBT',
        ]);

        // Buat user HTD dan HoE untuk unit UIP JBT
        User::create([
            'name' => 'HTD UIP JBT',
            'email' => 'uip.jbt@pln.co.id',
            'password' => bcrypt('password'),
            'role_id' => 1, // HTD role
            'unit_id' => $uip_jbt->id,
        ]);
        User::create([
            'name' => 'HoE UIP JBT',
            'email' => 'hoe.uip.jbt@pln.co.id',
            'password' => bcrypt('password'),
            'role_id' => 6, // HoE role
            'unit_id' => $uip_jbt->id,
        ]);

        Area::insert([
            [
                'name' => 'REN',
                'unit_id' => $uip_jbt->id,
            ],
            [
                'name' => 'OPKONS I',
                'unit_id' => $uip_jbt->id,
            ],
            [
                'name' => 'OPKONS II',
                'unit_id' => $uip_jbt->id,
            ],
            [
                'name' => 'PPK',
                'unit_id' => $uip_jbt->id,
            ],
            [
                'name' => 'KAU',
                'unit_id' => $uip_jbt->id,
            ],
            [
                'name' => 'UPP JBT 1',
                'unit_id' => $uip_jbt->id,
            ],
            [
                'name' => 'UPP JBT 2',
                'unit_id' => $uip_jbt->id,
            ],
            [
                'name' => 'UPP JBT 3',
                'unit_id' => $uip_jbt->id,
            ],
            [
                'name' => 'UPP JBT 4',
                'unit_id' => $uip_jbt->id,
            ],
        ]);

        // PUSHARLIS (9 Bidang)
        $pusharlis = Unit::create([
            'name' => 'PUSHARLIS',
        ]);

        // Buat user HTD dan HoE untuk unit PUSHARLIS
        User::create([
            'name' => 'HTD PUSHARLIS',
            'email' => 'pusharlis@pln.co.id',
            'password' => bcrypt('password'),
            'role_id' => 1, // HTD role
            'unit_id' => $pusharlis->id,
        ]);
        User::create([
            'name' => 'HoE PUSHARLIS',
            'email' => 'hoe.pusharlis@pln.co.id',
            'password' => bcrypt('password'),
            'role_id' => 6, // HoE role
            'unit_id' => $pusharlis->id,
        ]);

        Area::insert([
            [
                'name' => 'REN',
                'unit_id' => $pusharlis->id,
            ],
            [
                'name' => 'PROSHOP',
                'unit_id' => $pusharlis->id,
            ],
            [
                'name' => 'KKU',
                'unit_id' => $pusharlis->id,
            ],
            [
                'name' => 'UP2W I',
                'unit_id' => $pusharlis->id,
            ],
            [
                'name' => 'UP2W II',
                'unit_id' => $pusharlis->id,
            ],
            [
                'name' => 'UP2W III',
                'unit_id' => $pusharlis->id,
            ],
            [
                'name' => 'UP2W IV',
                'unit_id' => $pusharlis->id,
            ],
            [
                'name' => 'UP2W V',
                'unit_id' => $pusharlis->id,
            ],
            [
                'name' => 'UP2W VI',
                'unit_id' => $pusharlis->id,
            ],
        ]);

        // Buat user PIC dan SRM untuk semua area
        $allArea = Area::all();
        $users = [];
        foreach ($allArea as $area) {
            // Create PIC for each area
            $users[] = [
                'name' => 'PIC ' . $area->name . ' (' . $area->unit->name . ')',
                'password' => bcrypt('password'),
                'role_id' => 3, // PIC Bidang role
                'area_id' => $area->id,
                'email' => str_replace(' ', '_', strtolower($area->unit->name)) . '-pic-' . str_replace(' ', '_', strtolower($area->name)) . '@gmail.com',
            ];

            // Create SRM for each area
            $users[] = [
                'name' => 'SRM ' . $area->name . ' (' . $area->unit->name . ')',
                'password' => bcrypt('password'),
                'role_id' => 4, // SRM role
                'area_id' => $area->id,
                'email' => str_replace(' ', '_', strtolower($area->unit->name)) . '-srm-' . str_replace(' ', '_', strtolower($area->name)) . '@gmail.com',
            ];
        }

        User::insert($users);
    }
}
