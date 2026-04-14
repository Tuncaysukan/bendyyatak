<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaytrTransaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaytrService
{
    private string $merchantId;
    private string $merchantKey;
    private string $merchantSalt;
    private string $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('services.paytr.merchant_id');
        $this->merchantKey = config('services.paytr.merchant_key');
        $this->merchantSalt = config('services.paytr.merchant_salt');
        $this->baseUrl = 'https://www.paytr.com';
    }

    public function createPayment(Order $order, array $installmentInfo = []): array
    {
        $merchantOid = PaytrTransaction::generateMerchantOid();
        
        // PayTR transaction kaydini olustur
        $paytrTransaction = PaytrTransaction::create([
            'order_id' => $order->id,
            'merchant_oid' => $merchantOid,
            'amount' => $order->total,
            'installment_count' => $installmentInfo['installment'] ?? 1,
            'status' => 'pending',
        ]);

        // PayTR için gerekli parametreler
        $userBasket = $this->prepareBasket($order);
        $userIp = request()->ip();
        $email = $order->customer_email;
        $paymentAmount = $order->total * 100; // PayTR kuruç kullaniyor

        // Hash olusturma
        $hashStr = $this->merchantId . $userIp . $merchantOid . $email . $paymentAmount . $userBasket . $this->merchantSalt;
        $token = base64_encode(hash('sha256', $hashStr, true));

        // PayTR API'ye gönderilecek veriler
        $postData = [
            'merchant_id' => $this->merchantId,
            'user_ip' => $userIp,
            'merchant_oid' => $merchantOid,
            'email' => $email,
            'payment_amount' => $paymentAmount,
            'payment_type' => 'card',
            'paytr_token' => $token,
            'user_basket' => $userBasket,
            'debug_on' => config('app.debug') ? 1 : 0,
            'no_installment' => 0, // Tüm taksit seçenekleri
            'max_installment' => 12, // Maksimum 12 taksit
            'currency' => 'TRY',
            'test_mode' => config('app.env') === 'local' ? 1 : 0,
        ];

        // Taksit bilgisi varsa ekle
        if (!empty($installmentInfo)) {
            $postData['installment_count'] = $installmentInfo['installment'];
            $postData['card_type'] = $installmentInfo['card_type'] ?? '';
        }

        try {
            $response = Http::asForm()->post($this->baseUrl . '/odeme/api/get-token', $postData);
            $responseData = $response->json();

            // Token kaydet
            $paytrTransaction->update([
                'paytr_token' => $responseData['token'] ?? null,
                'paytr_response' => $responseData,
            ]);

            return [
                'success' => true,
                'token' => $responseData['token'] ?? null,
                'iframe_url' => $responseData['iframe_url'] ?? null,
                'merchant_oid' => $merchantOid,
                'paytr_transaction_id' => $paytrTransaction->id,
            ];

        } catch (\Exception $e) {
            Log::error('PayTR payment creation failed: ' . $e->getMessage());
            
            $paytrTransaction->update([
                'status' => 'failed',
                'paytr_response' => ['error' => $e->getMessage()],
            ]);

            return [
                'success' => false,
                'error' => 'Ödeme oluþturulamadý. Lütfen tekrar deneyin.',
            ];
        }
    }

    public function handleCallback(array $callbackData): bool
    {
        $merchantOid = $callbackData['merchant_oid'] ?? null;
        $status = $callbackData['status'] ?? null;
        $hash = $callbackData['hash'] ?? null;

        if (!$merchantOid || !$status || !$hash) {
            Log::error('PayTR callback: Missing required data');
            return false;
        }

        // Hash dogrulama
        $hashStr = $merchantOid . config('services.paytr.merchant_salt') . $status;
        $calculatedHash = base64_encode(hash('sha256', $hashStr, true));

        if ($hash !== $calculatedHash) {
            Log::error('PayTR callback: Hash verification failed');
            return false;
        }

        // Transaction bul
        $transaction = PaytrTransaction::where('merchant_oid', $merchantOid)->first();
        if (!$transaction) {
            Log::error('PayTR callback: Transaction not found');
            return false;
        }

        // Transaction güncelle
        $transaction->update([
            'status' => $status === 'success' ? 'success' : 'failed',
            'callback_data' => $callbackData,
            'paid_at' => $status === 'success' ? now() : null,
            'card_type' => $callbackData['card_type'] ?? null,
            'card_brand' => $callbackData['card_brand'] ?? null,
            'card_bank' => $callbackData['card_bank'] ?? null,
            'card_bank_id' => $callbackData['card_bank_id'] ?? null,
            'card_holder' => $callbackData['card_holder'] ?? null,
            'card_last_four' => $callbackData['card_last_four'] ?? null,
        ]);

        // Sipariþ durumunu güncelle
        if ($status === 'success') {
            $transaction->order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);
        } else {
            $transaction->order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);
        }

        return true;
    }

    public function getInstallments(float $amount, string $bin = null): array
    {
        try {
            $postData = [
                'merchant_id' => $this->merchantId,
                'amount' => $amount * 100, // Kuruç
                'currency' => 'TRY',
            ];

            if ($bin) {
                $postData['bin_number'] = $bin;
            }

            $response = Http::asForm()->post($this->baseUrl . '/odeme/taksit', $postData);
            return $response->json();

        } catch (\Exception $e) {
            Log::error('PayTR installments failed: ' . $e->getMessage());
            return [];
        }
    }

    private function prepareBasket(Order $order): string
    {
        $basket = [];
        
        foreach ($order->items as $item) {
            $basket[] = [
                $item->product->name,
                $item->price,
                $item->quantity,
                $item->product->category?->name ?? 'Ürün'
            ];
        }

        // Kargo bedeli varsa ekle
        if ($order->shipping_cost > 0) {
            $basket[] = ['Kargo Ücreti', $order->shipping_cost, 1, 'Kargo'];
        }

        return base64_encode(json_encode($basket));
    }

    public function getIframeUrl(string $token): string
    {
        return $this->baseUrl . '/odeme/guvenli/' . $token;
    }
}
