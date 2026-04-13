@extends('layouts.app')
@section('title', 'Sipariş Takibi')

@section('content')
<div class="container" style="max-width: 600px; margin: 40px auto; padding: 20px;">
    <h1 style="text-align: center; margin-bottom: 24px; font-size: 28px;">Sipariş Takibi</h1>
    
    @if(session('error') || $errors->any())
        <div style="background:var(--danger); color:white; padding:12px; border-radius:8px; margin-bottom:20px;">
            Sipariş bulunamadı. Lütfen bilgilerinizi kontrol ediniz.
        </div>
    @endif

    <div class="card" style="padding: 24px;">
        <form action="{{ route('order.track.post') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 16px;">
                <label style="display:block; font-weight:600; margin-bottom:8px;">Sipariş Numarası</label>
                <input type="text" name="order_no" class="form-control" placeholder="Örn: ORD-12345678" value="{{ old('order_no') }}" required style="width:100%; padding:10px 14px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
                <label style="display:block; font-weight:600; margin-bottom:8px;">E-Posta Adresi</label>
                <input type="email" name="email" class="form-control" placeholder="Sipariş verirken kullandığınız e-posta" value="{{ old('email') }}" required style="width:100%; padding:10px 14px; border:1px solid #ccc; border-radius:6px;">
            </div>
            <button type="submit" style="width: 100%; background:var(--primary); color:white; padding:12px; border:none; border-radius:6px; font-size:16px; font-weight:bold; cursor:pointer;">
                Sorgula
            </button>
        </form>
    </div>

    @if(isset($order))
    @php
    $statusLabels = [
        'pending' => 'Bekliyor',
        'confirmed' => 'Onaylandı',
        'preparing' => 'Hazırlanıyor',
        'shipped' => 'Kargoya Verildi',
        'delivered' => 'Teslim Edildi',
        'cancelled' => 'İptal Edildi',
        'refunded' => 'İade Edildi'
    ];
    $paymentLabels = [
        'unpaid' => 'Ödenmedi',
        'paid' => 'Ödendi',
        'refunded' => 'İade Edildi',
        'failed' => 'Başarısız',
        'pending' => 'Bekliyor'
    ];
    @endphp
    <div class="card" style="margin-top: 32px; padding: 24px;">
        <h3 style="margin-bottom: 16px;">Sipariş #{{ $order->order_no }}</h3>
        
        <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
            <span style="color:#666;">Tarih:</span>
            <strong>{{ $order->created_at->format('d.m.Y H:i') }}</strong>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
            <span style="color:#666;">Toplam Tutar:</span>
            <strong>{{ number_format($order->total, 2) }} ₺</strong>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
            <span style="color:#666;">Ödeme Durumu:</span>
            <strong>{{ mb_strtoupper($paymentLabels[$order->payment_status] ?? $order->payment_status) }}</strong>
        </div>
        <div style="display:flex; justify-content:space-between; margin-bottom:24px;">
            <span style="color:#666;">Sipariş Durumu:</span>
            <strong>{{ mb_strtoupper($statusLabels[$order->status] ?? $order->status) }}</strong>
        </div>

        <h4 style="margin-bottom: 12px; border-bottom: 1px solid #eee; padding-bottom:8px;">Sipariş Geçmişi</h4>
        <ul style="list-style:none; padding:0; margin:0;">
            @foreach($order->statusLogs as $log)
                <li style="margin-bottom: 12px; border-left: 3px solid var(--primary); padding-left: 12px;">
                    <strong style="display:block; font-size:14px; color:var(--primary);">{{ mb_strtoupper($statusLabels[$log->status] ?? $log->status) }}</strong>
                    <p style="margin: 4px 0 0; font-size:13px;">{{ $log->note }}</p>
                    <span style="font-size:11px; color:#999;">{{ $log->created_at->format('d.m.Y H:i') }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
