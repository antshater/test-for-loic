<?php

namespace App\Policies;

use App\Turnstile;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TurnstilePolicy
{
    use HandlesAuthorization;

    public function pass(User $authUser, Turnstile $turnstile)
    {
        return ! $turnstile->is_locked;
    }
}
