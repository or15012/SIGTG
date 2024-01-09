<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'path',
    ];

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
}
