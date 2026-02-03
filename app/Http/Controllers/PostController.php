<?php

namespace App\Http\Controllers;

use App\Enums\PostType;
use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'tags'])->orderBy("created_at", "desc")->get();

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
            'posts' => $posts,
        ]);
    }

    public function show(Post $post)
    {
        $post = Post::with(['category', 'tags', 'references'])->findOrFail($post->id);

        return Inertia::render('blog/post', [
            'post' => $post->append('content_html'),
        ]);
    }

    public function edit(Post $post)
    {
        //
    }
}
