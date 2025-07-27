<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    private function titleLogCreated($model, $log) {
        $title = '';
        if ($model == 'Certificate') {
            $title = 'Menambahkan Sertifikat';
        } elseif ($model == 'Diklat') {
            $title = 'Menambahkan Diklat';
        } elseif ($model == 'DiklatPlanning') {
            $title = 'Menambahkan Perencanaan Diklat';
        } elseif ($model == 'Employee') {
            $title = 'Menambahkan Pegawai';
        } elseif ($model == 'Tagihan') {
            $title = 'Menambahkan Tagihan';
        } elseif ($model == 'ScoringLv1') {
            $title = 'Menambahkan Penilaian Level 1';
        } elseif ($model == 'ScoringLv2') {
            $title = 'Menambahkan Penilaian Level 2';
        } elseif ($model == 'ScoringLv3') {
            $title = 'Menambahkan Penilaian Level 3';
        } elseif ($model == 'ScoringLv4') {
            $title = 'Menambahkan Penilaian Level 4';
        } elseif ($model == 'ScoringLv5') {
            $title = 'Menambahkan Penilaian Level 5';
        } elseif ($model == 'Vendor') {
            $title = 'Menambahkan Vendor';
        }
        return $title;
    }
    private function titleLogUpdated($model, $log) {
        $title = '';
        if ($model == 'Certificate') {
            $title = 'Mengubah Sertifikat';
        } elseif ($model == 'Diklat') {
            $title = 'Mengubah Diklat';
        } elseif ($model == 'DiklatPlanning') {
            $title = 'Mengubah Perencanaan Diklat';
        } elseif ($model == 'Employee') {
            $title = 'Mengubah Pegawai';
        } elseif ($model == 'Tagihan') {
            $title = 'Mengubah Tagihan';
        } elseif ($model == 'ScoringLv1') {
            $title = 'Mengubah Penilaian Level 1';
        } elseif ($model == 'ScoringLv2') {
            $title = 'Mengubah Penilaian Level 2';
        } elseif ($model == 'ScoringLv3') {
            $title = 'Mengubah Penilaian Level 3';
        } elseif ($model == 'ScoringLv4') {
            $title = 'Mengubah Penilaian Level 4';
        } elseif ($model == 'ScoringLv5') {
            $title = 'Mengubah Penilaian Level 5';
        } elseif ($model == 'Vendor') {
            $title = 'Mengubah Vendor';
        }
        return $title;
    }
    private function titleLogDeleted($model, $log) {
        $title = '';
        if ($model == 'Certificate') {
            $title = 'Menghapus Sertifikat';
        } elseif ($model == 'Diklat') {
            $title = 'Menghapus Diklat';
        } elseif ($model == 'DiklatPlanning') {
            $title = 'Menghapus Perencanaan Diklat';
        } elseif ($model == 'Employee') {
            $title = 'Menghapus Pegawai';
        } elseif ($model == 'Tagihan') {
            $title = 'Menghapus Tagihan';
        } elseif ($model == 'ScoringLv1') {
            $title = 'Menghapus Penilaian Level 1';
        } elseif ($model == 'ScoringLv2') {
            $title = 'Menghapus Penilaian Level 2';
        } elseif ($model == 'ScoringLv3') {
            $title = 'Menghapus Penilaian Level 3';
        } elseif ($model == 'ScoringLv4') {
            $title = 'Menghapus Penilaian Level 4';
        } elseif ($model == 'ScoringLv5') {
            $title = 'Menghapus Penilaian Level 5';
        } elseif ($model == 'Vendor') {
            $title = 'Menghapus Vendor';
        }
        return $title;
    }
    private function titleLog($action, $model, $log) {
        $title = '';
        if ($action == 'created') {
            $title = $this->titleLogCreated($model, $log);
        } elseif ($action == 'updated') {
            $title = $this->titleLogUpdated($model, $log);
        } elseif ($action == 'deleted') {
            $title = $this->titleLogDeleted($model, $log);
        }
        return $title;
    }
    public function getIndex() {
        return view('activity.index', [
            // 'data' => $data,
            // 'dataLog' => $dataLog,
        ]);
    }

    public function getData() {
        $data = Activity::all();

        // LIST MODEL
        $m = [
            'App\Models\Certificate' => 'Certificate',
            'App\Models\Diklat' => 'Diklat',
            'App\Models\DiklatPlanning' => 'DiklatPlanning',
            'App\Models\Employee' => 'Employee',
            'App\Models\ProccessVip' => 'Tagihan',
            'App\Models\ScoringLv1' => 'ScoringLv1',
            'App\Models\ScoringLv2' => 'ScoringLv2',
            'App\Models\ScoringLv3' => 'ScoringLv3',
            'App\Models\ScoringLv4' => 'ScoringLv4',
            'App\Models\ScoringLv5' => 'ScoringLv5',
            'App\Models\Vendor' => 'Vendor',
        ];

        $dataLog = [];
        foreach ($data as $item) {
            $action = $item->description;
            $name = $item && $item->causer && $item->causer->name ? $item->causer->name : '';
            $subject_type = $item->subject_type;

            $subject = $m[$subject_type];
            
            $title = $this->titleLog($action, $subject, $item);
            $dataLog[] = [
                'name' => $name,
                'title' => $title,
                'created_at' => $item->created_at,
            ];
        }

        return datatables($dataLog)
            ->addIndexColumn()
            ->toJson();
    }
}
