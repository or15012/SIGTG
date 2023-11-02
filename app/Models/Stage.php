<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name","protocol_id","cycle_id","school_id",
    ];

    // Relación con el modelo protocolo
     public function protocol()
     {
         return $this->belongsTo(Protocol::class, 'protocol_id');
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
}
