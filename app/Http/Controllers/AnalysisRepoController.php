<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepositoryAnalysis;

class AnalysisRepoController extends Controller
{

    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $analyses = RepositoryAnalysis::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($analyses);
    }

    public function show(Request $request, $id)
    {
        $userId = $request->user()->id;

        $analysis = RepositoryAnalysis::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        return response()->json($analysis);
    }

    public function update(Request $request, $id)
    {
        $analysis = RepositoryAnalysis::findOrFail($id);

        $validated = $request->validate([
            'readme' => 'nullable|string',
   
        ]);

        $analysis->update($validated);

        return response()->json($analysis);
    }

    public function destroy($id)
    {
        $analysis = RepositoryAnalysis::findOrFail($id);
        $analysis->delete();

        return response()->json(['message' => 'Analysis deleted successfully']);
    }

    public function storeAnalysis(Request $request)
    {

        $userId = $request->user()->id;

        $validated = $request->validate([
            'repo_id' => 'required', 
            'repo_name' => 'nullable|string',
            'summary' => 'nullable|string',
            'architecture_diagram' => 'nullable|string',
            'readme' => 'nullable|string',
            'files' => 'nullable|array', 
        ]);


        $analysis = RepositoryAnalysis::updateOrCreate(
            [
                'repository_id' => $validated['repo_id'],
                'user_id' => $userId,
            ],
            [
                'repo_name' => $validated['repo_name'] ?? null,
                'summary' => $validated['summary'] ?? null,
                'architecture_diagram' => $validated['architecture_diagram'] ?? null,
                'readme' => $validated['readme'] ?? null,
                'files' => $validated['files'] ?? null, 
            ]
        );

        return response()->json($analysis);
    }
}