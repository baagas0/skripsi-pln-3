<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ScoringLv3 extends Model
{
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions

    {
        return LogOptions::defaults();
        // Chain fluent methods for configuration options
    }
    // scoring_lv3_question_id, diklat_participant_id, score
    protected $fillable = [
        'scoring_lv3_question_id',
        'diklat_participant_id',
        'score',
    ];
    public function diklatParticipant()
    {
        return $this->belongsTo(DiklatParticipant::class);
    }
    public function scoringLv3Question()
    {
        return $this->belongsTo(ScoringLv3Question::class);
    }
}
