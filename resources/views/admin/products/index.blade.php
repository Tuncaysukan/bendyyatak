@extends('layouts.admin')

@section('title', 'Ürünler')
@section('topbar-title', 'Ürün Yönetimi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Ürünler</div>
        <div class="page-subtitle">Toplam {{ $products->total() }} ürün</div>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Yeni Ürün
    </a>
</div>

{{-- Filtreler --}}
<div class="card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.products.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div class="form-group" style="margin:0;flex:1;min-width:200px;">
            <label class="form-label">Arama</label>
            <input type="text" name="search" class="form-control" placeholder="Ürün adı..." value="{{ request('search') }}">
        </div>
        <div class="form-group" style="margin:0;min-width:180px;">
            <label class="form-label">Kategori</label>
            <select name="category" class="form-control">
                <option value="">Tüm Kategoriler</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin:0;min-width:150px;">
            <label class="form-label">Durum</label>
            <select name="status" class="form-control">
                <option value="">Tümü</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="passive" {{ request('status') === 'passive' ? 'selected' : '' }}>Pasif</option>
            </select>
        </div>
        <div style="display:flex;gap:8px;">
            <button type="submit" class="btn btn-primary">Filtrele</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Temizle</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Görsel</th>
                    <th>Ürün Adı</th>
                    <th>Kategori</th>
                    <th>Fiyat</th>
                    <th>Stok</th>
                    <th>Görüntülenme</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <img src="{{ $product->primaryImageUrl }}" alt="{{ $product->name }}"
                             style="width:48px;height:48px;border-radius:8px;object-fit:cover;">
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ Str::limit($product->name, 40) }}</div>
                        @if($product->sku)
                            <div style="font-size:11.5px;color:var(--text-muted);">SKU: {{ $product->sku }}</div>
                        @endif
                        @if($product->is_featured)
                            <span class="badge badge-primary" style="margin-top:3px;">Öne Çıkan</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);">{{ $product->category?->name }}</td>
                    <td>
                        <div style="font-weight:700;">₺{{ number_format($product->price, 0, ',', '.') }}</div>
                        @if($product->compare_at_price)
                            <div style="font-size:11.5px;color:var(--text-muted);text-decoration:line-through;">₺{{ number_format($product->compare_at_price, 0, ',', '.') }}</div>
                        @endif
                    </td>
                    <td>
                        @php $totalStock = $product->variants->sum('stock'); @endphp
                        @if($product->variants->isEmpty())
                            <span class="badge badge-secondary">Varyant yok</span>
                        @elseif($totalStock <= 0)
                            <span class="badge badge-danger">Tükendi</span>
                        @elseif($totalStock <= 5)
                            <span class="badge badge-warning">{{ $totalStock }}</span>
                        @else
                            <span class="badge badge-success">{{ $totalStock }}</span>
                        @endif
                    </td>
                    <td>
                        <span style="display:flex;align-items:center;gap:5px;font-size:13px;">
                            <i class="fas fa-eye" style="color:var(--text-muted);font-size:11px;"></i>
                            {{ number_format($product->view_count) }}
                        </span>
                    </td>
                    <td>
                        @if($product->trashed())
                            <span class="badge badge-secondary">Silinmiş</span>
                        @elseif($product->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Pasif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('product.show', $product) }}" target="_blank" class="btn btn-outline btn-sm" title="Önizle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-pen"></i>
                            </a>
                            @if(!$product->trashed())
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Ürünü silmek istediğinize emin misiniz?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">
                        <i class="fas fa-box-open" style="font-size:32px;display:block;margin-bottom:12px;"></i>
                        Henüz ürün eklenmemiş.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 0 0 10px;">
        {{ $products->links() }}
    </div>
</div>
@endsection
