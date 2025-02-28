<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CriticController extends Controller
{
    // Route 6
    public function destroy($id)
    {
        $critic = Critic::findOrFail($id);
        $critic->delete();
        return response()->json(['message' => 'Critique supprimée'], 204);
    }
}
