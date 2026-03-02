<?php

namespace App\Http\Controllers;

use App\Enums\ChangeLogType;
use App\Enums\PostType;
use App\Models\ChangeLog;
use App\Models\Post;
use App\Services\InviteService;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(): Response
    {
        $posts = Post::with(['category', 'tags', 'contents', 'changeLogs'])
            ->whereNotNull('published_at')
            ->get();

        $posts->transform(function (Post $post) {
            $post->type_label = $post->type->label();

            $post->append('has_spoiler', 'estimated_read_time');

            return $post;
        });

        $posts = $posts->groupBy(function (Post $post) {
            if (isset($post->pinned_at) && $post->type === PostType::BOTH) return 'bothPinnedsPosts';
            if ($post->type === PostType::PERSONAL) return 'personalPosts';
            if ($post->type === PostType::PROFESSIONAL) return 'professionalPosts';
            return 'bothPosts';
        });

        return Inertia::render('blog/index', [
            'posts' => $posts,
        ]);
    }

    public function show(Post $post): Response
    {
        $post = Post::with(['category', 'tags', 'contents.references'])->findOrFail($post->id);
        $changelogs = ChangeLog::where('type', ChangeLogType::FEATURE)
            ->where('published_at', '>', $post->published_at)
            ->orderByDesc('published_at')->get();

        if (is_null($post->published_at)) {
            return abort(404, 'Post not found');
        }

        return Inertia::render('blog/post', [
            'post' => $post->append('content_html', 'references', 'estimated_read_time', 'sub_topics'),
            'changelogs' => $changelogs,
        ]);
    }

    public function showByInvite($token): Response
    {
        $postId = InviteService::show($token);

        if (!$postId) {
            return abort(404, 'Post invite expired');
        }

        $post = Post::with(['category', 'tags', 'contents.references'])->findOrFail($postId);

        return Inertia::render('blog/post', [
            'post' => $post->append('content_html', 'references', 'estimated_read_time', 'sub_topics'),
            'changelogs' => [],
        ]);
    }
}
