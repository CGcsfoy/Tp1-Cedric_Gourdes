<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route 1
Route::get('/films', 'App\Http\Controllers\FilmController@index');
// Route 2
Route::get('/films/{id}/actors', 'App\Http\Controllers\ActorController@index');
// Route 3
Route::get('/films/{id}/critics', 'App\Http\Controllers\CriticController@show');




// Routes inutiles dans le TP1
Route::get('/films/{id}', 'App\Http\Controllers\FilmController@show');
