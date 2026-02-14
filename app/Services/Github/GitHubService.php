<?php

namespace App\Services\Github;

use App\Facade\GithubServiceFacade as GitHub;

class GitHubService
{
    public static function getCommits()
    {
        return Github::get('/repos/cawe-dev/blog/commits');
    }
}
