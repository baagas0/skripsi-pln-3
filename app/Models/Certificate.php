<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Certificate extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions

    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    // diklat_id, employee_id, vendor_id, fungsi, certificate_number, certificate_date, certificate_expire, certificate_path (nullable)
    protected $fillable = [
        'diklat_id',
        'employee_id',
        'vendor_id',
        'fungsi',
        'certificate_number',
        'certificate_date',
        'certificate_expire',
        'certificate_path',
        'reminded',
        'certificate_type',
    ];
    public function diklat()
    {
        return $this->belongsTo(Diklat::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
