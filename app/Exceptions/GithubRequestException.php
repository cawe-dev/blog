<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class GithubRequestException extends Exception
{
    public static function fromResponse(Response $response): self
    {
        $status = $response->status();
        $error = $response->json('message', 'Unknown error from GitHub API');

        return new self(
            message: "GitHub API Error [{$status}]: {$error}",
            code: $status
        );
    }
}
