<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class Cycle extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;
    protected $fillable = [
        'year',
        'number',
        'status',
        'date_start',
        'date_end',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function parameters()
    {
        return $this->hasMany(Parameter::class);
    }

    public function phases()
    {
        return $this->hasMany(Phase::class);
    }
}
