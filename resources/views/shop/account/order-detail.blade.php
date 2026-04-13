@extends('layouts.account')
@section('title', 'Sipariş Detayı')

@section('account_content')
<div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 12px; margin-bottom: 20px;">
    <h2 style="font-size: 20px; margin: 0;">Sipariş Detayı (#{{ $order->order_no }})</h2>
    <a href="{{ route('account.orders') }}" style="color: #666; text-decoration: none; font-size: 14px;"><i class="fas fa-arrow-left"></i> Geri Dön</a>
</div>

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

<div class="card" style="padding: 24px; margin-bottom: 24px;">
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

<h3 style="font-size: 18px; margin-bottom: 16px;">Sipariş Kalemleri</h3>
<div style="background: white; border: 1px solid #eee; border-radius: 8px; overflow: hidden; margin-bottom: 24px;">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead style="background: #f9f9f9; font-size: 13px; color: #666;">
            <tr>
                <th style="padding: 12px 16px; border-bottom: 1px solid #eee;">Ürün</th>
                <th style="padding: 12px 16px; border-bottom: 1px solid #eee;">Birim Fiyat</th>
                <th style="padding: 12px 16px; border-bottom: 1px solid #eee;">Adet</th>
                <th style="padding: 12px 16px; border-bottom: 1px solid #eee;">Toplam</th>
            </tr>
        </thead>
        <tbody style="font-size: 14px;">
            @foreach($order->items as $item)
            <tr>
                <td style="padding: 12px 16px; border-bottom: 1px solid #eee;">
                    <div style="font-weight: 600;">{{ $item->product_name }}</div>
                    @if($item->variant_name)
                        <div style="font-size: 12px; color: #666;">Varyant: {{ $item->variant_name }}</div>
                    @endif
                </td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #eee;">{{ number_format($item->unit_price, 2) }} ₺</td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #eee;">{{ $item->quantity }}</td>
                <td style="padding: 12px 16px; border-bottom: 1px solid #eee; font-weight: bold;">{{ number_format($item->total_price, 2) }} ₺</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
