<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

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

// Route::get('/dashboard', function () {
// return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [PostController::class, 'store'])->name('dashboard.store');
    Route::get('/dashboard/create', [PostController::class, 'create'])->name('dashboard.create');
    Route::delete('/dashboard/{post:slug}', [PostController::class, 'destroy'])->name('dashboard.destroy');
    Route::get('/dashboard/{post:slug}/edit', [PostController::class, 'edit'])->name('dashboard.edit');
    Route::patch('/dashboard/{post:slug}', [PostController::class, 'update'])->name('dashboard.update');
    Route::get('/dashboard/{post:slug}', [PostController::class, 'show'])->name('dashboard.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/upload', [ProfileController::class, 'upload']);
});

require __DIR__ . '/auth.php';