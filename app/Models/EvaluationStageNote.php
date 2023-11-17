<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationStageNote extends Model
{
    use HasFactory;

    protected $table = 'evalution_stage_note';
    protected $fillable = [
        "evaluation_stage_id",
        "user_id",
        "note",
    ];

}
