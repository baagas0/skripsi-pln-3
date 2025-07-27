<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ScoringLv5 extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions

    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    // diklat_id, total_tangible, cost_of_training, roti
    protected $fillable = [
        'diklat_id',
        'total_tangible',
        'cost_of_training',
        'roti',
    ];
    public function diklat()
    {
        return $this->belongsTo(Diklat::class);
    }

    public function getHasilrotiAttribute() {
        return $this->roti/100;
    }
}
