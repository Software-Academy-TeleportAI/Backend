<?php

namespace App\Http\Controllers;

use App\Models\UserGithubCredentials;
use App\Services\GitHubService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function show(string $owner, string $repo)
    {
        $githubCredentials = UserGithubCredentials::where('user_id', auth()->id())->first();
        
        if (!$githubCredentials || !$githubCredentials->access_token) {
            return redirect()->route('playground.index')
                ->with('error', 'Please add your GitHub access token first.');
        }

        try {
            $githubService = new GitHubService($githubCredentials->access_token);
            $projectData = $githubService->getRepositoryDetails($owner, $repo);

            if (!$projectData) {
                return redirect()->route('dashboard')
                    ->with('error', 'Repository not found.');
            }

            // $flaskUrl = env('FLASK_API_URL', 'http://localhost:5001');
            
            // $flaskResponse = Http::timeout(30)->post("{$flaskUrl}/test", [
            //     'name' => $projectData['name'] ?? $repo,
            //     'repository' => $projectData['repository']['full_name']
            // ]);

            // $flaskData = null;
            
            // if ($flaskResponse->successful()) {
            //     $flaskData = $flaskResponse->json();
            // } else {
            //     Log::warning('Flask API Error: ' . $flaskResponse->body());
            // }


            return Inertia::render('project/show', [
                'projectData' => $projectData,
            ]);
        } 
        catch (\Exception $e) {
            \Log::error('GitHub API Error: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Failed to fetch repository data.');
        }
    }


   public function createDocumentation(string $owner, string $repo)
    {
        try {
             $githubCredentials = UserGithubCredentials::where('user_id', auth()->id())->first();
            $flaskUrl = env('FLASK_API_URL', 'http://localhost:5001');
            
            $flaskResponse = Http::timeout(30)->post("{$flaskUrl}/test", [
                'name' => "{$owner}/{$repo}",
                "access_token" => $githubCredentials->access_token
            ]);

            if ($flaskResponse->successful()) {
            $flaskData = $flaskResponse->json();
                
                error_log(print_r($flaskData, true));
                
                return back()->with('success', 'Documentation created successfully!');
            } else {
                Log::warning('Flask API Error: ' . $flaskResponse->body());
                return back()->with('error', 'Failed to create documentation.');
            }
        } 
        catch (\Exception $e) {
            Log::error('Flask API Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to connect to documentation service.');
        }
    }
}