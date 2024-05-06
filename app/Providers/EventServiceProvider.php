<?php

namespace App\Providers;

use App\Events\ChirpCreated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;

class EventServiceProvider extends ServiceProvider
{
    // protected $listen = [
    //     Registered::class => [
    //         SendEmailVerificationNotification::class,
    //     ],
    //     ChirpCreated::class => [
    //         SendEmailVerificationNotification::class,
    //     ],
    // ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
