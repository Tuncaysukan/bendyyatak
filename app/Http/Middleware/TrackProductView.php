<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class TrackProductView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Sadece ürün sayfasında ve GET isteğinde sayım yap
        if ($request->route('product') && $request->isMethod('get')) {
            $product = $request->route('product');
            if ($product instanceof Product) {
                $sessionKey = 'viewed_product_' . $product->id;
                if (!session()->has($sessionKey)) {
                    $product->increment('view_count');
                    session()->put($sessionKey, true);
                }
            }
        }

        return $response;
    }
}
