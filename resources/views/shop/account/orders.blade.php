@extends('layouts.account')
@section('title', 'Siparişlerim')

@section('account_content')
<h2 style="font-size: 20px; border-bottom: 2px solid #f0f0f0; padding-bottom: 12px; margin-bottom: 20px;">Siparişlerim</h2>

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
                    <td style="padding: 12px 16px; border-bottom: 1px solid #eee;">
                        @if($order->status == 'pending') <span style="background: #fef3c7; color: #b45309; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Bekliyor</span>
                        @elseif($order->status == 'confirmed') <span style="background: #dcfce7; color: #15803d; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Onaylandı</span>
                        @elseif($order->status == 'preparing') <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Hazırlanıyor</span>
                        @elseif($order->status == 'shipped') <span style="background: #e0e7ff; color: #4338ca; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Kargoda</span>
                        @elseif($order->status == 'delivered') <span style="background: #dcfce7; color: #15803d; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Teslim Edildi</span>
                        @elseif($order->status == 'cancelled') <span style="background: #fee2e2; color: #b91c1c; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">İptal Edildi</span>
                        @elseif($order->status == 'refunded') <span style="background: #fee2e2; color: #b91c1c; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">İade Edildi</span>
                        @else {{ strtoupper($order->status) }} @endif
                    </td>
                    <td style="padding: 12px 16px; border-bottom: 1px solid #eee;">
                        <a href="{{ route('account.order.detail', $order->id) }}" style="color: white; background: var(--primary); padding: 6px 12px; border-radius: 6px; font-weight: 500; font-size: 12px; text-decoration: none;">Detay</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $orders->links() }}
    </div>
@else
    <div style="background: white; border: 1px solid #eee; border-radius: 8px; padding: 32px; text-align: center; color: #666;">
        <i class="fas fa-shopping-bag" style="font-size: 40px; color: #ccc; margin-bottom: 16px; display: block;"></i>
        Henüz hiç siparişiniz bulunmuyor.
    </div>
@endif
@endsection
