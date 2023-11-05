<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'year',
        'status',
        'state_id',
        'protocol_id',
        'cycle_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_group')
            ->withPivot(['status','is_leader']) // Esto permite acceder a la columna 'status' de la tabla intermedia
            ->withTimestamps();
    }

    public function teacherUsers()
    {
        return $this->belongsToMany(User::class, 'teacher_group')
            ->withPivot(['status']) // Esto permite acceder a la columna 'status' de la tabla intermedia
            ->withTimestamps();
    }
}
