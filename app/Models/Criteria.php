<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory,LogsActivityTrait;

    protected $fillable = [
        "name",
        "description",
        "percentage",
        "stage_id",
        "type",
    ];
}
