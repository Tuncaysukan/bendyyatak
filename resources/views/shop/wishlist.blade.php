@extends('layouts.app')

@section('seo_title', 'Favorilerim — BendyyYatak')

@section('content')
<div class="container" style="padding-top:32px;padding-bottom:60px;">
    <h1 style="font-size:22px;font-weight:700;margin-bottom:28px;"><i class="far fa-heart" style="color:#ef4444;margin-right:8px;"></i> Favorilerim</h1>

    @if($products->isEmpty())
    <div style="text-align:center;padding:80px 20px;background:#fff;border:1px solid #e5e7eb;border-radius:16px;">
        <i class="far fa-heart" style="font-size:56px;color:#e5e7eb;display:block;margin-bottom:16px;"></i>
        <h3 style="font-size:18px;font-weight:600;color:#1a1a2e;margin-bottom:8px;">Listeniz Boş</h3>
        <p style="color:#6b7280;margin-bottom:24px;">Beğendiğiniz ürünleri favorilere ekleyerek burada görüntüleyebilirsiniz.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Alışverişe Başla</a>
    </div>
    @else
    <div class="products-grid">
        @foreach($products as $product)
            @include('shop.partials.product-card', ['product' => $product])
        @endforeach
    </div>
    @endif
</div>
@endsection
