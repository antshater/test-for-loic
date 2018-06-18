<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turnstile extends Model
{
    public $timestamps = false;

    protected $fillable = ['is_locked', 'alarm_on'];

    protected $casts = [
        'is_locked' => 'bool',
        'alarm_on' => 'bool',
    ];
}
