<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route 1
Route::get('/films', 'App\Http\Controllers\FilmController@index');
// Route 2
Route::get('/films/{id}/actors', 'App\Http\Controllers\ActorController@index');
// Route 3
Route::get('/films/{id}/critics', 'App\Http\Controllers\CriticController@show');
// Route 4
Route::post('/users', 'App\Http\Controllers\UserController@store');
// Route 5
Route::put('/users/{id}', 'App\Http\Controllers\UserController@update');
// Route 6
Route::delete('/critics/{id}', 'App\Http\Controllers\CriticController@destroy');
// Route 7
Route::get('/films/{id}/average-score', 'App\Http\Controllers\FilmController@averageScore');
// Route 8 -- Revoir, retourne toujours 1
Route::get('/users/{id}/preferred-language', 'App\Http\Controllers\UserController@preferredLanguage');
// Route 9
Route::get('/films/search', 'App\Http\Controllers\FilmController@search');


// Routes inutiles dans le TP1
Route::get('/films/{id}', 'App\Http\Controllers\FilmController@show');
