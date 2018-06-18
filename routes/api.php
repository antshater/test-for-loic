<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/turnstiles/{turnstile}/insert-coin', 'TurnstileController@insertCoin')->name('api.turnstiles.insert-coin');
Route::middleware('auth')->post('/turnstiles/{turnstile}/pass', 'TurnstileController@pass')->name('api.turnstiles.pass');
