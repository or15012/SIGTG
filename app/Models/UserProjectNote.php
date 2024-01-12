<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;
class UserProjectNote extends Model
{
    use HasFactory;
    use LogsActivityTrait;

    protected $table = 'user_project_note'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'user_id',
        'project_id',
        'note',
    ];
}
