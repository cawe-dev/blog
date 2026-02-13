<?php

namespace App\Models;

use App\Enums\PostType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'excerpt',
        'thumbnail',
        'pinned_at',
        'published_at',
        'category_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'pinned_at' => 'datetime',
            'published_at' => 'datetime',
            'type' => PostType::class,
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('pinneds_first', function ($builder) {
            $builder->orderBy('pinned_at', 'asc')
                ->orderBy('published_at', 'desc');
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag')
            ->withTimestamps();
    }

    public function contents(): HasMany
    {
        return $this->hasMany(ContentPost::class);
    }

    protected function contentHtml(): Attribute
    {
        return Attribute::get(function () {
            $groupedByViewMode = $this->contents
                ->groupBy('view_mode')
                ->map(fn($group) => $group->implode('content_html', ''));

            if ($groupedByViewMode->count() === 1) {
                return $groupedByViewMode->first();
            }

            return $groupedByViewMode->toArray();
        });
    }

    protected function references(): Attribute
    {
        return Attribute::get(function () {
            return $this->contents->pluck('references')->flatten()->unique('id')->values();
        });
    }

    protected function hasSpoiler(): Attribute
    {
        return Attribute::get(function () {
            return $this->contents->some(function (ContentPost $content) {
                return $content->body->hasSpoiler();
            });
        });
    }
}
