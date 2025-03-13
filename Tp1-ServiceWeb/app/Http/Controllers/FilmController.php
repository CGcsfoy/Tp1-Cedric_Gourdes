<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FilmResource;
use App\Models\Film;
use App\Constants\HttpStatusCodes;


class FilmController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/films",
     *     tags={"Films"},
     *     summary="Lister les films",
     *     description="Retourne une liste paginée des films",
     *     @OA\Response(response=200, description="Liste des films récupérée"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function index()
    {
        try {
            return FilmResource::collection(Film::paginate(20));
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Erreur base de données',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::SERVER_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/films/{id}",
     *     tags={"Films"},
     *     summary="Obtenir un film par ID",
     *     description="Retourne les détails d'un film",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Film trouvé"),
     *     @OA\Response(response=404, description="Film introuvable"),
     *     @OA\Response(response=422, description="ID invalide")
     * )
     */
    public function show($id)
    {
        try {
            if (!is_numeric($id) || intval($id) <= 0) {
                return response()->json([
                    'error' => 'ID invalide',
                    'message' => 'L\'ID du film doit être un entier positif.'
                ], HttpStatusCodes::UNPROCESSABLE_ENTITY);
            }
            
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

    /**
     * @OA\Get(
     *     path="/api/films/{id}/average-score",
     *     tags={"Films"},
     *     summary="Obtenir la note moyenne d'un film",
     *     description="Retourne la note moyenne des critiques",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Note moyenne calculée"),
     *     @OA\Response(response=404, description="Film introuvable"),
     *     @OA\Response(response=422, description="ID invalide")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/films/search",
     *     tags={"Films"},
     *     summary="Rechercher des films",
     *     description="Recherche des films par critères",
     *     @OA\Parameter(name="keyword", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="rating", in="query", @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="minLength", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="maxLength", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Résultats de la recherche"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
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
