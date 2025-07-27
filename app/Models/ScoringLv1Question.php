<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringLv1Question extends Model
{
    // group, name
    protected $fillable = [
        'name',
        'group',
    ];
}
