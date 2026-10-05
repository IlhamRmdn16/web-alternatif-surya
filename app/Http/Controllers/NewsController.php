<?php

namespace App\Http\Controllers;

use App\Models\Post;

class NewsController extends Controller
{
    public function index()
    {
        $posts = Post::published()->orderByDesc('published_at')->orderByDesc('id')->paginate(9);
        return view('news.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_unless($post->is_live, 404);
        $related = Post::published()->where('id', '!=', $post->id)->orderByDesc('published_at')->take(3)->get();
        return view('news.show', compact('post', 'related'));
    }
}
