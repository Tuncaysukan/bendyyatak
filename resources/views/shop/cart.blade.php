@extends('layouts.app')

@section('seo_title', 'Sepetim — BendyyYatak')

@section('content')
<div class="container" style="padding-top:32px;padding-bottom:60px;">
    <h1 style="font-size:22px;font-weight:700;margin-bottom:28px;">Sepetim</h1>

    @if(empty($cart))
    <div style="text-align:center;padding:80px 20px;background:#fff;border:1px solid #e5e7eb;border-radius:16px;">
        <i class="fas fa-shopping-bag" style="font-size:56px;color:#e5e7eb;display:block;margin-bottom:16px;"></i>
        <h3 style="font-size:18px;font-weight:600;color:#1a1a2e;margin-bottom:8px;">Sepetiniz Boş</h3>
        <p style="color:#6b7280;margin-bottom:24px;">Sepetinizde henüz ürün bulunmuyor.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Alışverişe Başla</a>
    </div>
    @else
    <div style="display:grid;grid-template-columns:1fr 340px;gap:28px;align-items:start;">

        {{-- Sepet İtems --}}
        <div>
            @foreach($cart as $key => $item)
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:20px;display:flex;gap:16px;align-items:center;margin-bottom:14px;">
                <img src="{{ $item['image'] ?? asset('images/placeholder.jpg') }}" alt="{{ $item['name'] }}"
                     style="width:90px;height:90px;object-fit:cover;border-radius:10px;background:#f5f5f0;">
                <div style="flex:1;min-width:0;">
                    <a href="{{ route('product.show', $item['slug']) }}" style="font-weight:600;font-size:15px;color:#1a1a2e;text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $item['name'] }}
                    </a>
                    @if($item['variant_name'])
                        <div style="font-size:12.5px;color:#6b7280;margin-top:3px;">{{ $item['variant_name'] }}</div>
                    @endif
                    <div style="font-size:16px;font-weight:700;color:#1a1a2e;margin-top:8px;">₺{{ number_format($item['price'], 2, ',', '.') }}</div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;">
                    <form action="{{ route('cart.update') }}" method="POST" style="display:flex;align-items:center;gap:6px;">
                        @csrf
                        <input type="hidden" name="key" value="{{ $key }}">
                        <div style="display:flex;align-items:center;border:1.5px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                            <button type="submit" name="quantity" value="{{ max(1, $item['quantity']-1) }}" style="width:32px;height:36px;background:#f9f8f5;border:none;cursor:pointer;font-size:16px;">−</button>
                            <span style="width:36px;text-align:center;font-size:14px;font-weight:600;">{{ $item['quantity'] }}</span>
                            <button type="submit" name="quantity" value="{{ $item['quantity']+1 }}" style="width:32px;height:36px;background:#f9f8f5;border:none;cursor:pointer;font-size:16px;">+</button>
                        </div>
                    </form>
                    <div style="font-size:15px;font-weight:700;color:#1a1a2e;">₺{{ number_format($item['total'], 2, ',', '.') }}</div>
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="key" value="{{ $key }}">
                        <button type="submit" style="background:none;border:none;color:#9ca3af;cursor:pointer;font-size:12px;">
                            <i class="fas fa-trash"></i> Kaldır
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Özet --}}
        <div style="position:sticky;top:90px;">
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:24px;">
                <div style="font-size:17px;font-weight:700;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #f3f4f6;">Sipariş Özeti</div>

                {{-- Kupon --}}
                @if($coupon)
                <div style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <span style="font-size:13px;font-weight:600;color:#15803d;"><i class="fas fa-ticket"></i> {{ $coupon['code'] }}</span>
                    <form action="{{ route('cart.coupon.remove') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:#15803d;cursor:pointer;font-size:12px;">Kaldır</button>
                    </form>
                </div>
                @else
                <form action="{{ route('cart.coupon') }}" method="POST" style="display:flex;gap:8px;margin-bottom:14px;">
                    @csrf
                    <input type="text" name="code" placeholder="Kupon kodu" style="flex:1;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:'Inter',sans-serif;">
                    <button type="submit" class="btn btn-outline" style="padding:9px 14px;font-size:13px;">Uygula</button>
                </form>
                @if($errors->has('code'))
                    <p style="color:#b91c1c;font-size:12.5px;margin-top:-8px;margin-bottom:10px;">{{ $errors->first('code') }}</p>
                @endif
                @endif

                {{-- Tutarlar --}}
                @php $subtotal = array_sum(array_column($cart, 'total')); @endphp
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;">
                        <span style="color:#6b7280;">Ara Toplam</span>
                        <span>₺{{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                    @if($shipping > 0)
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;">
                        <span style="color:#6b7280;">Kargo</span>
                        <span>₺{{ number_format($shipping, 2, ',', '.') }}</span>
                    </div>
                    @else
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;">
                        <span style="color:#6b7280;">Kargo</span>
                        <span style="color:#10b981;font-weight:600;">Ücretsiz</span>
                    </div>
                    @endif
                    @if($coupon)
                    <div style="display:flex;justify-content:space-between;font-size:13.5px;color:#15803d;">
                        <span>İndirim</span>
                        <span>−₺{{ number_format($coupon['discount'], 2, ',', '.') }}</span>
                    </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;font-size:17px;font-weight:700;padding-top:12px;border-top:1px solid #f3f4f6;">
                        <span>Toplam</span>
                        <span>₺{{ number_format($subtotal + $shipping - ($coupon['discount'] ?? 0), 2, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn btn-primary" style="width:100%;justify-content:center;font-size:15px;">
                    <i class="fas fa-lock"></i> Güvenli Ödemeye Geç
                </a>
                <a href="{{ route('home') }}" style="display:block;text-align:center;font-size:13px;color:#6b7280;margin-top:12px;">Alışverişe Devam Et</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
