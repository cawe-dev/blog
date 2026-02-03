<?php

namespace App\Models;

use App\Enums\PostType;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'type',
        'excerpt',
        'is_featured',
        'published_at',
        'category_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'type' => PostType::class,
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function references(): BelongsToMany
    {
        return $this->belongsToMany(Reference::class, 'post_reference')
            ->withPivot('context', 'term')
            ->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag')
            ->withTimestamps();
    }

    protected function contentHtml(): Attribute
    {
        return Attribute::get(function () {
            if (empty($this->content)) {
                return '';
            }

            try {
                return RichContentRenderer::make($this->content)
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsVisibility('public')
                    ->toUnsafeHtml();
            } catch (\Throwable $e) {
                report($e);
                return '';
            }
        });
    }
}
