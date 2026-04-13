<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockAlert;
use App\Models\WhatsappClick;
use App\Models\InstallmentPlan;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        if (!$product->is_active) abort(404);

        $product->load('images', 'variants', 'attributes', 'reviews', 'category');

        // Benzer ürünler
        $relatedProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $product->id)
                                  ->where('is_active', true)
                                  ->with('images')
                                  ->take(4)
                                  ->get();

        // Taksit planları
        $installmentPlans = InstallmentPlan::where('is_active', true)
                                           ->orderBy('sort_order')
                                           ->get()
                                           ->groupBy('bank_name');

        // Karşılaştırma listesi
        $compareList = session()->get('compare_list', []);

        // Ortalama rating
        $avgRating = $product->reviews->avg('rating');

        return view('shop.product', compact('product', 'relatedProducts', 'installmentPlans', 'compareList', 'avgRating'));
    }

    public function compare()
    {
        $compareIds  = session()->get('compare_list', []);
        $products    = Product::whereIn('id', $compareIds)
                              ->with('images', 'attributes', 'variants')
                              ->get();

        // Tüm attribute key'lerini topla
        $allKeys = $products->flatMap(fn($p) => $p->attributes->pluck('key'))->unique()->values();

        return view('shop.compare', compact('products', 'allKeys'));
    }

    public function addToCompare(Request $request)
    {
        $productId   = $request->product_id;
        $compareList = session()->get('compare_list', []);

        if (count($compareList) >= 3) {
            return response()->json(['success' => false, 'message' => 'En fazla 3 ürün karşılaştırabilirsiniz.']);
        }
        if (!in_array($productId, $compareList)) {
            $compareList[] = $productId;
            session()->put('compare_list', $compareList);
        }
        return response()->json(['success' => true, 'count' => count($compareList)]);
    }

    public function removeFromCompare(Request $request)
    {
        $compareList = session()->get('compare_list', []);
        $compareList = array_filter($compareList, fn($id) => $id != $request->product_id);
        session()->put('compare_list', array_values($compareList));
        return response()->json(['success' => true, 'count' => count($compareList)]);
    }

    public function stockAlert(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'product_id' => 'required|exists:products,id',
        ]);

        StockAlert::firstOrCreate([
            'product_id'         => $request->product_id,
            'product_variant_id' => $request->variant_id,
            'email'              => $request->email,
        ]);

        return response()->json(['success' => true, 'message' => 'Stok geldiğinde size haber vereceğiz!']);
    }

    public function whatsappClick(Request $request)
    {
        \DB::table('whatsapp_clicks')->insert([
            'product_id' => $request->product_id,
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return response()->json(['success' => true]);
    }
}
