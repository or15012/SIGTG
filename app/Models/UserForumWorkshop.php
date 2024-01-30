<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserForumWorkshop extends Model
{
    use HasFactory;
    use LogsActivityTrait;

    protected $table = 'user_forum_workshop'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'user_id',
        'forum_id',
        'workshop_id',
    ];

     // Relación con el modelo User
     public function user()
     {
         return $this->belongsToMany(User::class, 'user_id');
     }

}
