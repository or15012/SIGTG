<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationSubareaNote extends Model
{
    use HasFactory;

    protected $table = 'evaluation_subarea_note';
    protected $fillable = [
        "evaluation_subarea_id",
        "user_id",
        "note",
    ];
}
