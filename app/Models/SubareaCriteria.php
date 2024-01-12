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
        "evaluation_criteria_id",
    ];

    public function area()
     {
         return $this->belongsTo(EvaluationCriteria::class);
     }
}
