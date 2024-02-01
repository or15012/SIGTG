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
        'id',
        'user_id',
        'forum_id',
        'workshop_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'forum_id');
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id');
    }

}
