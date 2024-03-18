<?php

use App\Models\Phase;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

if (!function_exists('getNotifications')) {
    function getNotifications()
    {
        $notifications =  UserNotification::where('is_read', 0)->where('user_id', Auth::user()->id)->limit(25)->get();
        return $notifications;
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime($stringDateTime)
    {
        $datetime = new DateTime($stringDateTime);
        $today = new DateTime();
        // $yesterday = clone $today->modify('-1 day');
        // $beforeYesterday = clone $today->modify('-2 day');

        // $date = '';
        // if($datetime->format('Y-m-d') == $today->format('Y-m-d')){
        //     $date = 'Hoy';
        // }else if($datetime->format('Y-m-d') == $yesterday->format('Y-m-d')){
        //     $date = 'Ayer';
        // }else if($datetime->format('Y-m-d') == $beforeYesterday->format('Y-m-d')){
        //     $date = 'Anteayer';
        // }else{
        $date = $today->format('d/m/Y');
        // }

        return $date . ' a las ' . $datetime->format('h:i a');
    }
}

if (!function_exists('getPhase')) {
    function getPhase($stage)
    {
        $phase = Phase::join('phase_stage AS ps', 'ps.phase_id', 'phases.id')
            ->where('ps.stage_id', $stage->id)
            ->first();

        return $phase;
    }
}
