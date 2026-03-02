<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class InviteService
{

    public static function create(int $postId): string
    {
        $token = Str::random(32);
        Redis::setex("invite:{$token}", 86400, $postId);

        return $token;
    }

    public static function show(string $token): string
    {
        return Redis::command('GET', ["invite:{$token}"]);
    }
}
