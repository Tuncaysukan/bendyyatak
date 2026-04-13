<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\BlogPost;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
                                   ->where('is_active', true)
                                   ->with('images')
                                   ->take(8)
                                   ->get();

        $categories = Category::whereNull('parent_id')
                               ->where('is_active', true)
                               ->orderBy('sort_order')
                               ->get();

        $newProducts = Product::where('is_active', true)
                               ->where('is_new_arrival', true)
                               ->with('images')
                               ->latest()
                               ->take(8)
                               ->get();

        // Fallback for new products if none marked
        if ($newProducts->isEmpty()) {
            $newProducts = Product::where('is_active', true)
                                   ->with('images')
                                   ->latest()
                                   ->take(8)
                                   ->get();
        }

        $popularProducts = Product::where('is_active', true)
                                   ->where('is_bestseller', true)
                                   ->with('images')
                                   ->take(8)
                                   ->get();

        // Fallback for popular if none marked
        if ($popularProducts->isEmpty()) {
            $popularProducts = Product::where('is_active', true)
                                       ->with('images')
                                       ->orderBy('view_count', 'desc')
                                       ->take(8)
                                       ->get();
        }

        $latestPosts = BlogPost::where('is_published', true)
                               ->latest('published_at')
                               ->take(3)
                               ->get();

        $sliders = \App\Models\Slider::where('is_active', true)
                                     ->orderBy('sort_order')
                                     ->get();

        return view('shop.home', compact(
            'featuredProducts', 'categories', 'newProducts', 'popularProducts', 'latestPosts', 'sliders'
        ));
    }
}
