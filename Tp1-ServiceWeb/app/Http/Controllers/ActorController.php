<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Http\Resources\ActorResource;
use App\Constants\HttpStatusCodes;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ActorController extends Controller
{
    // Route 2
    public function index($id)
    {
        try {
            $film = Film::findOrFail($id);
            return ActorResource::collection($film->actors);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Film non trouvé',
                'message' => 'Aucun film trouvé avec cet ID',
            ], HttpStatusCodes::NOT_FOUND);
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
