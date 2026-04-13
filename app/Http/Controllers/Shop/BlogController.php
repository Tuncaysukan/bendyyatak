<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('is_published', true)
                         ->with('category')
                         ->latest('published_at')
                         ->paginate(12);
        return view('shop.blog.index', compact('posts'));
    }

    public function show(BlogPost $post)
    {
        if (!$post->is_published) abort(404);
        $related = BlogPost::where('blog_category_id', $post->blog_category_id)
                           ->where('id', '!=', $post->id)
                           ->where('is_published', true)
                           ->take(3)
                           ->get();
        return view('shop.blog.show', compact('post', 'related'));
    }
}
