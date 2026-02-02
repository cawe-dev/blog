<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('index');
})->name('home');

Route::post('/set-context', function (Request $request) {
    $request->session()->put('blog-context', $request->input('mode'));
    return back();
});
