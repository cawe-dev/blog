<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/blog');
Route::get('/blog', [PostController::class, 'index'])->name('home');
Route::get('/blog/post/{post:slug}', [PostController::class, 'show'])->name('blog.post');
Route::get('/blog/post/invite/{invite}', [PostController::class, 'showByinvite'])->name('blog.post.invite');

Route::post('/set-context', function (Request $request) {
    $request->session()->put('blog-context', $request->input('mode'));

    return back();
});
