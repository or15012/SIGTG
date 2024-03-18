<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeAgreement extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivityTrait;

    protected $table = 'type_agreements'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'name',
        'affect',
        'created_at',
        'updated_at',
    ];
}
