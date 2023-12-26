<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'date_start',
        'date_end',
        'group_id',
        'project_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relación con el modelo grupo
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // Relación con el modelo project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
