<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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

}
