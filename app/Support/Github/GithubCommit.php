<?php

namespace App\Support\Github;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class GithubCommit implements Arrayable, JsonSerializable
{
    public function __construct(
        public string $sha,
        public string $message,
        public string $author,
        public string $date,
        public string $url,
    ) {}

    public function toArray(): array
    {
        return [
            'sha' => $this->sha,
            'message' => $this->message,
            'author' => $this->author,
            'date' => $this->date,
            'url' => $this->url,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            sha: $data['sha'],
            message: $data['commit']['message'],
            author: $data['commit']['author']['name'],
            date: $data['commit']['author']['date'],
            url: $data['html_url'],
        );
    }
}
