<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;
class SubareaDocument extends Model
{
    use HasFactory;
    use LogsActivityTrait;

    protected $fillable = [
        'name',
        'path',
        'evaluation_subarea_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
