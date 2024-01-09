<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    protected $table = 'evaluation_criteria';
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "percentage",
        "stage_id",
        "type",
    ];

    public function Stage()
     {
         return $this->belongsTo(Stage::class, 'stage_id');
     }

     public function subareas()
     {
         return $this->belongsTo(SubareaCriteria::class, 'evaluation_criteria_id');
     }
}
