<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Diklat;
use App\Models\DiklatPlanning;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $roleId = Auth::user()->role_id;
        $yearStart = request()->get('year_start', 'all');
        $yearEnd = request()->get('year_end', 'all');
        $diklatType = request()->get('diklat_type', 'all');
        $selectedYear = $yearStart !== 'all' ? $yearStart : date('Y');
        $vendorId = Auth::user()->vendor_id;
        $currentDate = Carbon::now();

        // Base query with role filtering
        $baseQuery = function ($query) use ($roleId) {
            return $query->when($roleId == 1, function ($q) {
                // Filter for HTD role - only show data from their unit
                $unitId = Auth::user()->unit_id;
                return $q->where('unit_id', $unitId);
            })
                ->when($roleId == 2, function ($q) {
                    // Filter for Vendor role
                    return $q->where('vendor_id', Auth::user()->vendor_id);
                })
                ->when(in_array($roleId, [3, 4]), function ($q) {
                    // For PIC Area and SRM role
                    if (get_class($q->getModel()) === DiklatPlanning::class) {
                        // For DiklatPlanning, use diklat_planning_area relationship
                        return $q->whereHas('areas', function ($query) {
                            $query->where('areas.id', Auth::user()->area_id);
                        });
                    } elseif (get_class($q->getModel()) === Diklat::class) {
                        // For Diklat, use diklat_area relationship
                        return $q->whereHas('areas', function ($query) {
                            $query->where('areas.id', Auth::user()->area_id);
                        });
                    }
                    return $q;
                });
        };

        // Year range filter
        $yearFilter = function ($query) use ($yearStart, $yearEnd) {
            return $query->when($yearStart !== 'all', function ($q) use ($yearStart) {
                return $q->where('year', '>=', $yearStart);
            })
                ->when($yearEnd !== 'all', function ($q) use ($yearEnd) {
                    return $q->where('year', '<=', $yearEnd);
                });
        };

        // Diklat type filter
        $typeFilter = function ($query) use ($diklatType) {
            return $query->when($diklatType !== 'all', function ($q) use ($diklatType) {
                return $q->where('diklat_type', $diklatType);
            });
        };
        $participantDetailsPic = Diklat::select(
            'diklats.name',
            'diklats.count_of_participant',
            'diklats.estimate_start_date',
            'diklats.diklat_type',
            'diklats.total_cost',
            'diklats.year',
            'diklats.status_monitoring'
        )
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->orderBy('estimate_start_date', 'desc')
            ->get();

        // Get participant details with filters
        $participantDetails = Diklat::select(
            'name',
            'count_of_participant',
            'estimate_start_date',
            'diklat_type',
            'total_cost',
            'year'
        )
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->orderBy('estimate_start_date', 'desc')
            ->get();

        // Calculate total participants (cumulative)
        $employeeCount = $participantDetails->sum('count_of_participant');

        // Get certificate count through diklat relationship
        // dd($diklatType);
        $certificateCount = Certificate::whereHas('diklat', function ($q) use ($yearStart, $yearEnd, $diklatType, $roleId) {
            // $q->when($yearStart !== 'all', function ($query) use ($yearStart) {
            //     return $query->where('year', '>=', $yearStart);
            // })
            // ->when($yearEnd !== 'all', function ($query) use ($yearEnd) {
            //     return $query->where('year', '<=', $yearEnd);
            // })
            // ->when($diklatType !== 'all', function ($query) use ($diklatType) {
            //     return $query->where('diklat_type', $diklatType);
            // })
            $q->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            });
        })
        ->when($yearStart !== 'all', function ($query) use ($yearStart) {
            return $query->whereYear('certificate_date', '>=', $yearStart);
        })
        ->when($yearEnd !== 'all', function ($query) use ($yearEnd) {
            return $query->whereYear('certificate_date', '<=', $yearEnd);
        })
        ->when($diklatType !== 'all', function ($query) use ($diklatType) {
            return $query->where('certificate_type', $diklatType);
        })
        ->count();

        // Calculate total approved budget
        $totalApprovedBudget = $participantDetails->sum('total_cost');

        // Prepare monthly budget data for chart
        $monthlyBudgetData = $participantDetails
            ->groupBy(function ($item) {
                return Carbon::parse($item->estimate_start_date)->format('F Y');
            })
            ->map(function ($items) {
                return [
                    'month' => $items->first()->estimate_start_date,
                    'value' => $items->sum('total_cost')
                ];
            })
            ->values();

        // Get years for dropdown (10 years before and after current year)
        $years = collect(range(date('Y') - 10, date('Y') + 10))->toArray();

        // Get diklat planning data
        $diklatPlanning = DiklatPlanning::select('name')
            ->with('areas')
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($typeFilter)
            ->tap($yearFilter)
            ->get();

        // Get diklat realization data
        $diklat = Diklat::select('name')
            ->with('areas')
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->get();

        // Calculate realization percentage
        $diklatSameName = $diklat->filter(function ($item) use ($diklatPlanning) {
            return $diklatPlanning->contains('name', $item->name);
        });

        $diklatPlanningCount = $diklatPlanning->count();
        $diklatCount = $diklat->count();
        $diklatSameNameCount = $diklatSameName->count();
        $percentage = $diklatPlanningCount > 0 ? ($diklatSameNameCount / $diklatPlanningCount) * 100 : 0;
        $percentage = number_format($percentage, 0);

        // Prepare payment status data
        $sudahDibayar = (float) Diklat::where('payment_status', 'sudah dibayar')
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->sum('total_cost');
        $sudahTagih = (float) Diklat::where('payment_status', 'sudah tertagih')
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->sum('total_cost');

        $belumTagih = (float) Diklat::where('payment_status', 'belum tertagih')
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->sum('total_cost');
        $totalDiklat = (float) Diklat::when(in_array($roleId, [3, 4]), function ($query) {
            return $query->whereHas('areas', function ($q) {
                $q->where('areas.id', Auth::user()->area_id);
            });
        })
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->sum('total_cost');
        $totalDiklatPlanning = (float) DiklatPlanning::when(in_array($roleId, [3, 4]), function ($query) {
            return $query->where('unit_id', Auth::user()->area->unit_id);
        })
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->sum('total_cost');

        $diklatSameName = $diklat->filter(function ($item) use ($diklatPlanning) {
            return $diklatPlanning->contains('name', $item->name);
        });

        $diklatPlanningCount = $diklatPlanning->count();
        $diklatCount = $diklat->count();
        $diklatSameNameCount = $diklatSameName->count();

        // Update percentage calculation to handle division by zero
        $diklatPercentage = $diklatPlanningCount > 0
            ? number_format(($diklatSameNameCount / $diklatPlanningCount) * 100, 0)
            : 0;

        // Get current date
        $currentDate = Carbon::now();
        $sudahDibayar = (float) Diklat::where('payment_status', 'sudah dibayar')
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->sum('total_cost');

        $sudahDilaksanakanSudahTagih = (float) Diklat::where('payment_status', 'sudah tertagih')
            ->where('estimate_end_date', '<', $currentDate)
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->sum('total_cost');

        $sudahDilaksanakanBelumTagih = (float) Diklat::where(function ($query) use ($currentDate) {
            $query->where(function ($q) use ($currentDate) {
                $q->where('estimate_end_date', '<', $currentDate)
                    ->where('payment_status', 'belum tertagih');
            })->orWhere(function ($q) use ($currentDate) {
                $q->where('estimate_start_date', '<=', $currentDate)
                    ->where('estimate_end_date', '>=', $currentDate)
                    ->where('payment_status', '!=', 'sudah dibayar');
            });
        })
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->sum('total_cost');
        // 4. Pelatihan Belum dilaksanakan (belum tertagih)
        $belumDilaksanakan = (float) Diklat::where('estimate_start_date', '>', $currentDate)
            ->where('payment_status', 'belum tertagih')
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->sum('total_cost');



        // Prepare payment status data with new conditions
        // $sudahDibayar = (float) Diklat::where('payment_status', 'sudah dibayar')
        //     ->tap($baseQuery)
        //     ->tap($yearFilter)
        //     ->sum('total_cost');

        // $sudahDilaksanakanSudahTagih = (float) Diklat::where('payment_status', 'sudah tertagih')
        //     ->where('estimate_start_date', '<', $currentDate)
        //     ->tap($baseQuery)
        //     ->tap($yearFilter)
        //     ->sum('total_cost');

        // $sudahDilaksanakanBelumTagih = (float) Diklat::where(function ($query) use ($currentDate) {
        //     $query->where(function ($q) use ($currentDate) {
        //         // Sudah melewati tanggal mulai dan belum tertagih
        //         $q->where('estimate_start_date', '<', $currentDate)
        //             ->where('payment_status', 'belum tertagih');
        //     })->orWhere(function ($q) use ($currentDate) {
        //         // Dalam proses pelaksanaan
        //         $q->where('estimate_start_date', '<=', $currentDate)
        //             ->where('estimate_end_date', '>=', $currentDate);
        //     });
        // })
        //     ->tap($baseQuery)
        //     ->tap($yearFilter)
        //     ->sum('total_cost');

        // $belumDilaksanakan = (float) Diklat::where('estimate_start_date', '>', $currentDate)
        //     ->where('payment_status', 'belum tertagih')
        //     ->tap($baseQuery)
        //     ->tap($yearFilter)
        //     ->sum('total_cost');

        // Update chartData array
        $chartData = [
            [
                "category" => "Sudah dibayarkan ke vendor",
                "value" => $sudahDibayar
            ],
            [
                "category" => "Sudah selesai, tertagih, belum dibayar",
                "value" => $sudahDilaksanakanSudahTagih
            ],
            [
                "category" => "Sudah/sedang dilaksanakan, belum tertagih",
                "value" => $sudahDilaksanakanBelumTagih
            ],
            [
                "category" => "Belum dilaksanakan (belum tertagih)",
                "value" => $belumDilaksanakan
            ]
        ];

        // Get vendor's diklat summary
        $diklatSummary = Diklat::when($roleId == 2, function ($query) use ($vendorId) {
            return $query->where('vendor_id', $vendorId);
        })
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->selectRaw('
        COUNT(*) as total_diklat,
        SUM(CASE 
            WHEN estimate_end_date < NOW() THEN 1 
            ELSE 0 
        END) as completed_diklat,
        SUM(CASE 
            WHEN estimate_end_date >= NOW() THEN 1 
            ELSE 0 
        END) as upcoming_diklat
    ')
            ->first();


        // Get vendor's payment summary with filters
        $paymentSummary = Diklat::when($roleId == 2, function ($query) use ($vendorId) {
            return $query->where('vendor_id', $vendorId);
        })
            ->when(in_array($roleId, [3, 4]), function ($query) {
                return $query->whereHas('areas', function ($q) {
                    $q->where('areas.id', Auth::user()->area_id);
                });
            })
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->tap($typeFilter)
            ->selectRaw('
        SUM(CASE 
            WHEN payment_status = "sudah dibayar" THEN total_cost 
            ELSE 0 
        END) as total_paid,
        SUM(CASE 
            WHEN payment_status = "sudah tertagih" THEN total_cost 
            ELSE 0 
        END) as total_billed,
        SUM(CASE 
            WHEN payment_status = "belum tertagih" THEN total_cost 
            ELSE 0 
        END) as total_unbilled,
        SUM(total_cost) as total_cost
    ')
            ->first();

        $participantDetails = Diklat::select(
            'name',
            'count_of_participant',
            'estimate_start_date',
            'estimate_end_date',
            'diklat_type',
            'payment_status',
            'total_cost',
            'year'
        )
            ->tap($baseQuery)
            ->tap($yearFilter)
            ->tap($typeFilter)
            ->orderBy('estimate_start_date', 'desc')
            ->get();

        return view('dashboard.index', compact(
            'yearStart',
            'yearEnd',
            'diklatType',
            'selectedYear',
            'diklatPlanningCount',
            'diklatCount',
            'percentage',
            'employeeCount',
            'certificateCount',
            'years',
            'participantDetails',
            'totalApprovedBudget',
            'monthlyBudgetData',
            'sudahDibayar',
            'sudahTagih',
            'belumTagih',
            'totalDiklat',
            'totalDiklatPlanning',
            'diklatPercentage',
            'chartData',
            'sudahDibayar',
            'sudahDilaksanakanSudahTagih',
            'sudahDilaksanakanBelumTagih',
            'belumDilaksanakan',
            'diklatSummary',
            'paymentSummary',
            'participantDetails',
            'participantDetailsPic'
        ));
    }

    public function updateEmail(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Email is invalid or already taken.')
                ->withErrors($validator)
                ->withInput();
        }

        // Update the user's email
        $user = User::find(auth()->id());
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->back()->with('success', 'Email updated successfully.');
    }
}
