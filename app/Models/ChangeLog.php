<?php

namespace App\Models;

use App\Enums\ChangeLogType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ChangeLog extends Model
{
    /** @use HasFactory<\Database\Factories\ChangeLogFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'version',
        'commit',
        'pull_request',
        'published_at',
        'post_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => ChangeLogType::class,
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function scopeNotificable(Builder $query): void
    {
        $query->where('published_at', '>', Carbon::now()->subDays(7))
            ->orderByDesc('published_at')
            ->limit(5);
    }
}
