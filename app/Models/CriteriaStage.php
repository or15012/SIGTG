<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;
class CriteriaStage extends Model
{
    use HasFactory;
    use LogsActivityTrait;
    
    protected $table = 'criteria_stage';

    protected $fillable = [
        'evaluation_criteria_id',
        'evaluation_stage_id',
        'user_id',
        'note',
        ];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function evaluationCriteria()
    {
        return $this->belongsTo(EvaluationCriteria::class, 'evaluation_criteria_id');
    }

}
