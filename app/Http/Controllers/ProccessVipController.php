<?php

namespace App\Http\Controllers;

use App\Models\ProccessVip;
use App\Models\Diklat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProccessVipController extends Controller
{
    public function getIndex() 
    {
        $roleId = Auth::user()->role_id;
        $data = ProccessVip::with(['diklat'])
        ->when($roleId == 1, function ($q) {
            // HTD melihat data dari unit yang mereka kelola
            $unitId = Auth::user()->unit_id;
            return $q->whereHas('diklat', function($qq) use ($unitId) {
                $qq->where('unit_id', $unitId);
            });
        })
        ->when($roleId == 2, function ($q) {
            $q->whereHas('diklat', function($qq) {
                $qq->where('vendor_id', Auth::user()->vendor_id);
            });
        })
        ->when($roleId == 7, function ($q) {
            // HTD melihat data dari unit yang mereka kelola
            $unitIdsString = Auth::user()->manage_unit_ids;
            $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
            return $q->whereHas('diklat', function($qq) use ($unitIds) {
                $qq->whereIn('unit_id', $unitIds ?? []);
            });
        })
        ->when($roleId == 8, function ($q) {
            // Vice President melihat data dari unit yang mereka kelola
            $unitIdsString = Auth::user()->manage_unit_ids;
            $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
            return $q->whereHas('diklat', function($qq) use ($unitIds) {
                $qq->whereIn('unit_id', $unitIds ?? []);
            });
        })
        ->get();
        $diklats = Diklat::when($roleId == 2, function ($q) {
            $q->where('vendor_id', Auth::user()->vendor_id);
        })->get();
        return view('proccess_vip.index', compact('diklats', 'data'));
    }

    public function getData(Request $request) 
    {
        $roleId = Auth::user()->role_id;
        $query = ProccessVip::with(['diklat'])
        ->when($roleId == 1, function ($q) {
            // HTD melihat data dari unit yang mereka kelola
            $unitId = Auth::user()->unit_id;
            return $q->whereHas('diklat', function($qq) use ($unitId) {
                $qq->where('unit_id', $unitId);
            });
        })
        ->when($roleId == 2, function ($q) {
            $q->whereHas('diklat', function($qq) {
                $qq->where('vendor_id', Auth::user()->vendor_id);
            });
        })
        ->when($roleId == 7, function ($q) {
            // HTD melihat data dari unit yang mereka kelola
            $unitIdsString = Auth::user()->manage_unit_ids;
            $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
            return $q->whereHas('diklat', function($qq) use ($unitIds) {
                $qq->whereIn('unit_id', $unitIds ?? []);
            });
        })
        ->when($roleId == 8, function ($q) {
            // Vice President melihat data dari unit yang mereka kelola
            $unitIdsString = Auth::user()->manage_unit_ids;
            $unitIds = is_string($unitIdsString) ? json_decode($unitIdsString) : $unitIdsString;
            return $q->whereHas('diklat', function($qq) use ($unitIds) {
                $qq->whereIn('unit_id', $unitIds ?? []);
            });
        })->get();

        return datatables($query)
            ->filter(function ($query) use ($request) {
                if ($request->search['value']) {
                    $query->where(function($q) use ($request) {
                        $q->where('status', 'like', "%{$request->search['value']}%")
                          ->orWhereHas('diklat', function($qq) use ($request) {
                              $qq->where('name', 'like', "%{$request->search['value']}%");
                          })
                        ->orWhere('submission_id', 'like', "%{$request->search['value']}%");
                    });
                }

                // Individual column filtering
                foreach ($request->columns as $column) {
                    if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                        if ($column['data'] === 'status') {
                            $query->where('status', $column['search']['value']);
                        }
                    }
                }
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public function postStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'submission_id' => 'required|unique:proccess_vips,submission_id',
            'diklat_id' => 'required|exists:diklats,id',
            // 'status' => 'required|in:belum tertagih,sudah tertagih,belum dibayar,sudah dibayar',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['status'] = 'sudah tertagih';
        $proccessVip = ProccessVip::create($data);

        $diklat = Diklat::findOrFail($request->diklat_id);
        $diklat->update([
            'payment_status' => 'sudah tertagih',
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil ditambahkan',
            'data' => $proccessVip
        ]);
    }

    public function getShow($id)
    {
        $proccessVip = ProccessVip::with(['diklat'])->findOrFail($id);
        return response()->json([
            'status' => 200,
            'data' => $proccessVip
        ]);
    }

    public function postUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'submission_id' => 'required',
            'diklat_id' => 'required|exists:diklats,id',
            // 'status' => 'required|in:belum tertagih,sudah tertagih,belum dibayar,sudah dibayar',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $proccessVip = ProccessVip::findOrFail($id);
        $data = $request->all();

        if ($proccessVip->diklat_id !== $data['diklat_id']) {
            $diklat = Diklat::findOrFail($proccessVip->diklat_id);
            $diklat->update([
                'payment_status' => 'belum tertagih',
            ]);
            ProccessVip::where('diklat_id', $proccessVip->diklat_id)->update([
                'status' => 'belum tertagih',
            ]);
        }

        $diklat = Diklat::findOrFail($request->diklat_id);
        $diklat->update([
            'payment_status' => 'sudah tertagih',
        ]);
        $proccessVip->update($data);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil diupdate',
            'data' => $proccessVip
        ]);
    }

    public function deleteDestroy($id)
    {
        $proccessVip = ProccessVip::findOrFail($id);

        $diklat = Diklat::findOrFail($proccessVip->diklat_id);
        $diklat->update([
            'payment_status' => 'belum tertagih',
        ]);

        $proccessVip->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function postUpdateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:belum tertagih,sudah tertagih,belum dibayar,sudah dibayar',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $proccessVip = ProccessVip::findOrFail($id);
        $proccessVip->update(['status' => $request->status]);

        $diklat = Diklat::findOrFail($proccessVip->diklat_id);
        if ($request->status === 'sudah tertagih') {
            $diklat->update([
                'payment_status' => 'sudah tertagih',
            ]);
        } elseif ($request->status === 'belum tertagih') {
            $diklat->update([
                'payment_status' => 'belum tertagih',
            ]);
        } elseif ($request->status === 'sudah dibayar') {
            $diklat->update([
                'payment_status' => 'sudah dibayar',
            ]);
        } elseif ($request->status === 'belum dibayar') {
            $diklat->update([
                'payment_status' => 'belum dibayar',
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Status berhasil diupdate',
            'data' => $proccessVip
        ]);
    }

    public function postBulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:proccess_vips,id',
            'status' => 'required|in:belum tertagih,sudah tertagih,belum dibayar,sudah dibayar',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        ProccessVip::whereIn('id', $request->ids)->update(['status' => $request->status]);

        return response()->json([
            'status' => 200,
            'message' => 'Status berhasil diupdate'
        ]);
    }
}