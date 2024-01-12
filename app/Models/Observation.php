<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class Observation extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;
    protected $fillable = [
        'id',
        'description',
        'evaluation_stage_id',
        'profile_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id'); // Asumiendo que la clave foránea se llama 'school_id'
    }

}
