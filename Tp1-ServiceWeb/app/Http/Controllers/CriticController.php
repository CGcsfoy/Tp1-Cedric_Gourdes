<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Film;
use App\Models\Critic;
use App\Http\Resources\CriticResource;
use App\Constants\HttpStatusCodes;
use Exception;

class CriticController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/films/{id}/critics",
     *     tags={"Critics"},
     *     summary="Obtenir les critiques d'un film",
     *     description="Retourne une liste de critiques associées à un film donné",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Liste des critiques récupérée"),
     *     @OA\Response(response=404, description="Film introuvable"),
     *     @OA\Response(response=422, description="ID invalide"),
     *     @OA\Response(response=503, description="Erreur base de données"),
     *     @OA\Response(response=500, description="Erreur serveur")
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
            return CriticResource::collection($film->critics)
                ->response()->setStatusCode(HttpStatusCodes::OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Film non trouvé',
                'message' => 'Aucun film trouvé avec cet ID',
            ], HttpStatusCodes::NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Erreur base de données',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::SERVICE_UNAVAILABLE);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/critics/{id}",
     *     tags={"Critics"},
     *     summary="Supprimer une critique",
     *     description="Supprime une critique spécifique par son ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Critique supprimée avec succès"),
     *     @OA\Response(response=404, description="Critique non trouvée"),
     *     @OA\Response(response=422, description="ID invalide"),
     *     @OA\Response(response=503, description="Erreur base de données"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function destroy($id)
    {
        try {
            if (!is_numeric($id) || intval($id) <= 0) {
                return response()->json([
                    'error' => 'ID invalide',
                    'message' => 'L\'ID de la critique doit être un entier positif.'
                ], HttpStatusCodes::UNPROCESSABLE_ENTITY);
            }

            $critic = Critic::findOrFail($id);
            $critic->delete();
            return response()->json([], HttpStatusCodes::NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Critique non trouvée',
                'message' => 'Aucune critique trouvée avec cet ID',
            ], HttpStatusCodes::NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Erreur base de données',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::SERVICE_UNAVAILABLE);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }
}