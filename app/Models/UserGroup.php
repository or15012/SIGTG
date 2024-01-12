<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;
class UserGroup extends Model
{
    use HasFactory;
    use LogsActivityTrait;

    protected $table = 'user_group'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'user_id',
        'group_id',
        'status',
        'is_leader'
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el modelo Protocol
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    
    public function status()
    {
        switch($this->pivot->status){
            case(0):
                return 'No aceptado';
            
            case(1):
                return 'Confirmado';
            
            case(2):
                return 'Rechazado';
            default: 
                return 'No found';
        }
    }
}
