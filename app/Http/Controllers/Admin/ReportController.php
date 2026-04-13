<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $period = (int) $request->get('period', 30);
        $from   = now()->subDays($period);

        // Günlük gelir verisi (bar chart)
        $dailyData = Order::select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as cnt')
            )
            ->where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $from)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $labels   = $dailyData->pluck('day')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray();
        $revenues = $dailyData->pluck('revenue')->map(fn($r) => (float) $r)->toArray();

        $totalRevenue = Order::where('status', '!=', 'cancelled')->where('created_at', '>=', $from)->sum('total');
        $totalOrders  = Order::where('status', '!=', 'cancelled')->where('created_at', '>=', $from)->count();
        $totalItems   = OrderItem::whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled')->where('created_at', '>=', $from))->sum('quantity');

        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(total_price) as revenue'))
                                ->whereHas('order', fn($q) => $q->where('created_at', '>=', $from))
                                ->groupBy('product_name')
                                ->orderByDesc('qty')
                                ->take(8)
                                ->get();

        $statusCounts = Order::select('status', DB::raw('COUNT(*) as cnt'))->where('created_at', '>=', $from)->groupBy('status')->get();
        $statusMap    = ['pending'=>'Bekliyor','processing'=>'Hazırlanıyor','shipped'=>'Kargolandı','delivered'=>'Teslim','cancelled'=>'İptal'];
        $statusLabels = $statusCounts->pluck('status')->map(fn($s) => $statusMap[$s] ?? $s)->toArray();
        $statusData   = $statusCounts->pluck('cnt')->toArray();

        $recentOrders = Order::with('items')->orderByDesc('created_at')->take(10)->get();

        return view('admin.reports.sales', compact(
            'labels', 'revenues', 'totalRevenue', 'totalOrders', 'totalItems',
            'topProducts', 'statusLabels', 'statusData', 'recentOrders', 'period'
        ));
    }

    public function views(Request $request)
    {
        $products    = Product::with(['category', 'images'])->orderByDesc('view_count')->get();
        $totalViews  = $products->sum('view_count');
        $totalProducts = $products->where('is_active', true)->count();
        $topProduct  = $products->first();

        return view('admin.reports.views', compact('products', 'totalViews', 'totalProducts', 'topProduct'));
    }

    public function exportSales(Request $request)
    {
        $orders = Order::with('items')
                       ->where('payment_status', 'paid')
                       ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
                       ->when($request->date_to,   fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
                       ->get();

        $filename = 'satis-raporu-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            fputcsv($file, ['Sipariş No', 'Tarih', 'Müşteri', 'Tutar', 'Ödeme Yöntemi', 'Durum']);
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_no,
                    $order->created_at->format('d.m.Y H:i'),
                    $order->customer_name,
                    number_format($order->total, 2) . ' ₺',
                    $order->payment_method_label,
                    $order->status_label,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
