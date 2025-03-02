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



// Routes inutiles dans le TP1
Route::get('/films/{id}', 'App\Http\Controllers\FilmController@show');
