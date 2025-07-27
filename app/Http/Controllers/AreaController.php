<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function getIndex() 
    {
        $units = Unit::all();
        return view('area.index', compact('units'));
    }

    public function getData(Request $request) 
    {
        $query = Area::with(['unit']);

        return datatables($query)
            ->filter(function ($query) use ($request) {
                if ($request->search['value']) {
                    $query->where(function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search['value']}%")
                          ->orWhereHas('unit', function($unit) use ($request) {
                              $unit->where('name', 'like', "%{$request->search['value']}%");
                          });
                    });
                }
            })
            ->addColumn('unit_name', function ($data) {
                return $data->unit ? $data->unit->name : '-';
            })
            ->addColumn('action', function ($data) {
                return '
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-light btn-active-light-primary btn-sm btn-outline" data-kt-docs-table-filter="edit_row" data-id="'.$data->id.'">
                            Edit
                        </a>
                        <a href="#" class="btn btn-light btn-active-light-danger btn-sm btn-outline" data-kt-docs-table-filter="delete_row" data-id="'.$data->id.'">
                            Delete
                        </a>
                    </div>
                ';
            })
            ->addColumn('DT_RowId', function ($data) {
                return 'row_' . $data->id;
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->toJson();
    }

    public function postStore(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = Area::create([
            'name' => $request->name,
            'unit_id' => $request->unit_id,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function getShow($id)
    {
        $data = Area::with(['unit'])->findOrFail($id);
        
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function postUpdate(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = Area::findOrFail($id);

        $data->update([
            'name' => $request->name,
            'unit_id' => $request->unit_id,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    public function deleteDestroy($id)
    {
        $data = Area::findOrFail($id);
        
        // Check if area is being used by users
        if ($data->users()->count() > 0) {
            return response()->json([
                'status' => 400,
                'message' => 'Area tidak dapat dihapus karena masih digunakan oleh user'
            ], 400);
        }

        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}