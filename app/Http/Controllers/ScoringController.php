<?php

namespace App\Http\Controllers;

use App\Models\Diklat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoringController extends Controller
{
    public function getIndex()
    {
        $roleId = Auth::user()->role_id;
        $unitId = Auth::user()->unit_id;

        // Filter diklats hanya untuk unit user yang login
        $d_1 = Diklat::query();
        if ($roleId == 2) {
            $d_1->where('vendor_id', Auth::user()->vendor_id);
        } else if ($roleId !== 7) {
            $d_1->where('unit_id', $unitId);
        }
        $diklats = $d_1->get();


        $diklat_id = request('diklat_id');
        $diklat = null;
        $urlLv1 = '';
        $urlLv2 = '';
        if ($diklat_id) {
            $diklat = Diklat::findOrFail($diklat_id);

            $hashDiklat = encrypt_custom($diklat->slug);
            $urlLv1 = url('form/lv1/' . $hashDiklat);

            $urlLv2 = url('form/lv2/' . $hashDiklat);
        }
        return view('scoring.index', compact('diklats', 'diklat', 'urlLv1', 'urlLv2'));
    }
}
