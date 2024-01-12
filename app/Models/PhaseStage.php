<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;
class PhaseStage extends Model
{
    use HasFactory;
    use LogsActivityTrait;

    protected $table = 'phase_stage'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'phase_id',
        'stage_id',
        'created_at',
        'updated_at',
    ];

    // Relación con el modelo Phase
    public function phase()
    {
        return $this->belongsTo(Phase::class, 'phase_id');
    }

    // Relación con el modelo Stage
    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }
}
