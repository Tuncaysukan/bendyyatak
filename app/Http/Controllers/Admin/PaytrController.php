<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaytrTransaction;
use App\Models\Order;
use Illuminate\Http\Request;

class PaytrController extends Controller
{
    public function index(Request $request)
    {
        $query = PaytrTransaction::with('order');

        // Filtreler
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('merchant_oid', 'like', "%{$search}%")
                  ->orWhereHas('order', function($subQ) use ($search) {
                      $subQ->where('order_no', 'like', "%{$search}%")
                           ->orWhere('customer_email', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')
                            ->paginate(20)
                            ->withQueryString();

        // Istatistikler
        $stats = [
            'total' => PaytrTransaction::count(),
            'success' => PaytrTransaction::where('status', 'success')->count(),
            'failed' => PaytrTransaction::where('status', 'failed')->count(),
            'pending' => PaytrTransaction::where('status', 'pending')->count(),
            'total_amount' => PaytrTransaction::where('status', 'success')->sum('amount'),
        ];

        return view('admin.paytr.index', compact('transactions', 'stats'));
    }

    public function show(PaytrTransaction $transaction)
    {
        $transaction->load('order.items.product', 'order.user');
        return view('admin.paytr.show', compact('transaction'));
    }

    public function report(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        // Günlük ödeme istatistikleri
        $dailyStats = PaytrTransaction::where('status', 'success')
            ->whereDate('paid_at', '>=', $dateFrom)
            ->whereDate('paid_at', '<=', $dateTo)
            ->selectRaw('DATE(paid_at) as date, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Banka bazinda istatistikler
        $bankStats = PaytrTransaction::where('status', 'success')
            ->whereNotNull('card_bank')
            ->selectRaw('card_bank, COUNT(*) as count, SUM(amount) as total, AVG(amount) as avg_amount')
            ->groupBy('card_bank')
            ->orderBy('total', 'desc')
            ->get();

        // Taksit istatistikleri
        $installmentStats = PaytrTransaction::where('status', 'success')
            ->selectRaw('installment_count, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('installment_count')
            ->orderBy('installment_count')
            ->get();

        // Kart marka istatistikleri
        $cardBrandStats = PaytrTransaction::where('status', 'success')
            ->whereNotNull('card_brand')
            ->selectRaw('card_brand, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('card_brand')
            ->orderBy('total', 'desc')
            ->get();

        return view('admin.paytr.report', compact(
            'dailyStats', 'bankStats', 'installmentStats', 'cardBrandStats',
            'dateFrom', 'dateTo'
        ));
    }

    public function settings()
    {
        return view('admin.paytr.settings');
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'paytr_merchant_id' => 'nullable|string|max:100',
            'paytr_merchant_key' => 'nullable|string|max:100',
            'paytr_merchant_salt' => 'nullable|string|max:100',
            'paytr_max_installment' => 'nullable|integer|min:1|max:12',
        ]);

        // Ayarlari kaydet
        \App\Models\Setting::set('paytr_merchant_id', $request->paytr_merchant_id ?? '');
        \App\Models\Setting::set('paytr_merchant_key', $request->paytr_merchant_key ?? '');
        \App\Models\Setting::set('paytr_merchant_salt', $request->paytr_merchant_salt ?? '');
        \App\Models\Setting::set('paytr_test_mode', $request->has('paytr_test_mode') ? '1' : '0');
        \App\Models\Setting::set('paytr_active', $request->has('paytr_active') ? '1' : '0');
        \App\Models\Setting::set('paytr_max_installment', $request->paytr_max_installment ?? '12');

        // .env dosyasini da güncelle
        $this->updateEnv([
            'PAYTR_MERCHANT_ID' => $request->paytr_merchant_id ?? '',
            'PAYTR_MERCHANT_KEY' => $request->paytr_merchant_key ?? '',
            'PAYTR_MERCHANT_SALT' => $request->paytr_merchant_salt ?? '',
        ]);

        return redirect()->route('admin.paytr.settings')->with('success', 'PayTR ayarlari basariyla kaydedildi!');
    }

    private function updateEnv(array $data): void
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) return;

        $content = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            // Mevcut degeri güncelle veya ekle
            if (strpos($content, $key . '=') !== false) {
                $content = preg_replace('/^' . $key . '=.*/m', $key . '=' . $value, $content);
            } else {
                $content .= "\n" . $key . '=' . $value;
            }
        }

        file_put_contents($envPath, $content);
    }

    public function export(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        $transactions = PaytrTransaction::with('order')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="paytr_transactions_' . $dateFrom . '_to_' . $dateTo . '.csv"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV basliklari
            fputcsv($file, [
                'Tarih', 'Siparis No', 'Musteri', 'Email', 'Tutar',
                'Durum', 'Taksit', 'Banka', 'Kart Marka', 'Kart Son 4',
                'Kart Sahibi', 'Odeme Tarihi'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('d.m.Y H:i'),
                    $transaction->order->order_no ?? 'N/A',
                    $transaction->order->customer_name ?? 'N/A',
                    $transaction->order->customer_email ?? 'N/A',
                    number_format($transaction->amount, 2, ',', '.') . ' TL',
                    $transaction->status_label,
                    $transaction->installment_count . ' taksit',
                    $transaction->card_bank ?? 'N/A',
                    $transaction->card_brand ?? 'N/A',
                    $transaction->card_last_four ?? 'N/A',
                    $transaction->card_holder ?? 'N/A',
                    $transaction->paid_at?->format('d.m.Y H:i') ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
