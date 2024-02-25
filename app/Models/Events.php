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

    protected $dates = ['date']; // Para asegurar que 'date' sea un objeto Carbon

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

