<?php

namespace App\Models;

use Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Diklat extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions

    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    // slug, name, letter_number, year, estimate_start_date, estimate_end_date, vendor_id, count_of_participant, unit_id, total_cost, payment_status (belum tertagih, sudah tertagih, belum dibayar, sudah dibayar), locked_at
    protected $fillable = [
        'slug',
        'name',
        'letter_number',
        'year',
        'estimate_start_date',
        'estimate_end_date',
        'vendor_id',
        'count_of_participant',
        'unit_id',
        'total_cost',
        'payment_status',
        'locked_at',
        'status_monitoring',
        'diklat_type',
        'diklat_planning_id',
    ];
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function proccessVip()
    {
        return $this->hasOne(ProccessVip::class);
    }
    public function participants()
    {
        return $this->hasMany(DiklatParticipant::class);
    }
    public function tangibles()
    {
        return $this->hasMany(ScoringLv4_tangible::class);
    }
    public function scoreLv4()
    {
        return $this->hasMany(ScoringLv4::class);
    }
    
    public function areas()
    {
        return $this->belongsToMany(Area::class, 'diklat_area');
    }

    // generate slug from parameter
    static public function generateSlug($name)
    {
        $slug = Str::slug($name);
        $count = static::where('slug', 'like', "$slug%")->count();
        $countPlus = $count + 1;
        return $count > 0 ? "$slug-$countPlus" : $slug;
    }
}
