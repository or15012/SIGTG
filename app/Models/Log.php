<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    public static function createLog($user, $table_name, $action){
        $log = new Log();

        $log->id_user = $user;
        $log->table_name = $table_name;
        $log->action = $action;


        $log->save();
    }

    
}
