<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScoringLv4_tangible extends Model
{
    // diklat_id, category, cost
    protected $fillable = [
        'diklat_id',
        'area_id',
        'category',
        'cost',
    ];
    
    protected $table = 'scoring_lv4_tangibles';
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['component_summary'];
    
    public function diklat()
    {
        return $this->belongsTo(Diklat::class);
    }
    
    /**
     * Get the details for this tangible benefit.
     */
    public function details(): HasMany
    {
        return $this->hasMany(ScoringLv4TangibleDetail::class, 'scoring_lv4_tangible_id');
    }
    
    /**
     * Get a summary of components for this tangible benefit.
     * 
     * @return array
     */
    public function getComponentSummaryAttribute()
    {
        $details = $this->details;
        if ($details->isEmpty()) {
            return [];
        }
        
        $componentGroups = $details->groupBy('component_name');
        $summary = [];
        
        foreach ($componentGroups as $componentName => $components) {
            $componentSummary = [
                'name' => $componentName,
                'items' => []
            ];
            
            foreach ($components as $component) {
                $componentSummary['items'][] = [
                    'sub_name' => $component->sub_component_name,
                    'price' => $component->price,
                    'operator' => $component->operator
                ];
            }
            
            $summary[] = $componentSummary;
        }
        
        return $summary;
    }
}
