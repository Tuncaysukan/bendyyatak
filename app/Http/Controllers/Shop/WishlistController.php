<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistIds = session()->get('wishlist', []);
        $products    = Product::whereIn('id', $wishlistIds)->with('images')->get();
        return view('shop.wishlist', compact('products'));
    }

    public function add(Request $request)
    {
        $wishlist = session()->get('wishlist', []);
        if (!in_array($request->product_id, $wishlist)) {
            $wishlist[] = $request->product_id;
            session()->put('wishlist', $wishlist);
        }
        return response()->json(['success' => true, 'count' => count($wishlist)]);
    }

    public function remove(Request $request)
    {
        $wishlist = session()->get('wishlist', []);
        $wishlist = array_filter($wishlist, fn($id) => $id != $request->product_id);
        session()->put('wishlist', array_values($wishlist));
        return response()->json(['success' => true, 'count' => count($wishlist)]);
    }
}
