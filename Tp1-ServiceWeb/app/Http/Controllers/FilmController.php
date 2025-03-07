<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FilmResource;
use App\Models\Film;
use App\Constants\HttpStatusCodes;


class FilmController extends Controller
{
    // Route 1
    public function index()
    {
        try {
            return FilmResource::collection(Film::paginate(20));
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Erreur base de données',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $film = Film::findOrFail($id);
            return new FilmResource($film);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Erreur base de données',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Film non trouvé',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::NOT_FOUND);
        }
    }

    // Route 7
    public function averageScore($id)
    {
        try {
            $film = Film::findOrFail($id);
            $average = $film->critics()->avg('score');
            return response()->json(['average_score' => $average]);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Erreur base de données',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Film non trouvé',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::NOT_FOUND);
        }
    }

    // Route 9
    public function search(Request $request)
    {
        try {
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
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Erreur base de données',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }
}
