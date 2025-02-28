<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActorController extends Controller
{
    // Route 2
    public function index($id)
    {
        $film = Film::findOrFail($id);
        return ActorResource::collection($film->actors);
    }
}
