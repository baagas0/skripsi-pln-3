<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;

class DiklatParticipant extends Model
{
    use LogsActivity;
    // diklat_id, employee_id
    protected $fillable = [
        'diklat_id',
        'employee_id',
    ];

    public function getActivitylogOptions(): LogOptions

    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    public function diklat()
    {
        return $this->belongsTo(Diklat::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function scoreLv2()
    {
        return $this->hasOne(ScoringLv2::class);
    }
    public function scoreLv3()
    {
        return $this->hasOne(ScoringLv3::class);
    }
    
    public function scoreLv1()
    {
        return $this->hasMany(ScoringLv1::class);
    }
}
