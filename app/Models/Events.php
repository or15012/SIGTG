<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;

class Events extends Model
{
    use HasFactory;
    use LogsActivityTrait;
    protected $fillable = [
        'name',
        'description',
        'place',
        'date',
        'user_id',
        'group_id',
        'project_id',
        'cycle_id',
        'school_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el modelo ciclos
    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'cycle_id');
    }

    // Relación con el modelo escuelas
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

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
