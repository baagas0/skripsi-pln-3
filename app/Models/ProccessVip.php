<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProccessVip extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions

    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    // submission_id, diklat_id, status (belum tertagih, sudah tertagih, belum dibayar, sudah dibayar)
    protected $fillable = [
        'submission_id',
        'diklat_id',
        'status',
    ];
    public function diklat()
    {
        return $this->belongsTo(Diklat::class);
    }
}
