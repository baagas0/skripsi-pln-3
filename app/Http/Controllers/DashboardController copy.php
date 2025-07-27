<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Diklat;
use App\Models\DiklatPlanning;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardControllerCopy extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roleId = Auth::user()->role_id;
        $selectedYear = request()->get('year') ?? date('Y');

        $diklatPlanning = DiklatPlanning::select('name')
            ->when($roleId == 1, function ($query) {
                return $query;
            })
            ->when($roleId == 2, function ($query) {
                return $query->where('vendor_id', Auth::user()->vendor_id);
            })
            ->when($roleId == 3, function ($query) {
                $unitId = Auth::user()->area->unit_id;
                return $query->where('unit_id', $unitId);
            })
            ->when($roleId == 4, function ($query) {
                return $query;
            })
            ->where('year', $selectedYear)->get();
        $diklat = Diklat::select('name')
            ->when($roleId == 1, function ($query) {
                return $query;
            })
            ->when($roleId == 2, function ($query) {
                return $query->where('vendor_id', Auth::user()->vendor_id);
            })
            ->when($roleId == 3, function ($query) {
                $unitId = Auth::user()->area->unit_id;
                return $query->where('unit_id', $unitId);
            })
            ->when($roleId == 4, function ($query) {
                return $query;
            })
            ->where('year', $selectedYear)->get();

        $diklatSameName = $diklat->filter(function ($item) use ($diklatPlanning) {
            return $diklatPlanning->contains('name', $item->name);
        });
        $diklatPlanningCount = $diklatPlanning->count();
        $diklatCount = $diklat->count();
        $diklatSameNameCount = $diklatSameName->count();
        $percentage = $diklatPlanningCount > 0 ? ($diklatSameNameCount / $diklatPlanningCount) * 100 : 0;
        $percentage = number_format($percentage, 0);

        $employeeCount = Employee::whereYear('created_at', $selectedYear)->count();
        $certificateCount = Certificate::whereYear('created_at', $selectedYear)->count();

        $years = [];
        for ($i = -10; $i <= 10; $i++) {
            $years[] = date('Y') + $i;
        }

        $sudahDibayar = (float) Diklat::where('payment_status', 'sudah dibayar')
            ->when($roleId == 1, function ($query) {
                return $query;
            })
            ->when($roleId == 2, function ($query) {
                return $query->where('vendor_id', Auth::user()->vendor_id);
            })
            ->when($roleId == 3, function ($query) {
                $unitId = Auth::user()->area->unit_id;
                return $query->where('unit_id', $unitId);
            })
            ->when($roleId == 4, function ($query) {
                return $query;
            })
            ->sum('total_cost');
        $sudahTagih = (float) Diklat::where('payment_status', 'sudah tertagih')
            ->when($roleId == 1, function ($query) {
                return $query;
            })
            ->when($roleId == 2, function ($query) {
                return $query->where('vendor_id', Auth::user()->vendor_id);
            })
            ->when($roleId == 3, function ($query) {
                $unitId = Auth::user()->area->unit_id;
                return $query->where('unit_id', $unitId);
            })
            ->when($roleId == 4, function ($query) {
                return $query;
            })
            ->sum('total_cost');
        $belumTagih = (float) Diklat::where('payment_status', 'belum tertagih')
            ->when($roleId == 1, function ($query) {
                return $query;
            })
            ->when($roleId == 2, function ($query) {
                return $query->where('vendor_id', Auth::user()->vendor_id);
            })
            ->when($roleId == 3, function ($query) {
                $unitId = Auth::user()->area->unit_id;
                return $query->where('unit_id', $unitId);
            })
            ->when($roleId == 4, function ($query) {
                return $query;
            })
            ->sum('total_cost');
        $totalDiklat = (float) Diklat::when($roleId == 1, function ($query) {
            return $query;
        })
            ->when($roleId == 2, function ($query) {
                return $query->where('vendor_id', Auth::user()->vendor_id);
            })
            ->when($roleId == 3, function ($query) {
                $unitId = Auth::user()->area->unit_id;
                return $query->where('unit_id', $unitId);
            })
            ->when($roleId == 4, function ($query) {
                return $query;
            })->sum('total_cost');
        $todalDiklatPlanning = (float) DiklatPlanning::when($roleId == 1, function ($query) {
            return $query;
        })
            ->when($roleId == 2, function ($query) {
                return $query->where('vendor_id', Auth::user()->vendor_id);
            })
            ->when($roleId == 3, function ($query) {
                $unitId = Auth::user()->area->unit_id;
                return $query->where('unit_id', $unitId);
            })
            ->when($roleId == 4, function ($query) {
                return $query;
            })->sum('total_cost');

        $data = [
            'selectedYear' => $selectedYear,
            'title' => 'Dashboard',
            'subtitle' => 'Dashboard',
            'diklatPlanningCount' => $diklatPlanningCount,
            'diklatCount' => $diklatCount,
            'diklatPercentage' => $percentage,
            'employeeCount' => $employeeCount,
            'certificateCount' => $certificateCount,
            'years' => $years,

            'sudahDibayar' => $sudahDibayar,
            'sudahTagih' => $sudahTagih,
            'belumTagih' => $belumTagih,
            'totalDiklat' => $totalDiklat,
            'totalDiklatPlanning' => $todalDiklatPlanning,
        ];

        // dd($data);
        return view('dashboard.index', $data);
    }
}
