<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    protected $table = 'evaluation_criteria';
    use HasFactory;

    protected $fillable = [
        "name","percentage", "stage_id",
    ];

    public function Stage()
     {
         return $this->belongsTo(Stage::class, 'stage_id');
     }
}
