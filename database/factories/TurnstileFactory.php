<?php

$factory->define(App\Turnstile::class, function () {
    return [
        'is_locked' => true,
        'alarm_on' => false,
    ];
});
