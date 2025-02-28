<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FilmResource;
use App\Models\Film;


class FilmController extends Controller
{
    // Route 1
    public function index()
    {
        return FilmResource::collection(Film::paginate(20));
    }

    // Route 3
    public function show($id)
    {
        $film = Film::findOrFail($id);
        return new FilmResource($film);
    }

    // Route 7
    public function averageScore($id)
    {
        $film = Film::findOrFail($id);
        $average = $film->critics()->avg('score');
        return response()->json(['average_score' => $average]);
    }

    // Route 9
    public function search(Request $request)
    {
        $query = Film::query();
        
        if ($request->has('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }
        if ($request->has('minLength')) {
            $query->where('length', '>=', $request->minLength);
        }
        if ($request->has('maxLength')) {
            $query->where('length', '<=', $request->maxLength);
        }
        
        return FilmResource::collection($query->paginate(20));
    }
}
