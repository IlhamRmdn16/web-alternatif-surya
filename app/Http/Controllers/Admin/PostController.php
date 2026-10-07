<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\Helpers;
use App\Support\Html;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $r)
    {
        $posts = Post::query()
            ->when($r->query('q'), fn ($q, $v) => $q->where('title', 'like', "%$v%"))
            ->when($r->query('status') === 'terbit', fn ($q) => $q->published())
            ->when($r->query('status') === 'draft', fn ($q) => $q->where('is_published', false))
            ->when($r->query('status') === 'terjadwal', fn ($q) => $q->where('is_published', true)->where('published_at', '>', now()))
            ->orderByDesc('id')
            ->paginate(Helpers::perPage($r, 10))->onEachSide(1)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => new Post(['is_published' => true])]);
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function store(Request $r) { return $this->save($r, new Post); }

    public function update(Request $r, Post $post) { return $this->save($r, $post); }

    protected function save(Request $r, Post $post)
    {
        $data = $r->validate([
            'title'        => 'required|string|max:150',
            'excerpt'      => 'nullable|string|max:300',
            'cover'        => 'nullable|image|max:4096',
            'content'      => 'required|string',
            'published_at' => 'nullable|date',
        ], [], ['title' => 'Judul', 'excerpt' => 'Ringkasan', 'cover' => 'Foto sampul', 'content' => 'Isi berita']);

        $content = Html::clean($data['content']);
        if ($content === '') {
            return back()->withInput()->withErrors(['content' => 'Isi berita wajib diisi.']);
        }

        $isPublished = $r->boolean('is_published');
        $publishedAt = $r->filled('published_at') ? Carbon::parse($data['published_at']) : null;
        if ($isPublished && ! $publishedAt) $publishedAt = $post->published_at ?? now();

        $fields = [
            'title'        => $data['title'],
            'excerpt'      => $data['excerpt'] ?? null,
            'content'      => $content,
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ];
        if (! $post->exists) $fields['slug'] = Helpers::uniqueSlug(Post::class, $data['title']);

        if ($r->hasFile('cover')) {
            if ($post->cover) Storage::disk('public')->delete($post->cover);
            $fields['cover'] = $r->file('cover')->store('news', 'public');
        }

        $post->fill($fields)->save();

        return redirect()->route('admin.posts.index')->with('ok', 'Berita "'.$post->title.'" berhasil disimpan.');
    }

    public function destroy(Post $post)
    {
        if ($post->cover) Storage::disk('public')->delete($post->cover);
        $post->delete();
        return back()->with('ok', 'Berita dihapus.');
    }
}
