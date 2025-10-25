<?php

namespace App\Http\Controllers;

use App\Models\UserGithubCredentials;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlaygroundController extends Controller
{
    public function index()
    {
        return Inertia::render('playground/index', []);
    }
    
    public function store(Request $request)
    {

        $request->validate([
            'access_token' => 'required|string',
        ]);

        UserGithubCredentials::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['access_token' => $request->input('access_token')]
        );

        return redirect()->route("dashboard");
    }
}
