<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->where('author_id', Auth::user()->id);
        if (request('keyword')) {
            $posts->where('title', 'like', '%' . request('keyword') . '%');
        }
        $posts = $posts->paginate(4)->withQueryString();
        return view('dashboard.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|unique:posts|min:4|max:255|string',
        //     'body' => 'required|min:50|max:700|string',
        //     'category_id' => 'required',
        // ]);
        Validator::make($request->all(), [
            'title' => 'required|unique:posts|min:4|max:255|string',
            'body' => 'required|min:20|max:700|string',
            'category_id' => 'required',
        ], [
            'title.required' => 'Field :attribute Wajib Di isi!',
            'body.required' => 'Gak Boleh Kosong !',
            'category_id.required' => 'Pilih salah satu category !',
        ], [
            'title' => 'judul',
            'category_id' => 'kategori',
            'body' => 'isi konten'
        ])->validate();


        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'author_id' => Auth::user()->id,
        ]);
        return redirect('/dashboard')->with('success', 'SUCCESS, Berhasil menambahkan post baru!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('dashboard.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('dashboard.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validation
        $request->validate([
            'title' => 'required|min:4|max:255|string|unique:posts, title' . $post->id,
            'body' => 'required|min:20|max:700|string',
            'category_id' => 'required',
        ]);
        // Update Post
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'author_id' => Auth::user()->id,
        ]);
        // Redirect
        return redirect('/dashboard')->with('success', 'SUCCESS, Berhasil memperbarui post baru!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect('/dashboard')->with('success', 'Berhasil menghapus postingan!');
    }
}
