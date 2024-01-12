<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class School extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function phases()
    {
        return $this->hasMany(Phase::class);
    }
}
