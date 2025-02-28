<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/films', 'App\Http\Controllers\FilmController@index');
Route::get('/films/{id}', 'App\Http\Controllers\FilmController@show');
