<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;


class UnitController extends Controller
{
    public function getIndex() 
    {
        return view('unit.index');
    }

    public function getData(Request $request) 
    {
        $query = Unit::query();

        return datatables($query)
            ->filter(function ($query) use ($request) {
                if ($request->search['value']) {
                    $query->where(function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search['value']}%");
                    });
                }
    
                // Individual column filtering
                foreach ($request->columns as $column) {
                    if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                        if (in_array($column['data'], ['name'])) {
                            $query->where($column['data'], 'like', "%{$column['search']['value']}%");
                        }
                    }
                }
            })
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('name', $order);
            })
            ->addColumn('DT_RowId', function ($data) {
                return 'row_' . $data->id;
            })
            ->addIndexColumn()
            ->toJson();
    }

    public function postStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:units,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = Unit::create($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function getShow($id)
    {
        $data = Unit::findOrFail($id);
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function postUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:units,name,'.$id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = Unit::findOrFail($id);
        $data->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    public function deleteDestroy($id)
    {
        $data = Unit::findOrFail($id);
        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
