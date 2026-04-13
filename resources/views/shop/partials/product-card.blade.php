<div class="product-card">
    <!-- Badges -->
    <div class="product-card-img">
        <img src="{{ $product->primaryImageUrl }}"
             alt="{{ $product->name }}"
             loading="lazy"
             onerror="this.onerror=null;this.src='{{ asset('images/product-placeholder.svg') }}'">
        <div class="product-card-badges">
            @if($product->discount_percentage)
                <span class="badge-pill badge-discount">%{{ $product->discount_percentage }} İndirim</span>
            @endif
            @if($product->is_featured)
                <span class="badge-pill badge-featured">Öne Çıkan</span>
            @endif
            @if($product->created_at->isAfter(now()->subDays(30)))
                <span class="badge-pill badge-new">Yeni</span>
            @endif
        </div>
        <div class="product-card-actions">
            <button class="card-action-btn" title="Favorilere Ekle" onclick="toggleWishlist({{ $product->id }}, this)">
                <i class="far fa-heart"></i>
            </button>
            @if($product->is_comparable)
            <button class="card-action-btn" title="Karşılaştırmaya Ekle" onclick="toggleCompare({{ $product->id }}, this)">
                <i class="fas fa-scale-balanced"></i>
            </button>
            @endif
        </div>
    </div>

    <div class="product-card-body">
        <div class="product-card-cat">{{ $product->category?->name }}</div>
        <h3 class="product-card-name">
            <a href="{{ route('product.show', $product) }}" style="color:inherit;text-decoration:none;">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Sertlik Skalası -->
        @if($product->firmness_level)
        <div style="margin-bottom:10px;">
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:10.5px;color:#9ca3af;margin-bottom:4px;">
                <span>Yumuşak</span>
                <span style="font-weight:600;color:#6b7280;">{{ $product->firmness_label }}</span>
                <span>Sert</span>
            </div>
            <div style="height:4px;background:#f3f4f6;border-radius:99px;overflow:hidden;">
                <div style="height:100%;width:{{ $product->firmness_level * 10 }}%;background:linear-gradient(90deg,#c8a96e,#a8893e);border-radius:99px;transition:width .3s;"></div>
            </div>
        </div>
        @endif

        <div class="product-card-price">
            <span class="price-main">₺{{ number_format($product->price, 0, ',', '.') }}</span>
            @if($product->compare_at_price)
                <span class="price-old">₺{{ number_format($product->compare_at_price, 0, ',', '.') }}</span>
            @endif
        </div>

        @if($product->variants->isNotEmpty() && isset($product->variants))
            <div style="font-size:11px;color:#9ca3af;margin-top:5px;">{{ $product->variants->count() }} beden seçeneği</div>
        @endif

        <button class="product-card-cart" onclick="addToCart({{ $product->id }}, null, this)">
            <i class="fas fa-shopping-bag"></i> Sepete Ekle
        </button>
    </div>
</div>
