<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringLv4_tangible extends Model
{
    // diklat_id, category, cost
    protected $fillable = [
        'diklat_id',
        'area_id',
        'category',
        'cost',
    ];
    public function diklat()
    {
        return $this->belongsTo(Diklat::class);
    }
}
