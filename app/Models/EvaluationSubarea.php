<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class EvaluationSubarea extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;
    protected $fillable = [
        "evaluation_criteria_id",
        "project_id",
        "date",
    ];
}
