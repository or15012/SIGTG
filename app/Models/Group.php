<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
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
            ->withPivot(['status', 'is_leader']) // Esto permite acceder a la columna 'status' de la tabla intermedia
            ->withTimestamps();
    }

    public function teacherUsers()
    {
        return $this->belongsToMany(User::class, 'teacher_group')
            ->withPivot(['status', 'type']) // Esto permite acceder a la columna 'status' de la tabla intermedia
            ->withTimestamps();
    }

    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'cycle_id');
    }

    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'user_group')
            ->withPivot(['status', 'is_leader']) // Esto permite acceder a la columna 'status' de la tabla intermedia
            ->withTimestamps();;
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'group_id');
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'group_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'group_id');
    }
}
