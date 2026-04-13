@extends('layouts.admin')
@section('title', 'Sipariş Detayı')
@section('topbar-title', 'Sipariş Detayı')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ $order->order_no }}</div>
        <div class="page-subtitle">{{ $order->created_at->format('d.m.Y H:i') }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri</a>
    </div>
</div>

<div class="grid grid-2" style="margin-bottom:20px;">
    {{-- Sol: Sipariş Durumu + Kargo --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
        {{-- Durum Güncelle --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Sipariş Durumu</span></div>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Mevcut Durum</label>
                    @php $cls = match($order->status) {
                        'delivered'=>'badge-success','shipped'=>'badge-info',
                        'cancelled'=>'badge-danger','processing'=>'badge-indigo',
                        default=>'badge-warning'
                    }; @endphp
                    <div><span class="badge {{ $cls }}" style="font-size:13px;padding:6px 14px;">{{ $order->status_label ?? $order->status }}</span></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Yeni Durum</label>
                    <select name="status" class="form-control">
                        @foreach(['pending'=>'Bekliyor','preparing'=>'Hazırlanıyor','shipped'=>'Kargolandı','delivered'=>'Teslim Edildi','cancelled'=>'İptal Edildi'] as $val=>$label)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Not (Bildirimde görünür)</label>
                    <input type="text" name="note" class="form-control" placeholder="Kargo şirketi, takip numarası vb...">
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Durumu Güncelle</button>
            </form>
        </div>

        {{-- Kargo Bilgisi --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Kargo Bilgisi</span></div>
            <form action="{{ route('admin.orders.cargo', $order) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kargo Şirketi</label>
                    <select name="cargo_company" class="form-control">
                        <option value="">— Seçin —</option>
                        @foreach(['Yurtiçi Kargo','Aras Kargo','MNG Kargo','PTT Kargo','Sürat Kargo','UPS'] as $co)
                            <option value="{{ $co }}" {{ $order->cargo_company === $co ? 'selected':'' }}>{{ $co }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Takip Numarası</label>
                    <input type="text" name="cargo_tracking_no" class="form-control" value="{{ $order->cargo_tracking_no }}">
                </div>
                <button type="submit" class="btn btn-outline"><i class="fas fa-truck"></i> Kargo Bilgisi Kaydet</button>
            </form>
        </div>
    </div>

    {{-- Sağ: Müşteri + Adres --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
        <div class="card">
            <div class="card-header"><span class="card-title">Müşteri Bilgileri</span></div>
            <table style="font-size:13.5px;width:100%;border-collapse:collapse;">
                <tr><td style="padding:6px 0;color:var(--text-muted);width:120px;">Ad Soyad</td><td><strong>{{ $order->customer_name }}</strong></td></tr>
                <tr><td style="padding:6px 0;color:var(--text-muted);">E-Posta</td><td>{{ $order->customer_email }}</td></tr>
                <tr><td style="padding:6px 0;color:var(--text-muted);">Telefon</td><td>{{ $order->customer_phone }}</td></tr>
            </table>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">Teslimat Adresi</span></div>
            <p style="font-size:13.5px;line-height:1.7;color:var(--text-muted);">
                {{ $order->shipping_address['full_name'] ?? '' }}<br>
                {{ $order->shipping_address['line1'] ?? '' }}<br>
                @if($order->shipping_address['line2'] ?? ''){{ $order->shipping_address['line2'] }}<br>@endif
                {{ $order->shipping_address['city'] ?? '' }} / {{ $order->shipping_address['district'] ?? '' }}<br>
                {{ $order->shipping_address['zip'] ?? '' }}
            </p>
        </div>
    </div>
</div>

{{-- Ürünler --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><span class="card-title">Sipariş İçeriği</span></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Ürün</th><th>Varyant</th><th>Adet</th><th>Birim Fiyat</th><th>Toplam</th></tr></thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td style="font-weight:600;">{{ $item->product_name }}</td>
                    <td style="color:var(--text-muted);">{{ $item->variant_name ?? '—' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₺{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td><strong>₺{{ number_format($item->total_price, 2, ',', '.') }}</strong></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f9fafb;">
                    <td colspan="4" style="text-align:right;padding:12px 14px;font-weight:700;">Kargo:</td>
                    <td style="padding:12px 14px;font-weight:700;">₺{{ number_format($order->shipping_cost ?? 0, 2, ',', '.') }}</td>
                </tr>
                @if($order->discount_amount)
                <tr style="background:#f9fafb;">
                    <td colspan="4" style="text-align:right;padding:12px 14px;font-weight:700;color:var(--success);">İndirim:</td>
                    <td style="padding:12px 14px;font-weight:700;color:var(--success);">−₺{{ number_format($order->discount_amount, 2, ',', '.') }}</td>
                </tr>
                @endif
                <tr style="background:#f9fafb;border-top:2px solid var(--border);">
                    <td colspan="4" style="text-align:right;padding:12px 14px;font-weight:800;font-size:16px;">TOPLAM:</td>
                    <td style="padding:12px 14px;font-weight:800;font-size:16px;">₺{{ number_format($order->total, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Durum Geçmişi --}}
@if($order->statusLogs?->isNotEmpty())
<div class="card">
    <div class="card-header"><span class="card-title">Durum Geçmişi</span></div>
    <div style="padding:4px 0;">
        @foreach($order->statusLogs->sortByDesc('created_at') as $log)
        <div style="display:flex;gap:14px;align-items:flex-start;padding:10px 0;border-bottom:1px solid var(--border);">
            <div style="width:8px;height:8px;background:var(--primary);border-radius:50%;margin-top:5px;flex-shrink:0;"></div>
            <div>
                <div style="font-weight:600;font-size:13.5px;">{{ $log->status }}</div>
                @if($log->note)<div style="font-size:12.5px;color:var(--text-muted);">{{ $log->note }}</div>@endif
                <div style="font-size:11.5px;color:var(--text-light);">{{ $log->created_at->format('d.m.Y H:i') }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
