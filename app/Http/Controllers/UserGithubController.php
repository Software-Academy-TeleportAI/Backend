<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserGithub;
use Illuminate\Support\Facades\Auth;

class UserGithubController extends Controller
{
    public function setGithubAccess(Request $request)
    {
         $validated = $request->validate([
            'access_token' => 'required|string|max:255',
        ]);

        $userGithub = UserGithub::updateOrCreate(
            ['user_id' => Auth::id()],
            ['access_token' => $validated['access_token']] 
        );

        return response()->json([
            'message' => 'Access token set successfully',
            'status' => 'saved'
        ], 201);
    }
}
