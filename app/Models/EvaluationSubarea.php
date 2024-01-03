<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationSubarea extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "evaluation_criteria_id",
        "project_id",
        "date",
    ];
}
