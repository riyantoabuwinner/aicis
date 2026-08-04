<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/history', function () {
    return view('history');
});

Route::get('/contact', function () {
    return view('contact');
});
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'sendMessage'])->name('contact.send');

Route::get('/registration-success', function () {
    return view('registration-success');
});

Route::get('/posts', function () {
    $posts = \App\Models\Post::latest()->paginate(9);
    return view('posts', compact('posts'));
});

Route::get('/post/{slug}', function ($slug) {
    $post = \App\Models\Post::where('slug', $slug)->firstOrFail();
    return view('post', compact('post'));
});

Route::get('/page/{slug}', function ($slug) {
    $page = \App\Models\Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
    return view('page', compact('page'));
});
