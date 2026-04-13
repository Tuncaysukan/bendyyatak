@extends('layouts.admin')

@section('title', 'Dashboard')
@section('topbar-title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Hoş Geldiniz 👋</div>
        <div class="page-subtitle">{{ now()->format('d F Y, l') }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Ürün Ekle
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-shopping-bag"></i> Siparişler
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-4" style="margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-turkish-lira-sign"></i></div>
        <div>
            <div class="stat-value">₺{{ number_format($monthRevenue, 0, ',', '.') }}</div>
            <div class="stat-label">Bu Ay Gelir</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-shopping-bag"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">Toplam Sipariş</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-value">{{ $pendingOrders }}</div>
            <div class="stat-label">Bekleyen Sipariş</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalCustomers) }}</div>
            <div class="stat-label">Toplam Müşteri</div>
        </div>
    </div>
</div>

<!-- Charts + Tables -->
<div class="grid grid-2" style="margin-bottom:24px;">
    <!-- Gelir Grafiği -->
    <div class="card" style="grid-column: span 2;">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-line" style="color:var(--primary);margin-right:8px;"></i> Son 7 Günlük Gelir</span>
            <a href="{{ route('admin.reports.sales') }}" class="btn btn-outline btn-sm">Tam Rapor</a>
        </div>
        <canvas id="revenueChart" height="90"></canvas>
    </div>
</div>

<div class="grid grid-2" style="margin-bottom:24px;">
    <!-- En Çok Görüntülenen -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-fire" style="color:#ef4444;margin-right:8px;"></i> En Çok Görüntülenen</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Ürün</th><th>Görüntülenme</th></tr>
                </thead>
                <tbody>
                    @foreach($topViewedProducts as $product)
                    <tr>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" style="color:var(--text);text-decoration:none;font-weight:500;">
                                {{ Str::limit($product->name, 35) }}
                            </a>
                        </td>
                        <td><span class="badge badge-primary">{{ number_format($product->view_count) }}</span></td>
                    </tr>
                    @endforeach
                    @if($topViewedProducts->isEmpty())
                        <tr><td colspan="2" style="text-align:center;color:var(--text-muted);padding:24px;">Henüz veri yok</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Düşük Stok Uyarısı -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-triangle-exclamation" style="color:#f59e0b;margin-right:8px;"></i> Düşük Stok Uyarısı</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Ürün / Varyant</th><th>Stok</th></tr>
                </thead>
                <tbody>
                    @foreach($lowStockVariants as $variant)
                    <tr>
                        <td>
                            <div style="font-weight:500;">{{ Str::limit($variant->product->name ?? '', 25) }}</div>
                            <div style="font-size:11.5px;color:var(--text-muted);">{{ $variant->name }}</div>
                        </td>
                        <td><span class="badge badge-warning">{{ $variant->stock }} adet</span></td>
                    </tr>
                    @endforeach
                    @if($lowStockVariants->isEmpty())
                        <tr><td colspan="2" style="text-align:center;color:var(--text-muted);padding:24px;">Düşük stok yok ✓</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Son Siparişler -->
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-shopping-bag" style="color:var(--primary);margin-right:8px;"></i> Son Siparişler</span>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Tümünü Gör</a>
    </div>
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
                @foreach($recentOrders as $order)
                <tr>
                    <td><strong>{{ $order->order_no }}</strong></td>
                    <td>{{ $order->customer_name }}</td>
                    <td>₺{{ number_format($order->total, 2, ',', '.') }}</td>
                    <td>{{ $order->payment_method_label }}</td>
                    <td>
                        @php
                        $cls = match($order->status) {
                            'delivered' => 'badge-success',
                            'shipped'   => 'badge-info',
                            'cancelled' => 'badge-danger',
                            'pending'   => 'badge-warning',
                            default     => 'badge-secondary',
                        };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $order->status_label }}</span>
                    </td>
                    <td style="color:var(--text-muted);font-size:12px;">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($recentOrders->isEmpty())
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:32px;">Henüz sipariş yok.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Gelir (₺)',
            data: @json($revenue),
            borderColor: '#6c47ff',
            backgroundColor: 'rgba(108,71,255,.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 2.5,
            pointBackgroundColor: '#6c47ff',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => '₺' + ctx.parsed.y.toLocaleString('tr-TR', {minimumFractionDigits: 2})
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,.05)' },
                ticks: {
                    callback: v => '₺' + v.toLocaleString('tr-TR')
                }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
