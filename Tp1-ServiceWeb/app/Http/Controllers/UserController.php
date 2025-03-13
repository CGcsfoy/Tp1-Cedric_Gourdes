<?php

namespace App\Http\Controllers;

use App\Constants\HttpStatusCodes; 
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\User;
use App\Http\Resources\UserResource;
use Exception;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Créer un utilisateur",
     *     description="Crée un nouvel utilisateur avec les informations fournies",
     *     @OA\Response(response=201, description="Utilisateur créé"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'login' => $request->login,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return (new UserResource($user))->response()->setStatusCode(201);

        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Erreur base de données',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::DATABASE_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Mettre à jour un utilisateur",
     *     description="Met à jour les informations d'un utilisateur existant",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Utilisateur mis à jour"),
     *     @OA\Response(response=404, description="Utilisateur introuvable"),
     *     @OA\Response(response=422, description="ID invalide"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            if (!is_numeric($id) || intval($id) <= 0) {
                return response()->json([
                    'error' => 'ID invalide',
                    'message' => 'L\'ID de l\'utilisateur doit être un entier positif.'
                ], HttpStatusCodes::UNPROCESSABLE_ENTITY);
            }
            
            $user = User::findOrFail($id);
            $user->update($request->validated());

            return new UserResource($user);

        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Erreur base de données',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::DATABASE_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}/preferred-language",
     *     tags={"Users"},
     *     summary="Obtenir la langue préférée d'un utilisateur",
     *     description="Retourne l'ID de la langue la plus souvent critiquée par l'utilisateur",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Langue préférée retournée"),
     *     @OA\Response(response=404, description="Utilisateur introuvable"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function preferredLanguage($id)
    {
        try {
            $user = User::findOrFail($id);

            $preferredLanguage = $user->critics()
                ->select('films.language_id', \DB::raw('COUNT(*) as count'))
                ->join('films', 'critics.film_id', '=', 'films.id')
                ->groupBy('films.language_id')
                ->orderByDesc('count')
                ->first();

            return response()->json([
                'preferred_language_id' => optional($preferredLanguage)->language_id
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'error' => 'Erreur base de données',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::DATABASE_ERROR);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur serveur',
                'message' => $e->getMessage(),
            ], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }
}
