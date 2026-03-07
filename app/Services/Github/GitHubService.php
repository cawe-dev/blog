<?php

namespace App\Services\Github;

use App\Exceptions\GithubRequestException;
use App\Facade\GithubServiceFacade as GitHub;
use App\Filament\Concerns\InteractsWithFilament;
use App\Support\Github\GithubCommit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GitHubService
{
    use InteractsWithFilament;

    public function getCommitsByBranch(string $branch, int $page = 1, int $perPage = 10): Collection
    {
        return Cache::remember("github_commits_{$branch}-{$page}-{$perPage}", 180, function () use ($branch, $page, $perPage) {
            $response = Github::get('commits', [
                'sha' => $branch,
                'page' => $page,
                'per_page' => $perPage,
            ]);

            if ($response->failed()) {
                throw GithubRequestException::fromResponse($response);
            }

            return collect($response->json())->map(
                fn (array $commit) => GithubCommit::fromArray($commit)
            );
        });
    }
}
