<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationStage extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "stage_id",
        'project_id',
    ];

}
