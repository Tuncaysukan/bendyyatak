@extends('layouts.app')

@section('seo_title', 'Arama: ' . $query . ' — BendyyYatak')
@section('seo_description', $query . ' için arama sonuçları.')

@section('content')
<div class="container" style="padding-top:32px;padding-bottom:60px;">
    <h1 style="font-size:22px;font-weight:700;margin-bottom:6px;">
        @if($query) "{{ $query }}" için arama sonuçları @else Arama @endif
    </h1>
    @if($query && $products->isNotEmpty())
        <p style="color:#6b7280;font-size:13.5px;margin-bottom:28px;">{{ $products->total() }} ürün bulundu.</p>
    @else
        <p style="color:#6b7280;font-size:13.5px;margin-bottom:28px;">Arama yapmak için yukarıdaki kutuyu kullanın.</p>
    @endif

    <form action="{{ route('search') }}" method="GET" style="display:flex;gap:10px;max-width:500px;margin-bottom:36px;">
        <input type="text" name="q" value="{{ $query }}" placeholder="Ne arıyorsunuz?" style="flex:1;padding:12px 16px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;font-family:'Inter',sans-serif;">
        <button type="submit" class="btn btn-primary"><i class="fas fa-magnifying-glass"></i> Ara</button>
    </form>

    @if($products->isNotEmpty())
        <div class="products-grid">
            @foreach($products as $product)
                @include('shop.partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div style="margin-top:32px;">{{ $products->links() }}</div>
    @elseif($query)
        <div style="text-align:center;padding:60px 20px;color:#6b7280;">
            <i class="fas fa-magnifying-glass" style="font-size:48px;display:block;margin-bottom:16px;color:#d1d5db;"></i>
            <h3 style="font-size:18px;font-weight:600;color:#1a1a2e;margin-bottom:8px;">Sonuç bulunamadı</h3>
            <p>"{{ $query }}" için ürün bulunamadı. Farklı kelimeler deneyin.</p>
        </div>
    @endif
</div>
@endsection
