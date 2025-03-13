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
    // Route 3
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

    // Route 6
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
            return response()->json([
                'message' => 'Critique supprimée avec succès'
            ], HttpStatusCodes::NO_CONTENT);

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
