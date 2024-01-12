<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class TeacherCourse extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;

    protected $guarded = [
        'id'
    ];
}
