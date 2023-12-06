<?php

namespace App\Providers;

use App\Models\School;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            // Este código se ejecutará después de que un usuario se haya autenticado.
            $user = $event->user;

            $school = School::find($user->school_id);

            //que pasa sino tiene escuela asignada

            //tengo que revisar si esque no tiene o si posee todas segun el rol
            $schoolReturn = array(
                "id"    => $school->id,
                "name"  => $school->name,
            );

            // Puedes asignar tu variable de sesión aquí.
            session(['school' => $schoolReturn]);
        });
    }
}
