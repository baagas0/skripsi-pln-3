<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Authenticatable
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    // NIP, name, position, unit_id, area_id, email, birth_date, password
    protected $fillable = [
        'nip',
        'name',
        'position',
        'unit_id',
        'area_id',
        'email',
        'birth_date',
        'password',
    ];
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
