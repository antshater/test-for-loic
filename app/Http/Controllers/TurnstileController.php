<?php

namespace App\Http\Controllers;

use App\Turnstile;
use Illuminate\Support\Facades\Auth;

class TurnstileController extends Controller
{
    public function insertCoin(Turnstile $turnstile)
    {
        $turnstile->is_locked = false;
        $turnstile->alarm_on = false;
        $turnstile->save();
    }

    public function pass(Turnstile $turnstile)
    {
        if (Auth::user()->cant('pass', $turnstile)) {
            $turnstile->alarm_on = true;
            $turnstile->save();
        }

        $this->authorize('pass', $turnstile);

        $turnstile->is_locked = true;
        $turnstile->save();
        return ['result' => 'You passed !!!'];
    }
}
