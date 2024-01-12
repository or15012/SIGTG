<?php
namespace App\Traits;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

trait LogsActivityTrait
{
    public static function bootLogsActivityTrait()
    {
        static::created(function ($model) {
            $user = Auth::user();
            $table_name = $model->getTable();
            $action = 0; // 0 => insert

            Log::createLog($user->id, $table_name, $action);
        });

        static::updated(function ($model) {
            $user = Auth::user();
            $table_name = $model->getTable();
            $action = 1; // 1 => update

            Log::createLog($user->id, $table_name, $action);
        });

        static::deleted(function ($model) {
            $user = Auth::user();
            $table_name = $model->getTable();
            $action = 2; // 2 => delete

            Log::createLog($user->id, $table_name, $action);
        });
    }
}
