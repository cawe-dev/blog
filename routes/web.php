<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PostController::class, 'index'])->name('home');

Route::post('/set-context', function (Request $request) {
    $request->session()->put('blog-context', $request->input('mode'));
    return back();
});
