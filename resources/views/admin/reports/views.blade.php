@extends('layouts.admin')
@section('title', 'Görüntülenme Raporu')
@section('topbar-title', 'Ürün Görüntülenme Raporu')

@section('content')
<div class="page-header">
    <div class="page-title">Görüntülenme Raporu</div>
    <div class="page-subtitle">En çok ilgi gören ürünleri takip edin</div>
</div>

{{-- KPI --}}
<div class="grid grid-4" style="margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-eye"></i></div>
        <div>
            <div class="stat-value">{{ number_format($totalViews) }}</div>
            <div class="stat-label">Toplam Görüntülenme</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-box"></i></div>
        <div>
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-label">Aktif Ürün</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-chart-simple"></i></div>
        <div>
            <div class="stat-value">{{ $totalProducts > 0 ? number_format($totalViews / $totalProducts, 0) : 0 }}</div>
            <div class="stat-label">Ort. Görüntülenme</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-fire"></i></div>
        <div>
            <div class="stat-value">{{ $topProduct?->name ? Str::limit($topProduct->name, 14) : '—' }}</div>
            <div class="stat-label">En Popüler Ürün</div>
        </div>
    </div>
</div>

<div class="grid grid-2" style="margin-bottom:24px;">
    {{-- Bar Grafik --}}
    <div class="card" style="grid-column:span 2;">
        <div class="card-header"><span class="card-title"><i class="fas fa-chart-bar" style="color:var(--primary);margin-right:6px;"></i> Top 10 Ürün Görüntülenme</span></div>
        <canvas id="viewsChart" height="90"></canvas>
    </div>
</div>

{{-- Tablo --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Tüm Ürünler — Görüntülenme Sıralaması</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Ürün</th><th>Kategori</th><th>Fiyat</th><th>Görüntülenme</th><th>Son Güncelleme</th><th></th></tr></thead>
            <tbody>
                @foreach($products as $i => $product)
                <tr>
                    <td style="color:var(--text-muted);font-weight:600;">{{ $i + 1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <img src="{{ $product->primaryImageUrl }}" alt="{{ $product->name }}"
                                 style="width:40px;height:40px;border-radius:8px;object-fit:cover;">
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ Str::limit($product->name, 40) }}</div>
                                <div style="font-size:11.5px;color:var(--text-muted);">{{ $product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--text-muted);">{{ $product->category?->name }}</td>
                    <td style="font-weight:700;">₺{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="flex:1;max-width:120px;height:6px;background:#f3f4f6;border-radius:99px;overflow:hidden;">
                                <div style="height:100%;background:var(--primary);border-radius:99px;width:{{ $products->max('view_count') > 0 ? min(100, ($product->view_count / $products->max('view_count') * 100)) : 0 }}%;"></div>
                            </div>
                            <span class="badge badge-primary">{{ number_format($product->view_count) }}</span>
                        </div>
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $product->updated_at->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const top10 = @json($products->take(10));
new Chart(document.getElementById('viewsChart'), {
    type: 'bar',
    data: {
        labels: top10.map(p => p.name.substring(0, 25)),
        datasets: [{
            label: 'Görüntülenme',
            data: top10.map(p => p.view_count),
            backgroundColor: top10.map((_, i) => `hsl(${250 + i * 8}, 70%, ${55 + i * 2}%)`),
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.04)' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush
