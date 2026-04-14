<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaytrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaytrController extends Controller
{
    public function __construct(private PaytrService $paytrService)
    {
    }

    public function create(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'installment' => 'nullable|integer|min:1|max:12',
            'card_type' => 'nullable|string',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Sipariþ kontrolü
        if ($order->user_id && $order->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'error' => 'Yetkisiz iþlem'], 403);
        }

        if ($order->payment_status === 'paid') {
            return response()->json(['success' => false, 'error' => 'Bu sipariþ zaten ödendi'], 400);
        }

        $installmentInfo = [
            'installment' => $request->installment ?? 1,
            'card_type' => $request->card_type,
        ];

        $result = $this->paytrService->createPayment($order, $installmentInfo);

        return response()->json($result);
    }

    public function callback(Request $request)
    {
        Log::info('PayTR callback received', $request->all());

        $success = $this->paytrService->handleCallback($request->all());

        if ($success) {
            return response('OK', 200);
        }

        return response('FAIL', 400);
    }

    public function getInstallments(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'bin' => 'nullable|string|size:6',
        ]);

        $installments = $this->paytrService->getInstallments(
            $request->amount,
            $request->bin
        );

        return response()->json($installments);
    }

    public function iframe(string $token)
    {
        return view('shop.paytr-iframe', compact('token'));
    }
}
