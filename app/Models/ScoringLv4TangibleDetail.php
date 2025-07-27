<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoringLv4TangibleDetail extends Model
{
    protected $fillable = [
        'scoring_lv4_tangible_id',
        'component_name',
        'sub_component_name',
        'price',
        'operator'
    ];

    /**
     * Get the tangible that owns this detail.
     */
    public function tangible(): BelongsTo
    {
        return $this->belongsTo(ScoringLv4_tangible::class, 'scoring_lv4_tangible_id');
    }
}