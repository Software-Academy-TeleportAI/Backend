<?php

namespace App\Http\Controllers;

use App\Models\UserGithubCredentials;
use App\Services\GitHubService;
use Inertia\Inertia;

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
}