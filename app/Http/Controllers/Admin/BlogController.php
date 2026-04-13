<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category')->latest()->paginate(20);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'excerpt'          => 'nullable|string',
            'content'          => 'required|string',
            'cover_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seo_title'        => 'nullable|string|max:255',
            'seo_description'  => 'nullable|string|max:500',
        ]);

        $data             = $request->all();
        $data['slug']     = Str::slug($request->title, '-');
        $data['admin_id'] = session('admin_id');
        $data['is_published'] = $request->has('is_published');
        $data['published_at'] = $request->has('is_published') ? now() : null;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
        }

        BlogPost::create($data);
        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı oluşturuldu.');
    }

    public function edit(BlogPost $post)
    {
        $categories = BlogCategory::orderBy('name')->get();
        return view('admin.blog.edit', compact('post', 'categories'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $data             = $request->all();
        $data['is_published'] = $request->has('is_published');
        if ($request->has('is_published') && !$post->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) Storage::disk('public')->delete($post->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
        }

        $post->update($data);
        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı güncellendi.');
    }

    public function destroy(BlogPost $post)
    {
        if ($post->cover_image) Storage::disk('public')->delete($post->cover_image);
        $post->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Blog yazısı silindi.');
    }
}
