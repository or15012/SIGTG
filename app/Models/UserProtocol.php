<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivityTrait;
class UserProtocol extends Model
{
    use HasFactory;
    use LogsActivityTrait;

    protected $table = 'user_protocol'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'user_id',
        'protocol_id',
        'status',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el modelo Protocol
    public function protocol()
    {
        return $this->belongsTo(Protocol::class, 'protocol_id');
    }
}
