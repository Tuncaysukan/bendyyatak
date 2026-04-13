@extends('layouts.app')

@section('seo_title', ($product->seo_title ?? $product->name) . ' — BendyyYatak')
@section('seo_description', $product->seo_description ?? $product->short_description)
@section('og_image', $product->primaryImageUrl)

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ $product->short_description }}",
    "image": "{{ $product->primaryImageUrl }}",
    "offers": {
        "@@type": "Offer",
        "price": "{{ $product->price }}",
        "priceCurrency": "TRY",
        "availability": "https://schema.org/InStock"
    }
}
</script>
@endpush

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Anasayfa</a>
        <span>/</span>
        @if($product->category)
            <a href="{{ route('category.show', $product->category) }}">{{ $product->category->name }}</a>
            <span>/</span>
        @endif
        <span>{{ Str::limit($product->name, 40) }}</span>
    </div>

    {{-- Ana Ürün Bölümü --}}
    <div class="product-detail-grid">
        {{-- Görsel Galerisi --}}
        <div class="product-gallery">
            <div class="gallery-main">
            <img src="{{ $product->primaryImageUrl }}"
                 alt="{{ $product->name }}"
                 id="mainImg"
                 onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}'">
                @if($product->compare_at_price && $product->discount_percentage)
                    <span class="gallery-badge">%{{ $product->discount_percentage }} İNDİRİM</span>
                @endif
            </div>
            @if($product->images->count() > 1)
            <div class="gallery-thumbs">
                @foreach($product->images as $img)
                <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $product->name }}"
                     class="gallery-thumb {{ $img->is_primary ? 'active' : '' }}"
                     onclick="setMainImg(this, '{{ asset('storage/' . $img->image) }}')">
                @endforeach
            </div>
            @endif
        </div>

        {{-- Ürün Bilgileri --}}
        <div class="product-info">
            @if($product->category)
                <div class="product-info-cat">{{ $product->category->name }}</div>
            @endif
            <h1 class="product-info-name">{{ $product->name }}</h1>

            {{-- Rating --}}
            @if($product->reviews->count() > 0)
            <div class="product-rating">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star" style="color:{{ $i <= round($avgRating) ? '#f59e0b' : '#e5e7eb' }};font-size:14px;"></i>
                @endfor
                <span style="font-size:13px;color:#6b7280;margin-left:6px;">{{ number_format($avgRating, 1) }} ({{ $product->reviews->count() }} yorum)</span>
            </div>
            @endif

            {{-- Fiyat --}}
            <div class="product-price-box">
                <span class="product-price-main">₺{{ number_format($product->price, 2, ',', '.') }}</span>
                @if($product->compare_at_price)
                    <span class="product-price-old">₺{{ number_format($product->compare_at_price, 2, ',', '.') }}</span>
                    <span class="product-price-badge">%{{ $product->discount_percentage }} İndirim</span>
                @endif
            </div>

            {{-- Kısa Açıklama --}}
            @if($product->short_description)
            <p class="product-short-desc">{{ $product->short_description }}</p>
            @endif

            {{-- Sertlik Skalası --}}
            @if($product->firmness_level)
            <div class="firmness-box">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <span style="font-size:13px;font-weight:600;color:#1a1a2e;">Sertlik Düzeyi</span>
                    <span style="font-size:13px;font-weight:700;color:#a8893e;">{{ $product->firmness_label }}</span>
                </div>
                <div class="firmness-track">
                    <div class="firmness-fill" style="width:{{ $product->firmness_level * 10 }}%;"></div>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:11px;color:#9ca3af;margin-top:4px;">
                    <span>Çok Yumuşak</span>
                    <span>{{ $product->firmness_level }}/10</span>
                    <span>Çok Sert</span>
                </div>
            </div>
            @endif

            {{-- Varyant Seçimi --}}
            @if($product->variants->isNotEmpty())
            <div class="variant-section">
                <div style="font-size:13px;font-weight:600;margin-bottom:10px;">Boyut / Model Seçin</div>
                <div class="variant-grid">
                    @foreach($product->variants as $variant)
                    <label class="variant-btn {{ $variant->stock <= 0 ? 'out-of-stock' : '' }}">
                        <input type="radio" name="variant_id" value="{{ $variant->id }}"
                               {{ $loop->first ? 'checked' : '' }} {{ $variant->stock <= 0 ? 'disabled' : '' }}
                               onchange="updateVariantPrice({{ $variant->extra_price }})">
                        <span class="variant-label">
                            {{ $variant->name }}
                            @if($variant->extra_price > 0)
                                <small>+₺{{ number_format($variant->extra_price, 0, ',', '.') }}</small>
                            @endif
                        </span>
                        @if($variant->stock <= 0)
                            <span style="font-size:10px;color:#ef4444;display:block;">Tükendi</span>
                        @endif
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Miktar + Sepet --}}
            <div class="cart-actions">
                <div class="qty-box">
                    <button type="button" onclick="changeQty(-1)" class="qty-btn">−</button>
                    <input type="number" id="qty" value="1" min="1" max="10" class="qty-input">
                    <button type="button" onclick="changeQty(1)" class="qty-btn">+</button>
                </div>
                <button class="btn btn-primary btn-lg add-cart-btn" style="flex:1;"
                        onclick="addProductToCart()">
                    <i class="fas fa-shopping-bag"></i> Sepete Ekle
                </button>
            </div>

            <div style="display:flex;gap:10px;margin-top:12px;">
                <button class="btn btn-outline" style="flex:1;justify-content:center;" onclick="toggleWishlist({{ $product->id }}, this)">
                    <i class="far fa-heart"></i> Favorilere Ekle
                </button>
                @if($product->is_comparable)
                <button class="btn btn-outline" onclick="toggleCompare({{ $product->id }}, this)">
                    <i class="fas fa-scale-balanced"></i>
                </button>
                @endif
            </div>

            {{-- WhatsApp --}}
            @php $wa = \App\Models\Setting::get('whatsapp_number', ''); @endphp
            @if($wa)
            <a href="https://wa.me/{{ preg_replace('/\D/','',$wa) }}?text={{ urlencode('Merhaba! ' . $product->name . ' ürünü hakkında bilgi almak istiyorum. ' . url()->current()) }}"
               target="_blank"
               class="btn"
               style="background:#25D366;color:#fff;width:100%;justify-content:center;margin-top:10px;"
               onclick="trackWhatsapp({{ $product->id }})">
                <i class="fab fa-whatsapp"></i> WhatsApp ile Bilgi Al
            </a>
            @endif

            {{-- Özellikler Özet --}}
            <div class="product-trust">
                <div class="product-trust-item"><i class="fas fa-rotate-left"></i><span>120 Gün Deneme</span></div>
                <div class="product-trust-item"><i class="fas fa-shield-halved"></i><span>5 Yıl Garanti</span></div>
                <div class="product-trust-item"><i class="fas fa-truck-fast"></i><span>Ücretsiz Kargo</span></div>
            </div>
        </div>
    </div>

    {{-- Taksit Tablosu --}}
    @if($installmentPlans->isNotEmpty())
    <div class="section-card" style="margin-top:40px;">
        <h2 style="font-size:18px;font-weight:700;margin-bottom:20px;"><i class="fas fa-credit-card" style="color:#c8a96e;margin-right:8px;"></i> Taksit Seçenekleri</h2>
        <div style="overflow-x:auto;">
            <table class="installment-table">
                <thead>
                    <tr>
                        <th>Banka</th>
                        <th>2 Taksit</th>
                        <th>3 Taksit</th>
                        <th>6 Taksit</th>
                        <th>9 Taksit</th>
                        <th>12 Taksit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($installmentPlans as $bank => $plans)
                    <tr>
                        <td style="font-weight:600;">{{ $bank }}</td>
                        @foreach([2,3,6,9,12] as $count)
                            @php $plan = $plans->where('installment_count', $count)->first(); @endphp
                            <td>
                                @if($plan)
                                    @if($plan->interest_rate == 0)
                                        <strong style="color:#10b981;">Faizsiz</strong><br>
                                    @else
                                        <small style="color:#6b7280;">%{{ $plan->interest_rate }}</small><br>
                                    @endif
                                    <span style="font-size:12.5px;" id="inst-{{ $bank }}-{{ $count }}">
                                        ₺{{ number_format($plan->calculateMonthlyPayment($product->price), 2, ',', '.') }}
                                    </span>
                                @else
                                    <span style="color:#d1d5db;">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Tabs: Açıklama / Özellikler / Yorumlar --}}
    <div class="section-card" style="margin-top:40px;">
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('desc', this)">Açıklama</button>
            @if($product->attributes->isNotEmpty())
            <button class="tab-btn" onclick="switchTab('attrs', this)">Teknik Özellikler</button>
            @endif
            <button class="tab-btn" onclick="switchTab('reviews', this)">Yorumlar ({{ $product->reviews->count() }})</button>
        </div>

        <div id="tab-desc" class="tab-content" style="display:block;">
            <div class="product-desc-content">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>

        @if($product->attributes->isNotEmpty())
        <div id="tab-attrs" class="tab-content" style="display:none;">
            <table class="attr-table">
                @foreach($product->attributes->sortBy('sort_order') as $attr)
                <tr>
                    <td class="attr-key">{{ $attr->key }}</td>
                    <td class="attr-val">{{ $attr->value }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        <div id="tab-reviews" class="tab-content" style="display:none;">
            @auth
            <div class="review-form">
                <h3 style="font-size:15px;font-weight:700;margin-bottom:14px;">Yorum Yaz</h3>
                <form action="{{ route('account.review.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div style="margin-bottom:12px;">
                        <div style="font-size:13px;font-weight:600;margin-bottom:6px;">Puanınız</div>
                        <div class="star-rating" id="starRating">
                            @for($i=1;$i<=5;$i++)
                            <i class="fas fa-star star-btn" data-val="{{ $i }}" onclick="setRating({{ $i }})" style="font-size:24px;cursor:pointer;color:#e5e7eb;"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingVal" value="0">
                    </div>
                    <textarea name="comment" placeholder="Ürün hakkında görüşlerinizi paylaşın..." style="width:100%;padding:12px;border:1.5px solid #e5e7eb;border-radius:8px;font-family:'Inter',sans-serif;font-size:13.5px;resize:vertical;min-height:100px;"></textarea>
                    <button type="submit" class="btn btn-primary" style="margin-top:10px;">Yorum Gönder</button>
                </form>
            </div>
            @else
            <div style="background:#f9f8f5;border-radius:10px;padding:20px;text-align:center;color:#6b7280;margin-bottom:20px;">
                Yorum yapmak için <a href="{{ route('login') }}" style="color:#1a1a2e;font-weight:600;">giriş yapın</a>
            </div>
            @endauth

            @forelse($product->reviews->where('is_approved', true) as $review)
            <div class="review-item">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                    <div style="width:36px;height:36px;background:#1a1a2e;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;">
                        {{ strtoupper(substr($review->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:13.5px;">{{ $review->name }}</div>
                        <div>
                            @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="color:{{ $i <= $review->rating ? '#f59e0b' : '#e5e7eb' }};font-size:12px;"></i>@endfor
                        </div>
                    </div>
                    <div style="margin-left:auto;font-size:12px;color:#9ca3af;">{{ $review->created_at->format('d.m.Y') }}</div>
                </div>
                @if($review->comment)
                <p style="font-size:13.5px;color:#4b5563;line-height:1.6;">{{ $review->comment }}</p>
                @endif
            </div>
            @empty
            <div style="text-align:center;padding:32px;color:#9ca3af;">Henüz yorum yapılmamış. İlk yorumu siz yazın!</div>
            @endforelse
        </div>
    </div>

    {{-- İlgili Ürünler --}}
    @if($relatedProducts->isNotEmpty())
    <div style="margin-top:50px;">
        <h2 class="section-title" style="font-size:22px;margin-bottom:24px;">Benzer Ürünler</h2>
        <div class="products-grid">
            @foreach($relatedProducts as $related)
                @include('shop.partials.product-card', ['product' => $related])
            @endforeach
        </div>
    </div>
    @endif

    {{-- Stok Uyarısı Modal --}}
    @if($product->variants->every(fn($v) => $v->stock <= 0))
    <div id="stockAlertModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:16px;padding:32px;max-width:400px;width:90%;text-align:center;">
            <i class="fas fa-bell" style="font-size:40px;color:#c8a96e;margin-bottom:16px;"></i>
            <h3 style="margin-bottom:8px;">Stok Gelince Haber Ver</h3>
            <p style="color:#6b7280;font-size:13.5px;margin-bottom:20px;">E-postanızı girin, ürün stoğa girdiğinde size haber verelim.</p>
            <form id="stockAlertForm">
                <input type="email" id="alertEmail" placeholder="E-posta adresiniz" style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:9px;font-family:'Inter',sans-serif;margin-bottom:10px;">
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Haber Ver</button>
            </form>
            <button onclick="document.getElementById('stockAlertModal').style.display='none'" style="margin-top:10px;color:#6b7280;font-size:13px;background:none;border:none;cursor:pointer;">Kapat</button>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.product-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: start; padding: 24px 0 40px; }
.gallery-main { border-radius: 16px; overflow: hidden; background: #f5f5f0; aspect-ratio: 1; position: relative; }
.gallery-main img { width: 100%; height: 100%; object-fit: cover; }
.gallery-badge { position: absolute; top: 16px; left: 16px; background: #ef4444; color: #fff; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 99px; }
.gallery-thumbs { display: flex; gap: 8px; margin-top: 12px; flex-wrap: wrap; }
.gallery-thumb { width: 72px; height: 72px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid transparent; transition: border-color .2s; }
.gallery-thumb.active, .gallery-thumb:hover { border-color: #c8a96e; }
.product-info-cat { font-size: 12px; color: #a8893e; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px; }
.product-info-name { font-size: 26px; font-weight: 800; color: #1a1a2e; line-height: 1.25; margin-bottom: 12px; }
.product-rating { display: flex; align-items: center; margin-bottom: 16px; }
.product-price-box { display: flex; align-items: baseline; gap: 12px; margin-bottom: 16px; padding: 16px 0; border-top: 1px solid #f3f4f6; border-bottom: 1px solid #f3f4f6; }
.product-price-main { font-size: 32px; font-weight: 800; color: #1a1a2e; }
.product-price-old { font-size: 18px; color: #9ca3af; text-decoration: line-through; }
.product-price-badge { background: #fee2e2; color: #b91c1c; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 99px; }
.product-short-desc { font-size: 14px; color: #4b5563; line-height: 1.7; margin-bottom: 16px; }
.firmness-box { background: #fafaf8; border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px 16px; margin-bottom: 16px; }
.firmness-track { height: 8px; background: #f3f4f6; border-radius: 99px; overflow: hidden; }
.firmness-fill { height: 100%; background: linear-gradient(90deg, #c8a96e, #a8893e); border-radius: 99px; transition: width .4s ease; }
.variant-section { margin-bottom: 16px; }
.variant-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.variant-btn { cursor: pointer; }
.variant-btn input { display: none; }
.variant-label { display: block; padding: 8px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 13px; font-weight: 500; transition: all .2s; }
.variant-btn input:checked + .variant-label { border-color: #1a1a2e; background: #1a1a2e; color: #fff; }
.variant-btn.out-of-stock .variant-label { opacity: .4; text-decoration: line-through; cursor: not-allowed; }
.cart-actions { display: flex; gap: 12px; margin-bottom: 12px; }
.qty-box { display: flex; align-items: center; border: 1.5px solid #e5e7eb; border-radius: 9px; overflow: hidden; }
.qty-btn { width: 36px; height: 48px; background: #f9f8f5; border: none; cursor: pointer; font-size: 18px; color: #1a1a2e; transition: background .2s; }
.qty-btn:hover { background: #f3f4f6; }
.qty-input { width: 48px; text-align: center; border: none; font-size: 15px; font-weight: 600; font-family: 'Inter', sans-serif; -moz-appearance: textfield; }
.qty-input::-webkit-outer-spin-button, .qty-input::-webkit-inner-spin-button { display: none; }
.product-trust { display: flex; gap: 12px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #f3f4f6; }
.product-trust-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #6b7280; }
.product-trust-item i { color: #c8a96e; }
.section-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 28px; }
.installment-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.installment-table th { background: #1a1a2e; color: #fff; padding: 10px 14px; text-align: center; font-size: 12px; }
.installment-table td { padding: 10px 14px; text-align: center; border-bottom: 1px solid #f3f4f6; }
.installment-table tr:hover td { background: #fafaf8; }
.tabs { display: flex; gap: 4px; border-bottom: 1px solid #e5e7eb; margin-bottom: 24px; }
.tab-btn { padding: 10px 20px; border: none; background: none; cursor: pointer; font-size: 14px; font-weight: 500; font-family: 'Inter', sans-serif; color: #6b7280; border-bottom: 2px solid transparent; transition: all .2s; }
.tab-btn.active { color: #1a1a2e; border-bottom-color: #1a1a2e; font-weight: 700; }
.tab-content { animation: fadeIn .2s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
.product-desc-content { font-size: 14.5px; color: #4b5563; line-height: 1.8; }
.attr-table { width: 100%; border-collapse: collapse; }
.attr-table tr:nth-child(even) { background: #fafaf8; }
.attr-key { padding: 10px 14px; font-weight: 600; font-size: 13.5px; color: #1a1a2e; width: 40%; border-bottom: 1px solid #f3f4f6; }
.attr-val { padding: 10px 14px; font-size: 13.5px; color: #4b5563; border-bottom: 1px solid #f3f4f6; }
.review-form { background: #fafaf8; border-radius: 10px; padding: 20px; margin-bottom: 24px; }
.review-item { padding: 16px 0; border-bottom: 1px solid #f3f4f6; }
.review-item:last-child { border-bottom: none; }
@media(max-width: 768px) {
    .product-detail-grid { grid-template-columns: 1fr; gap: 24px; }
    .product-info-name { font-size: 20px; }
    .product-price-main { font-size: 26px; }
}
</style>
@endpush

@push('scripts')
<script>
function setMainImg(thumb, src) {
    document.getElementById('mainImg').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
function changeQty(delta) {
    const inp = document.getElementById('qty');
    inp.value = Math.max(1, Math.min(10, parseInt(inp.value) + delta));
}
function updateVariantPrice(extra) {
    const base = {{ $product->price }};
    document.querySelector('.product-price-main').textContent = '₺' + (base + extra).toLocaleString('tr-TR', {minimumFractionDigits: 2});
}
function addProductToCart() {
    const variantEl = document.querySelector('input[name="variant_id"]:checked');
    const qty = parseInt(document.getElementById('qty').value);
    const btn = document.querySelector('.add-cart-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ekleniyor...';
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ product_id: {{ $product->id }}, variant_id: variantEl ? variantEl.value : null, quantity: qty })
    }).then(r => r.json()).then(data => {
        if (data.success) { updateCartCount(data.count); showCartToast(data.message); }
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-shopping-bag"></i> Sepete Ekle';
    });
}
function setRating(val) {
    document.getElementById('ratingVal').value = val;
    document.querySelectorAll('.star-btn').forEach((star, i) => {
        star.style.color = i < val ? '#f59e0b' : '#e5e7eb';
    });
}
function switchTab(id, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).style.display = 'block';
    btn.classList.add('active');
}
</script>
@endpush
