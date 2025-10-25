<?php

namespace App\Http\Controllers;

use App\Models\UserGithubCredentials;
use App\Services\GitHubService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $githubCredentials = UserGithubCredentials::where('user_id', auth()->id())->first();
        
        $githubData = null;
        
        if ($githubCredentials && $githubCredentials->access_token) {
            try {
                $githubService = new GitHubService($githubCredentials->access_token);
                $githubData = $githubService->getStats();
            } catch (\Exception $e) {
               
                \Log::error('GitHub API Error: ' . $e->getMessage());
            }
        }

        return Inertia::render('dashboard', [
            'githubData' => $githubData,
            'hasGithubToken' => (bool) $githubCredentials,
        ]);
    }
}