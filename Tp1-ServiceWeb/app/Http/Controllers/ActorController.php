<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\ActorResource;

class ActorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/films/{id}/actors",
     *     tags={"Actors"},
     *     summary="Obtenir la liste des acteurs d'un film",
     *     description="Retourne une liste des acteurs associés à l'ID d'un film donné",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du film",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des acteurs récupérée avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Film introuvable",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
     *                 example="FilmIntrouvable",
     *                 summary="Réponse lorsque le film n'est pas trouvé",
     *                 value={
     *                     "error": "Film introuvable",
     *                     "message": "Aucun résultat trouvé pour le modèle [Film] avec cet ID"
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function index($id)
    {
        try {
            $film = Film::findOrFail($id);
            return ActorResource::collection($film->actors);
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
}
