<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function isActive($route)
    {
        return request()->routeIs($route) ? 'active' : '';
    }

    public static function isOpenActive($routes)
    {
        return in_array(request()->route()->getName(), (array)$routes) ? 'here show' : '';
    }

    public static function getSidebarMenu()
    {
        $role_id = Auth::user()->role_id;
        $allMenuItems = [
            [
                'title' => 'Home',
                'icon' => 'ki-outline ki-home-2',
                'route' => 'dashboard',
                'roles' => [7, 1, 2, 3, 4, 6],
            ],
            [
                'title' => 'Perencanaan Diklat',
                'icon' => 'ki-outline ki-file',
                'route' => '.diklat-planning',
                'roles' => [7, 1, 3, 4],
            ],
            [
                'title' => 'Realisasi Diklat',
                'icon' => 'ki-outline ki-file-added',
                'route' => '.diklat',
                'roles' => [7, 1],
            ],
            [
                'title' => 'Penilaian Pelatihan',
                'icon' => 'ki-outline ki-abstract-8',
                'route' => '.scorring',
                'roles' => [7, 1, 2, 6],
            ],
            [
                'title' => 'Progress Penilaian',
                'icon' => 'ki-outline ki-design',
                'route' => '.scorring.progress',
                'roles' => [7, 1],
            ],
            [
                'title' => 'Data Sertifikat',
                'icon' => 'ki-outline ki-receipt-square',
                'route' => '.certificate',
                'roles' => [7, 1, 2, 3],
            ],
            [
                'title' => 'Data Tagihan',
                'icon' => 'ki-solid ki-cheque',
                'route' => '.proccess-vip',
                'roles' => [7, 1,2],
            ],
            [
                'title' => 'Data Laporan',
                'icon' => 'ki-outline ki-tablet-text-down',
                'route' => '.report',
                'roles' => [7, 1],
            ],
            [
                'title' => 'Data Pegawai',
                'icon' => 'ki-outline ki-people',
                'route' => '.employee',
                'roles' => [7, 1],
            ],
            [
                'title' => 'Data Vendor',
                'icon' => 'ki-outline ki-profile-user',
                'route' => '.vendor',
                'roles' => [7, 1],
            ],
            [
                'title' => 'Data Aktivitas',
                'icon' => 'ki-outline ki-profile-user',
                'route' => '.activity',
                'roles' => [7, 1],
            ],
        ];

        // Filter menu items based on user's role
        return array_filter($allMenuItems, function ($menuItem) use ($role_id) {
            return in_array($role_id, $menuItem['roles']);
        });
    }
}
