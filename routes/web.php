<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', ['title' => 'Home Page']);
});

Route::get('/about', function () {
    return view('about', ['title' => 'About', 'nama' => 'Naufal Harits']);
});

Route::get('/posts', function () {
    // $posts = Post::with(['category', 'author'])->latest()->get();
    $posts = Post::latest()->filter(request(['search', 'category', 'author']))->get();

    return view('posts', ['title' => 'Blog', 'posts' => $posts]);
});


Route::get('/posts/{post:slug}', function (Post $post) {
    return view('post', ['title' => ' Single Post', 'post' => $post]);
});

Route::get('/authors/{user:username}', function (User $user) {
    // $posts = $user->posts->load('category', 'author');
    return view('posts', ['title' => count($user->posts) . ' Article by. ' . $user->name, 'posts' => $user->posts]);
});

Route::get('/categories/{category:slug}', function (Category $category) {
    // $posts = $category->posts->load('category', 'author');
    return view('posts', ['title' => 'Category : ' . $category->name, 'posts' => $category->posts]);
});

Route::get('/contact', function () {
    return view('contact', ['title' => 'Contact']);
});