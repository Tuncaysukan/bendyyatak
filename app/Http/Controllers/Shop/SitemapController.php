<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\BlogPost;

class SitemapController extends Controller
{
    public function index()
    {
        $products   = Product::where('is_active', true)->select('slug', 'updated_at')->get();
        $categories = Category::where('is_active', true)->select('slug', 'updated_at')->get();
        $posts      = BlogPost::where('is_published', true)->select('slug', 'updated_at')->get();

        return response()->view('shop.sitemap', compact('products', 'categories', 'posts'))
                         ->header('Content-Type', 'application/xml');
    }
}
