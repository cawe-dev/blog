<?php

namespace App\Http\Controllers;

use App\Enums\PostType;
use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'tags', 'contents', 'changeLogs'])
            ->whereNotNull('published_at')
            ->get();

        $posts->transform(function (Post $post) {
            $post->type_label = $post->type->label();

            return $post;
        });

        $posts = $posts->groupBy(function (Post $post) {
            if ($post->type === PostType::PERSONAL) return 'personalPosts';
            if ($post->type === PostType::PROFESSEONAL) return 'professionalPosts';
            return 'bothPosts';
        });

        return Inertia::render('blog/index', [
            'posts' => $posts->append('has_spoiler'),
        ]);
    }

    public function show(Post $post)
    {
        $post = Post::with(['category', 'tags', 'contents.references'])->findOrFail($post->id);

        if (is_null($post->published_at)) {
            return abort(404, 'Post not found');
        }

        return Inertia::render('blog/post', [
            'post' => $post->append('content_html', 'references'),
        ]);
    }
}
