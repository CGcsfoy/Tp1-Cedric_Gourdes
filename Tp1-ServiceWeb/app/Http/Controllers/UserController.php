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
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'login' => $request->login,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
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
            ], HttpStatusCodes::SERVER_ERROR);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
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
            ], HttpStatusCodes::SERVER_ERROR);
        }
    }

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
            ], HttpStatusCodes::SERVER_ERROR);
        }
    }
}
