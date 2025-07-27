<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Vendor extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions

    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    protected $fillable = [
        'name'
    ];
}
