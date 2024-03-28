<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class SubareaCriteria extends Model
{
    use HasFactory,SoftDeletes;
    use LogsActivityTrait;

    protected $fillable = [
        "name",
        "description",
        "percentage",
        "stage_id",
        "type",
        "subarea_id"
    ];

    public function area()
     {
         return $this->belongsTo(EvaluationCriteria::class);
     }

     public function evaluationCriterias()
     {
         return $this->belongsToMany(EvaluationCritSubareaCrit::class, 'evaluation_crit_subarea_crit',  'subarea_criteria_id',  'evaluation_criteria_id');
     }
}
