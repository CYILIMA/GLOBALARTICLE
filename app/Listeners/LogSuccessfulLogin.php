<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        $userAgent = request()->userAgent();
        $parsed = LoginHistory::parseUserAgent($userAgent);

        LoginHistory::create([
            'user_id' => $event->user->id,
            'ip_address' => request()->ip(),
            'user_agent' => $userAgent,
            'device_type' => $parsed['device_type'],
            'browser' => $parsed['browser'],
            'successful' => true,
            'login_at' => now(),
        ]);
    }
}
