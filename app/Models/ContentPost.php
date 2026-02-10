<?php

namespace App\Models;

use App\Casts\TipTapCast;
use App\Enums\ContentPostViewMode;
use App\Filament\Plugins\MediaIndexerRichContentPlugin;
use App\Filament\Plugins\ReferenceRichContentPlugin;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ContentPost extends Model
{
    protected $fillable = [
        'body',
        'view_mode'
    ];

    protected $appends = ['content_html'];

    protected $casts = [
        'body' => TipTapCast::class,
        'view_mode' => ContentPostViewMode::class,
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }


    public function references(): BelongsToMany
    {
        return $this->belongsToMany(Reference::class, 'content_post_reference')
            ->withPivot('context', 'term')
            ->withTimestamps();
    }

    protected function contentHtml(): Attribute
    {
        return Attribute::get(function () {
            if (empty($this->body->data)) {
                return '';
            }

            try {
                return RichContentRenderer::make($this->body->data)
                    ->plugins([
                        ReferenceRichContentPlugin::make(),
                        MediaIndexerRichContentPlugin::make(),
                    ])
                    ->toUnsafeHtml();
            } catch (\Throwable $e) {
                report($e);
                return '';
            }
        });
    }
}
