<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\ActorResource;

class ActorController extends Controller
{
    // Route 2
    public function index($id)
    {
        $film = Film::findOrFail($id);
        return ActorResource::collection($film->actors);
    }
}
