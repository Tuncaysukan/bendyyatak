<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, Category $category)
    {
        $categoryIds = $category->children()->pluck('id')->push($category->id);
        $query = Product::whereIn('category_id', $categoryIds)
                        ->where('is_active', true)
                        ->with('images');

        // Filtreleme
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('firmness')) {
            $query->where('firmness_level', $request->firmness);
        }

        // Sıralama
        switch ($request->get('sort', 'newest')) {
            case 'price_asc':  $query->orderBy('price', 'asc');  break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'popular':    $query->orderBy('view_count', 'desc'); break;
            default:           $query->latest(); break;
        }

        $products = $query->paginate(16)->withQueryString();
        $subCategories = $category->children()->where('is_active', true)->get();

        return view('shop.category', compact('category', 'products', 'subCategories'));
    }

    public function showSub(Request $request, Category $parent, Category $category)
    {
        return $this->show($request, $category);
    }
}
