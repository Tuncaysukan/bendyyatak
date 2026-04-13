@extends('layouts.account')
@section('title', 'Hesap Özeti')

@section('account_content')
<h2 style="font-size: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 12px; margin-bottom: 20px;">Hesap Özeti</h2>

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
@endphp

<div class="card" style="padding: 24px; margin-bottom: 24px;">
    <h3>Hoş Geldiniz, {{ $user->name }}!</h3>
    <p style="color: #666; margin-top: 8px;">Hesabınız üzerinden son siparişlerinizi takip edebilir, kargo adreslerinizi ve hesap detaylarınızı düzenleyebilirsiniz.</p>
</div>

<h3 style="font-size: 18px; margin-bottom: 16px;">Son Siparişlerim</h3>
@if($orders->count() > 0)
    <div style="background: white; border: 1px solid #eee; border-radius: 8px; overflow-x: auto;">
        <table style="min-width: 600px; width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #f9f9f9; font-size: 13px; color: #666;">
                <tr>
                    <th style="padding: 12px 16px; border-bottom: 1px solid #eee;">Sipariş No</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid #eee;">Tarih</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid #eee;">Tutar</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid #eee;">Durum</th>
                    <th style="padding: 12px 16px; border-bottom: 1px solid #eee;">İşlem</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px;">
                @foreach($orders as $order)
                <tr>
                    <td style="padding: 12px 16px; border-bottom: 1px solid #eee;"><strong>#{{ $order->order_no }}</strong></td>
                    <td style="padding: 12px 16px; border-bottom: 1px solid #eee;">{{ $order->created_at->format('d.m.Y') }}</td>
                    <td style="padding: 12px 16px; border-bottom: 1px solid #eee;">{{ number_format($order->total, 2) }} ₺</td>
                    <td style="padding: 12px 16px; border-bottom: 1px solid #eee;">{{ mb_strtoupper($statusLabels[$order->status] ?? $order->status) }}</td>
                    <td style="padding: 12px 16px; border-bottom: 1px solid #eee;">
                        <a href="{{ route('account.order.detail', $order->id) }}" style="color: var(--primary); font-weight: bold; text-decoration: none;">Detay</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div style="background: white; border: 1px solid #eee; border-radius: 8px; padding: 32px; text-align: center; color: #666;">
        <i class="fas fa-shopping-bag" style="font-size: 40px; color: #ccc; margin-bottom: 16px; display: block;"></i>
        Henüz hiç siparişiniz bulunmuyor.
        <br><br>
        <a href="{{ route('home') }}" class="btn" style="background: var(--primary); color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; display: inline-block;">Alışverişe Başla</a>
    </div>
@endif
@endsection
