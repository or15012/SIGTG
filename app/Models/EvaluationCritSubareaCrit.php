<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCritSubareaCrit extends Model
{
    use HasFactory;

    protected $table = 'evaluation_crit_subarea_crit';

    public function evaluationCriteria()
    {
        return $this->belongsTo(EvaluationCriteria::class, 'evaluation_criteria_id');
    }

    public function subareaCriteria()
    {
        return $this->belongsTo(SubareaCriteria::class, 'subarea_criteria_id');
    }
}
