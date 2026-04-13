@extends('layouts.admin')
@section('title', 'Siparişler')
@section('topbar-title', 'Sipariş Yönetimi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Siparişler</div>
        <div class="page-subtitle">Toplam {{ $orders->total() }} sipariş</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.reports.sales') }}" class="btn btn-outline"><i class="fas fa-chart-bar"></i> Satış Raporu</a>
    </div>
</div>

{{-- Filtreler --}}
<div class="card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div class="form-group" style="margin:0;flex:1;min-width:180px;">
            <label class="form-label">Arama</label>
            <input type="text" name="search" class="form-control" placeholder="Sipariş no, müşteri..." value="{{ request('search') }}">
        </div>
        <div class="form-group" style="margin:0;min-width:160px;">
            <label class="form-label">Durum</label>
            <select name="status" class="form-control">
                <option value="">Tümü</option>
                @foreach(['pending'=>'Bekliyor','processing'=>'Hazırlanıyor','shipped'=>'Kargolandı','delivered'=>'Teslim Edildi','cancelled'=>'İptal'] as $val=>$label)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn btn-primary">Filtrele</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">Temizle</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Sipariş No</th>
                    <th>Müşteri</th>
                    <th>Tutar</th>
                    <th>Ödeme</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_no }}</strong></td>
                    <td>
                        <div style="font-weight:600;">{{ $order->customer_name }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">{{ $order->customer_email }}</div>
                    </td>
                    <td><strong>₺{{ number_format($order->total, 2, ',', '.') }}</strong></td>
                    <td style="font-size:12.5px;color:var(--text-muted);">{{ $order->payment_method_label ?? $order->payment_method }}</td>
                    <td>
                        @php $cls = match($order->status) {
                            'delivered'  => 'badge-success',
                            'shipped'    => 'badge-info',
                            'cancelled'  => 'badge-danger',
                            'processing' => 'badge-indigo',
                            default      => 'badge-warning',
                        }; @endphp
                        <span class="badge {{ $cls }}">{{ $order->status_label ?? $order->status }}</span>
                    </td>
                    <td style="color:var(--text-muted);font-size:12px;">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">
                    <i class="fas fa-shopping-bag" style="font-size:32px;display:block;margin-bottom:12px;"></i> Henüz sipariş yok.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:0 0 10px;">{{ $orders->links() }}</div>
</div>
@endsection
