<?php

namespace App\Modules\Authentication\Listeners;

use App\Modules\Authentication\Events\UserLoggedIn;

class LogSuccessfulLogin
{
    public function handle(UserLoggedIn $event): void
    {
        // Handled directly in AuthenticationService for synchronous requirements,
        // but this listener can dispatch async tasks like notification emails.
    }
}
