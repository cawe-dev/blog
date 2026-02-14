<?php

namespace App\Services\Github;

use App\Facade\GithubServiceFacade as GitHub;
use Illuminate\Http\Client\Response;

class GitHubService
{
    public static function getCommitsByBranch(string $branch, int $page = 1, int $perPage = 10): Response
    {
        return Github::get('commits?sha=' . $branch . '&page=' . $page . '&per_page=' . $perPage);
    }
}
