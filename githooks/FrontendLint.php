<?php

declare(strict_types=1);

namespace GitHooks;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook;
use SebastianFeldmann\Git\Repository;

final class FrontendLint implements Hook\Action
{
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $stagedFiles = $repository->getIndexOperator()->getStagedFiles();
        $validExtensions = ['vue', 'js', 'ts', 'jsx', 'tsx'];

        foreach ($stagedFiles as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);

            if (in_array($extension, $validExtensions)) {
                $safeFile = escapeshellarg($file);

                shell_exec('npx prettier --write '.$safeFile);
                shell_exec('npx eslint --fix '.$safeFile);
                shell_exec('git add '.$safeFile);
            }
        }
    }
}
