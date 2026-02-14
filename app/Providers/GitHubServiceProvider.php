<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class GitHubServiceProvider  extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind('api-github', function () {
            $config = config('services.github');

            return Http::withOptions([
                'base_uri' => 'https://api.github.com/repos/cawe-dev/blog/'
            ])->withHeaders([
                'X-GitHub-Api-Version' => $config['version'],
                'Accept' => 'application/vnd.github+json',
                'Authorization' => 'Bearer ' . $config['token'],
            ]);
        });
    }

    public function boot(): void
    {
        //
    }

    protected function configureDefaults(): void
    {
        //
    }
}
