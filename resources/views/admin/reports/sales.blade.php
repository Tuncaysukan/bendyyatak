@extends('layouts.admin')
@section('title', 'Satış Raporları')
@section('topbar-title', 'Satış Raporları')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Satış Raporları</div>
        <div class="page-subtitle">{{ now()->format('Y') }} yılı analizi</div>
    </div>
    <form method="GET" style="display:flex;gap:8px;">
        <select name="period" class="form-control" style="min-width:150px;" onchange="this.form.submit()">
            <option value="7"   {{ request('period','30') == '7'  ? 'selected':'' }}>Son 7 Gün</option>
            <option value="30"  {{ request('period','30') == '30' ? 'selected':'' }}>Son 30 Gün</option>
            <option value="90"  {{ request('period','30') == '90' ? 'selected':'' }}>Son 90 Gün</option>
            <option value="365" {{ request('period','30') == '365'? 'selected':'' }}>Bu Yıl</option>
        </select>
    </form>
</div>

{{-- KPI Kartları --}}
<div class="grid grid-4" style="margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-turkish-lira-sign"></i></div>
        <div>
            <div class="stat-value">₺{{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-label">Toplam Gelir</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-shopping-bag"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">Sipariş Sayısı</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-chart-simple"></i></div>
        <div>
            <div class="stat-value">₺{{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 0, ',', '.') : '0' }}</div>
            <div class="stat-label">Ortalama Sipariş</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-box-open"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalItems) }}</div>
            <div class="stat-label">Satılan Ürün</div>
        </div>
    </div>
</div>

{{-- Gelir Grafiği --}}
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-chart-line" style="color:var(--primary);margin-right:8px;"></i> Günlük Gelir</span>
    </div>
    <canvas id="salesChart" height="80"></canvas>
</div>

<div class="grid grid-2" style="margin-bottom:24px;">
    {{-- En Çok Satan Ürünler --}}
    <div class="card">
        <div class="card-header"><span class="card-title"><i class="fas fa-medal" style="color:#f59e0b;margin-right:6px;"></i> En Çok Satan Ürünler</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Ürün</th><th>Adet</th><th>Gelir</th></tr></thead>
                <tbody>
                    @foreach($topProducts as $item)
                    <tr>
                        <td style="font-size:13px;font-weight:500;">{{ Str::limit($item->product_name, 35) }}</td>
                        <td><span class="badge badge-primary">{{ $item->qty }}</span></td>
                        <td style="font-weight:700;">₺{{ number_format($item->revenue, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    @if(!count($topProducts))
                        <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:24px;">Veri yok</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Durum Dağılımı --}}
    <div class="card">
        <div class="card-header"><span class="card-title"><i class="fas fa-pie-chart" style="color:var(--primary);margin-right:6px;"></i> Sipariş Durumları</span></div>
        <canvas id="statusChart" height="160"></canvas>
    </div>
</div>

{{-- Siparişler Tablosu --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Son Siparişler</span>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Tümünü Gör</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Sipariş No</th><th>Müşteri</th><th>Tutar</th><th>Durum</th><th>Tarih</th></tr></thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td><strong>{{ $order->order_no }}</strong></td>
                    <td>{{ $order->customer_name }}</td>
                    <td>₺{{ number_format($order->total, 2, ',', '.') }}</td>
                    <td><span class="badge {{ match($order->status) { 'delivered'=>'badge-success','shipped'=>'badge-info','cancelled'=>'badge-danger',default=>'badge-warning' } }}">{{ $order->status_label ?? $order->status }}</span></td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $order->created_at->format('d.m.Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Gelir grafiği
new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Gelir (₺)',
            data: @json($revenues),
            backgroundColor: 'rgba(108,71,255,.25)',
            borderColor: '#6c47ff',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => '₺' + v.toLocaleString('tr-TR') } },
            x: { grid: { display: false } }
        }
    }
});

// Durum dağılımı
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: @json($statusLabels),
        datasets: [{ data: @json($statusData), backgroundColor: ['#f59e0b','#6c47ff','#3b82f6','#10b981','#ef4444'], borderWidth: 2 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush
