<?php

namespace App\Http\Controllers;

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
        dd($request);
    }
}
