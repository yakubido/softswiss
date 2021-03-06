<?php

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

Route::get('/balance/', 'UserController@showBalance');

Route::post('/deposit', 'UserController@deposit');

Route::post('/withdraw', 'UserController@withdraw');

Route::post('/transfer', 'UserController@transfer');
