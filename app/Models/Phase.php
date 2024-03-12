<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivityTrait;
class Phase extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;

    protected $fillable = [
        'id',
        'name',
        'description',
        'cycle_id',
        'school_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function stages()
    {
        return $this->belongsToMany(Stage::class, 'phase_stage')->withTimestamps();
    }

    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

}
