<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Temel istatistikler
        $todayRevenue   = Order::whereDate('created_at', today())
                               ->whereIn('payment_status', ['paid'])
                               ->sum('total');
        $monthRevenue   = Order::whereMonth('created_at', now()->month)
                               ->whereYear('created_at', now()->year)
                               ->where('payment_status', 'paid')
                               ->sum('total');
        $totalOrders    = Order::count();
        $pendingOrders  = Order::where('status', 'pending')->count();
        $totalProducts  = Product::where('is_active', true)->count();
        $totalCustomers = User::count();

        // Son 7 günlük gelir grafiği
        $revenueChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [now()->subDays(6), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Grafik için 7 günlük dizi
        $labels  = [];
        $revenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date      = now()->subDays($i)->format('Y-m-d');
            $labels[]  = now()->subDays($i)->format('d M');
            $revenue[] = isset($revenueChart[$date]) ? (float) $revenueChart[$date]->total : 0;
        }

        // En çok görüntülenen ürünler
        $topViewedProducts = Product::orderBy('view_count', 'desc')
                                    ->take(5)
                                    ->get();

        // Son siparişler
        $recentOrders = Order::with('items')
                             ->latest()
                             ->take(10)
                             ->get();

        // Düşük stok uyarısı
        $lowStockVariants = \App\Models\ProductVariant::where('stock', '>', 0)
                                                      ->where('stock', '<=', 5)
                                                      ->with('product')
                                                      ->take(5)
                                                      ->get();

        return view('admin.dashboard', compact(
            'todayRevenue', 'monthRevenue', 'totalOrders', 'pendingOrders',
            'totalProducts', 'totalCustomers',
            'labels', 'revenue',
            'topViewedProducts', 'recentOrders', 'lowStockVariants'
        ));
    }
}
