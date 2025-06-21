<?php

use App\Models\Post;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', ['title' => 'Home Page']);
});

Route::get('/about', function () {
    return view('about', ['title' => 'About', 'nama' => 'Naufal Harits']);
});

Route::get('/posts', function () {
    // $posts = Post::with(['category', 'author'])->latest()->get();
    $posts = Post::latest()->filter(request(['search', 'category', 'author']))->paginate(6)->withQueryString();

    return view('posts', ['title' => 'Blog', 'posts' => $posts]);
});


Route::get('/posts/{post:slug}', function (Post $post) {
    return view('post', ['title' => ' Single Post', 'post' => $post]);
});

Route::get('/contact', function () {
    return view('contact', ['title' => 'Contact']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
