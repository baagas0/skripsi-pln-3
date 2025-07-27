<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ScoringLv2 extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions

    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    // diklat_participant_id, pretest_score, posttest_score
    protected $fillable = [
        'diklat_participant_id',
        'pretest_score',
        'posttest_score',
        'diff_score',
        'average_score',
        'rank',
    ];
    public function diklatParticipant()
    {
        return $this->belongsTo(DiklatParticipant::class);
    }

    static public function recalculateRank($diklat_participant_id)
    {
        $scoring = ScoringLv2::where('diklat_participant_id', $diklat_participant_id)->first();
        $diklat_id = $scoring->diklatParticipant->diklat_id;
        $scorings = ScoringLv2::whereHas('diklatParticipant', function ($query) use ($diklat_id) {
            $query->where('diklat_id', $diklat_id);
        })->orderBy('average_score')->get();

        $scorings->each(function ($item, $index) {
            $item->rank = $index + 1;
            $item->save();
        });
    }
}
