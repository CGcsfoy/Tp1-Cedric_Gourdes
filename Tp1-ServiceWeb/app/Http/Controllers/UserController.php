<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Route 4
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create(array_merge($validated, ['password' => bcrypt($validated['password'])]));
        return new UserResource($user);
    }

    // Route 5
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);
        return new UserResource($user);
    }

    // Route 8
    public function preferredLanguage($id)
    {
        $user = User::findOrFail($id);
        $preferredLanguage = $user->critics()
            ->select('films.language_id', DB::raw('COUNT(*) as count'))
            ->join('films', 'critics.film_id', '=', 'films.id')
            ->groupBy('films.language_id')
            ->orderByDesc('count')
            ->first();

        return response()->json(['preferred_language_id' => $preferredLanguage->language_id ?? null]);
    }
}
