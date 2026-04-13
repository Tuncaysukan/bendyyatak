<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');

        $products = collect();
        if (strlen($query) >= 2) {
            $products = Product::where('is_active', true)
                               ->where(function ($q) use ($query) {
                                   $q->where('name', 'like', '%' . $query . '%')
                                     ->orWhere('short_description', 'like', '%' . $query . '%');
                               })
                               ->with('images')
                               ->paginate(16)
                               ->withQueryString();
        }

        return view('shop.search', compact('products', 'query'));
    }
}
