<!--Rewritten to handle CORS issues between signup forms and API endpoints to database-->
<?php
// app/Http/Kernel.php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ... other properties

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
   protected $middlewareGroups = [
     \Illuminate\Http\Middleware\HandleCors::class, // ✅ This applies CORS globally
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ✅ This is okay here if needed
    ],

    'api' => [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        \Illuminate\Http\Middleware\HandleCors::class, // ✅ Use Laravel’s built-in CORS middleware here
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];


    // ... rest of the file
}