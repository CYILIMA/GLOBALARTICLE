<?php

return [
    App\Providers\AppServiceProvider::class,
];

// Register event listeners
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\LogSuccessfulLogin;

Event::listen(Login::class, LogSuccessfulLogin::class);
