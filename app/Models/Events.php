<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;

class Events extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;

    protected $fillable = [
        'name',
        'description',
        'place',
        'date',
        'status',
        'user_id',
        'group_id',
        'project_id',
        'cycle_id',
        'school_id',
        
    ];

    protected $dates = ['date']; // Para asegurar que 'date' sea un objeto Carbon

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'cycle_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function status()
    {
        switch ($this->status) {
            case 0:
                return 'Presentada';
                break;
            case 1:
                return 'Aprobada';
                break;
            case 2:
                return 'Rechazada';
                break;
            default:
                return 'Not found';
                break;
        }
    }
}

