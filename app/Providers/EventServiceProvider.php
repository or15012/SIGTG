<?php

namespace App\Providers;

use App\Models\Protocol;
use App\Models\School;
use App\Models\User;
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
            $protocols = $user->protocols;
            //que pasa sino tiene escuela asignada



            //tengo que revisar si esque no tiene o si posee todas segun el rol

            //con escuela asignada guardare asi
            $schoolReturn = array(
                "id"    => $school->id,
                "name"  => $school->name,
            );

            $protocol = array();
            foreach($protocols as $key => $protocol){
                if ($key === 0) {
                    $protocol = array(
                        "id"    => $protocol->id,
                        "name"  =>$protocol->name
                    );

                }
            }

            // Puedes asignar tu variable de sesión aquí.
            session([
                'school'        => $schoolReturn,
                'protocols'     => $protocols,
                'protocol'      => $protocol,
            ]);
        });
    }
}
