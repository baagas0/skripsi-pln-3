<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'area_id',
        'vendor_id',
        'unit_id',
        'manage_unit_ids',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'manage_unit_ids' => 'json',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}