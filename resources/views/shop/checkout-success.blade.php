@extends('layouts.app')

@section('seo_title', 'Siparişiniz Alındı — BendyyYatak')

@section('content')
<div class="container" style="padding-top:60px;padding-bottom:100px; max-width: 800px; text-align: center;">
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:60px 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
        
        <div style="width: 80px; height: 80px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <i class="fas fa-check" style="font-size: 40px; color: #15803d;"></i>
        </div>

        <h1 style="font-size:32px; font-weight:800; margin-bottom:16px; color:var(--text);">Siparişiniz Başarıyla Alındı!</h1>
        <p style="font-size: 16px; color: #6b7280; line-height: 1.6; margin-bottom: 32px;">
            Teşekkür ederiz <strong>{{ $order->first_name }} {{ $order->last_name }}</strong>, siparişiniz başarıyla sistemimize kaydedildi. Sipariş detaylarınızı e-posta adresinize de gönderdik.
        </p>

        <div style="background:#f9fafb; border: 1px dashed #ced4da; border-radius: 12px; padding: 24px; display: inline-block; text-align: left; margin-bottom: 40px; width: 100%; max-width: 400px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                <span style="color: #6b7280;">Sipariş Numarası:</span>
                <strong style="color: var(--primary);">#{{ $order->order_no }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                <span style="color: #6b7280;">Sipariş Tarihi:</span>
                <strong>{{ $order->created_at->format('d.m.Y H:i') }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                <span style="color: #6b7280;">Ödeme Yöntemi:</span>
                <strong>{{ $order->payment_method === 'iyzico' ? 'Kredi Kartı' : ($order->payment_method === 'bank_transfer' ? 'Havale/EFT' : 'Kapıda Ödeme') }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 16px; border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 4px;">
                <span style="color: #4b5563; font-weight: 600;">Toplam Tutar:</span>
                <strong style="color: var(--text);">₺{{ number_format($order->total_amount, 2, ',', '.') }}</strong>
            </div>
        </div>

        <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
            @auth
            <a href="{{ route('account.order.detail', $order->id) }}" class="btn btn-outline" style="padding: 14px 28px; border-radius: 99px;">
                Siparişi Görüntüle
            </a>
            @else
            <a href="{{ route('order.track') }}?order_no={{ $order->order_no }}&email={{ $order->email }}" class="btn btn-outline" style="padding: 14px 28px; border-radius: 99px;">
                Siparişi Takip Et
            </a>
            @endauth
            <a href="{{ route('home') }}" class="btn btn-primary" style="padding: 14px 28px; border-radius: 99px;">
                Alışverişe Devam Et
            </a>
        </div>
    </div>
</div>
@endsection
