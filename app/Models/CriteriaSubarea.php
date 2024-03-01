<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;
class CriteriaSubarea extends Model
{
    use HasFactory;
    use LogsActivityTrait;

    protected $table = 'criteria_subareas';
    protected $fillable = [
        'evaluation_subarea_id',
        'user_id',
        'note',
        'created_at',
        'updated_at',
        ];
}
