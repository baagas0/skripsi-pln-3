<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ScoringLv4 extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions

    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    // diklat_id, score_positive, impacts (array of string)
    protected $fillable = [
        'diklat_id',
        'area_id',
        'score_positive',
        'impacts',
        'employee_ids',
    ];
    protected $casts = [
        'impacts' => 'array',
        'employee_ids' => 'array',
    ];
    public function diklat()
    {
        return $this->belongsTo(Diklat::class);
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // public function getImpactsArrayAttribute($value)
    // {
    //     return json_decode($value, true);
    // }
}
