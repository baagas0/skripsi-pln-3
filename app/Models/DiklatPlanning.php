<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DiklatPlanning extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions

    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    // name, year, estimate_start_date, estimate_end_date, vendor_id, count_of_participant, unit_id, total_cost, approve_by_htd
    protected $fillable = [
        'name',
        'year',
        'estimate_start_date',
        'estimate_end_date',
        'vendor_id',
        'count_of_participant',
        'unit_id',
        'total_cost',
        'locked_at',
        'approve_by_htd',
        'notes',
        'diklat_type',
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'diklat_planning_area');
    }
}
