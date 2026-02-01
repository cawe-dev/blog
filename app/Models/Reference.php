<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reference extends Model
{
    /** @use HasFactory<\Database\Factories\ReferenceFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'description',
        'media_url',
        'parent_id',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
