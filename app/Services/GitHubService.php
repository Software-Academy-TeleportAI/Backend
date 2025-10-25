<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubService
{
    protected string $accessToken;
    protected string $baseUrl = 'https://api.github.com';

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getUser()
    {
        return Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/user")
            ->json();
    }

    public function getRepositories()
    {
        return Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/user/repos", [
                'sort' => 'updated',
                'per_page' => 10
            ])
            ->json();
    }

    public function getStats()
    {
        $user = $this->getUser();
        $repos = $this->getRepositories();

        return [
            'user' => $user,
            'total_repos' => $user['public_repos'] ?? 0,
            'followers' => $user['followers'] ?? 0,
            'following' => $user['following'] ?? 0,
            'recent_repos' => array_slice($repos, 0, 5),
        ];
    }
}