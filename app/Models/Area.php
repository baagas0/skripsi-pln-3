<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name',
        'unit_id',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    
    public function diklats()
    {
        return $this->belongsToMany(Diklat::class, 'diklat_area');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}