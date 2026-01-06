<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DocsGenerationController extends Controller
{
        public function generate()
    {
        $response = Http::get('http://127.0.0.1:8001/api/generate/docs');

        if ($response->successful()) {
            return response()->json([
                'message' => 'Data fetched from Flask successfully',
                'external_data' => $response->json()
            ]);
        }

        return response()->json([
            'message' => 'Failed to fetch data from external server',
            'error' => $response->body()
        ], $response->status());
    }
}
