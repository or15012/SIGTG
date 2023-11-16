<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'deadline',
        'group_id',
        'profile_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function extensions(){
        return $this->hasMany(Extension::class, 'project_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
