<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProjectNote extends Model
{
    use HasFactory;

    protected $table = 'user_project_note'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'user_id',
        'project_id',
        'note',
    ];
}
