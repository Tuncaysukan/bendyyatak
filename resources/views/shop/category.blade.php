@extends('layouts.app')

@section('seo_title', $category->seo_title ?? $category->name . ' — BendyyYatak')
@section('seo_description', $category->seo_description ?? $category->description)

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Anasayfa</a>
        <span>/</span>
        @if($category->parent)
            <a href="{{ route('category.show', $category->parent) }}">{{ $category->parent->name }}</a>
            <span>/</span>
        @endif
        <span>{{ $category->name }}</span>
    </div>

    <div style="display:grid;grid-template-columns:240px 1fr;gap:28px;align-items:start;">

        {{-- Sidebar Filtreler --}}
        <aside class="filter-sidebar">
            <div class="filter-card">
                <div class="filter-title">Filtreler</div>

                <form method="GET" action="{{ request()->url() }}" id="filter-form">
                    {{-- Fiyat Aralığı --}}
                    <div class="filter-group">
                        <div class="filter-group-title">Fiyat Aralığı</div>
                        <div style="display:flex;gap:8px;">
                            <input type="number" name="min_price" placeholder="Min ₺" value="{{ request('min_price') }}"
                                   class="filter-input" min="0">
                            <input type="number" name="max_price" placeholder="Max ₺" value="{{ request('max_price') }}"
                                   class="filter-input" min="0">
                        </div>
                    </div>

                    {{-- Sertlik Skalası --}}
                    <div class="filter-group">
                        <div class="filter-group-title">Sertlik Düzeyi</div>
                        @for($i = 1; $i <= 10; $i++)
                        <label class="filter-check">
                            <input type="radio" name="firmness" value="{{ $i }}" {{ request('firmness') == $i ? 'checked' : '' }}>
                            <span class="filter-check-label">
                                @if($i <= 3) Çok Yumuşak
                                @elseif($i <= 5) Yumuşak
                                @elseif($i <= 7) Orta Sert
                                @elseif($i <= 9) Sert
                                @else Çok Sert
                                @endif
                                ({{ $i }})
                            </span>
                        </label>
                        @endfor
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px;">
                        <i class="fas fa-filter"></i> Filtrele
                    </button>
                    @if(request()->hasAny(['min_price','max_price','firmness','sort']))
                        <a href="{{ route('category.show', $category) }}" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:8px;">
                            Temizle
                        </a>
                    @endif
                </form>
            </div>
        </aside>

        {{-- Ürün Grid --}}
        <div>
            {{-- Üst Bar --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
                <div>
                    <h1 style="font-size:22px;font-weight:700;">{{ $category->name }}</h1>
                    <p style="font-size:13px;color:#6b7280;margin-top:2px;">{{ $products->total() }} ürün bulundu</p>
                </div>
                <select name="sort" class="sort-select" onchange="this.form.submit()" form="filter-form">
                    <option value="newest"    {{ request('sort','newest') === 'newest'     ? 'selected' : '' }}>En Yeni</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc'            ? 'selected' : '' }}>Fiyat: Artan</option>
                    <option value="price_desc"{{ request('sort') === 'price_desc'           ? 'selected' : '' }}>Fiyat: Azalan</option>
                    <option value="popular"   {{ request('sort') === 'popular'              ? 'selected' : '' }}>En Popüler</option>
                </select>
            </div>

            {{-- Alt Kategoriler --}}
            @if($subCategories->isNotEmpty())
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:24px;">
                @foreach($subCategories as $sub)
                <a href="{{ route('category.show', $sub) }}"
                   style="padding:6px 16px;border:1.5px solid #e5e7eb;border-radius:99px;font-size:13px;font-weight:500;color:#1a1a2e;transition:all .2s;"
                   onmouseover="this.style.borderColor='#c8a96e';this.style.color='#a8893e';"
                   onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#1a1a2e';">
                    {{ $sub->name }}
                </a>
                @endforeach
            </div>
            @endif

            {{-- Ürünler --}}
            @if($products->isEmpty())
            <div style="text-align:center;padding:80px 20px;color:#6b7280;">
                <i class="fas fa-box-open" style="font-size:48px;display:block;margin-bottom:16px;color:#d1d5db;"></i>
                <h3 style="font-size:18px;font-weight:600;color:#1a1a2e;margin-bottom:8px;">Ürün Bulunamadı</h3>
                <p>Bu kategoride henüz ürün bulunmuyor veya filtrenizi değiştirin.</p>
            </div>
            @else
            <div class="products-grid">
                @foreach($products as $product)
                    @include('shop.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div style="margin-top:32px;">
                {{ $products->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.filter-sidebar { position: sticky; top: 90px; }
.filter-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px; }
.filter-title { font-size: 16px; font-weight: 700; color: #1a1a2e; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #f3f4f6; }
.filter-group { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #f3f4f6; }
.filter-group:last-of-type { border-bottom: none; }
.filter-group-title { font-size: 13px; font-weight: 700; color: #1a1a2e; margin-bottom: 10px; text-transform: uppercase; letter-spacing: .05em; }
.filter-input { width: 100%; padding: 8px 10px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif; }
.filter-check { display: flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 7px; font-size: 13px; color: #4b5563; }
.filter-check input { accent-color: #1a1a2e; }
.sort-select { padding: 9px 36px 9px 14px; border: 1.5px solid #e5e7eb; border-radius: 9px; font-size: 13.5px; font-family: 'Inter', sans-serif; color: #1a1a2e; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M5 6L0 0h10z' fill='%236b7280'/%3E%3C/svg%3E") no-repeat right 12px center; appearance: none; cursor: pointer; }

@media(max-width:768px){
    .filter-sidebar { display: none; }
    [style*="grid-template-columns:240px 1fr"] { display: block !important; }
}
</style>
@endpush
