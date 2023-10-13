<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parameter extends Model
{
    use HasFactory, SoftDeletes;
    const PARAMETERS = [
        'max_group'         => 'Valor máximo de grupos',
        'min_group'         => 'Valor mínimo de grupos',
        'max_advisors'      => 'Valor máximo de asesores',
        'min_advisors'      => 'Valor mínimo de asesores',
    ];

    protected $fillable = [
        'name',
        'value',
        'cycle_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }
}
