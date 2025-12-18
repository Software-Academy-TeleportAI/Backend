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

        public function getRepository(string $owner, string $repo)
    {
        return Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/repos/{$owner}/{$repo}")
            ->json();
    }

    public function getRepositoryLanguages(string $owner, string $repo)
    {
        return Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/repos/{$owner}/{$repo}/languages")
            ->json();
    }

       public function getRepositoryCommits(string $owner, string $repo, int $perPage = 10)
    {
        return Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/repos/{$owner}/{$repo}/commits", [
                'per_page' => $perPage
            ])
            ->json();
    }

    public function getRepositoryContributors(string $owner, string $repo)
    {
        return Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/repos/{$owner}/{$repo}/contributors", [
                'per_page' => 5
            ])
            ->json();
    }


    public function getRepositoryDetails(string $owner, string $repo)
    {
        try {
            $repository = $this->getRepository($owner, $repo);
            $languages = $this->getRepositoryLanguages($owner, $repo);
            $commits = $this->getRepositoryCommits($owner, $repo, 10);
            $contributors = $this->getRepositoryContributors($owner, $repo);

            return [
                'repository' => $repository,
                'languages' => $languages,
                'commits' => $commits,
                'contributors' => $contributors,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
}