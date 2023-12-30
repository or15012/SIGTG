<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubareaCriteria extends Model
{
    use HasFactory,SoftDeletes;

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
