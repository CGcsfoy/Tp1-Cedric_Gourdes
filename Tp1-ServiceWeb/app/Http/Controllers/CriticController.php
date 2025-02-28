<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\CriticResource;

class CriticController extends Controller
{
    // Route 3
    public function show($id)
    {
        $film = Film::findOrFail($id);
        return CriticResource::collection($film->critics);
    }

    // Route 6
    public function destroy($id)
    {
        $critic = Critic::findOrFail($id);
        $critic->delete();
        return response()->json(['message' => 'Critique supprimée'], 204);
    }
}
