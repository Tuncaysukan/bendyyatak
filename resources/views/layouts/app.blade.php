<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('seo_title', \App\Models\Setting::get('site_name', 'BendyyYatak'))</title>
    <meta name="description" content="@yield('seo_description', \App\Models\Setting::get('site_description', ''))">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('seo_title', \App\Models\Setting::get('site_name', 'BendyyYatak'))">
    <meta property="og:description" content="@yield('seo_description', '')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    @stack('schema')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a1a2e',
                        accent: '#1a1a2e',
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @stack('styles')
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="container">
        <span><i class="fas fa-truck fa-xs"></i> ₺{{ number_format((float) \App\Models\Setting::get('free_shipping_limit', 2000), 0, ',', '.') }} üzeri ücretsiz kargo</span>
        <span class="tb-hide-mobile" style="margin: 0 20px;">|</span>
        <span class="tb-hide-mobile"><i class="fas fa-phone fa-xs"></i> {{ \App\Models\Setting::get('site_phone', '') }}</span>
        <span class="tb-hide-mobile" style="margin: 0 20px;">|</span>
        <span class="tb-hide-mobile"><i class="fas fa-rotate-left fa-xs"></i> Kolay İade Avantajı</span>
    </div>
</div>

<!-- Header -->
<header class="header">
    <div class="header-inner">
        <a href="{{ route('home') }}" class="logo">
            @if(\App\Models\Setting::get('site_logo'))
                <img src="{{ Storage::url(\App\Models\Setting::get('site_logo')) }}" alt="{{ \App\Models\Setting::get('site_name', 'BendyyYatak') }}" style="max-height: 70px; max-width: 160px; object-fit: contain;">
            @else
                Bendyy<span>Yatak</span>
            @endif
        </a>

        <nav class="main-nav">
            @php
                try {
                    $navCategories = \App\Models\Category::whereNull('parent_id')->where('is_active', true)->with('children')->orderBy('sort_order')->get();
                } catch (\Exception $e) {
                    $navCategories = collect();
                }
            @endphp
            @foreach($navCategories as $cat)
                @if($cat->children->isNotEmpty())
                    <div class="dropdown">
                        <a href="{{ route('category.show', $cat) }}">{{ $cat->name }} <i class="fas fa-chevron-down" style="font-size:9px;"></i></a>
                        <div class="dropdown-menu">
                            @foreach($cat->children as $sub)
                                <a href="{{ route('category.show', $sub) }}">{{ $sub->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('category.show', $cat) }}">{{ $cat->name }}</a>
                @endif
            @endforeach
            <a href="{{ route('blog.index') }}">Blog</a>
        </nav>

        <form action="{{ route('search') }}" method="GET" class="header-search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" name="q" placeholder="Ürün ara..." value="{{ request('q') }}">
        </form>

        <div class="header-icons">
            <a href="{{ route('wishlist.index') }}" class="header-icon-btn" title="Favorilerim">
                <i class="far fa-heart"></i>
                <span class="badge" id="wishlist-count" style="{{ count(session('wishlist', [])) > 0 ? '' : 'display:none;' }}">{{ count(session('wishlist', [])) }}</span>
            </a>
            @auth
                <a href="{{ route('account.index') }}" class="header-icon-btn" title="Hesabım">
                    <i class="far fa-user"></i>
                </a>
            @else
                <a href="{{ route('login') }}" class="header-icon-btn" title="Giriş Yap">
                    <i class="far fa-user"></i>
                </a>
            @endauth
            <a href="{{ route('cart.index') }}" class="header-icon-btn" title="Sepetim">
                <i class="fas fa-shopping-bag"></i>
                @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
                @if($cartCount > 0)
                    <span class="badge" id="cart-count">{{ $cartCount }}</span>
                @else
                    <span class="badge" id="cart-count" style="display:none;">0</span>
                @endif
            </a>
        </div>
    </div>
</header>

<!-- Page Content -->
@if(session('success'))
<div class="container" style="padding-top:16px;">
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
</div>
@endif

@yield('content')

<!-- Trust Bar -->
<div class="trust-bar">
    <div class="container">
        <div class="trust-items">
            <div class="trust-item">
                <i class="fas fa-truck-fast"></i>
                <div class="trust-item-text">
                    <strong>Ücretsiz Kargo</strong>
                    <span>₺{{ number_format((float) \App\Models\Setting::get('free_shipping_limit', 2000), 0, ',', '.') }} ve üzeri</span>
                </div>
            </div>
            <div class="trust-item">
                <i class="fas fa-award"></i>
                <div class="trust-item-text">
                    <strong>10 Yıl Garanti</strong>
                    <span>Uzun ömürlü kullanım</span>
                </div>
            </div>
            <div class="trust-item">
                <i class="fas fa-shield-halved"></i>
                <div class="trust-item-text">
                    <strong>Güvenli Ödeme</strong>
                    <span>SSL korumalı alışveriş</span>
                </div>
            </div>
            <div class="trust-item">
                <i class="fas fa-headset"></i>
                <div class="trust-item-text">
                    <strong>7/24 Destek</strong>
                    <span>Her zaman yanınızdayız</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <h2>Bendyy<span>Yatak</span></h2>
                <p>{{ \App\Models\Setting::get('site_description', 'Türkiye\'nin en kaliteli yatak markası.') }}</p>
                <p style="margin-top:12px;font-size:12.5px;">{{ \App\Models\Setting::get('site_address', '') }}</p>
                <p style="margin-top:4px;font-size:12.5px;">{{ \App\Models\Setting::get('site_phone', '') }}</p>
                <div class="footer-social">
                    @if(\App\Models\Setting::get('social_instagram'))
                        <a href="{{ \App\Models\Setting::get('social_instagram') }}" target="_blank"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('social_facebook'))
                        <a href="{{ \App\Models\Setting::get('social_facebook') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('social_youtube'))
                        <a href="{{ \App\Models\Setting::get('social_youtube') }}" target="_blank"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('social_tiktok'))
                        <a href="{{ \App\Models\Setting::get('social_tiktok') }}" target="_blank"><i class="fab fa-tiktok"></i></a>
                    @endif
                </div>
            </div>
            <div class="footer-col">
                <h3>Kategoriler</h3>
                @foreach($navCategories->take(6) as $cat)
                    <a href="{{ route('category.show', $cat) }}">{{ $cat->name }}</a>
                @endforeach
            </div>
            <div class="footer-col">
                <h3>Kurumsal</h3>
                <a href="{{ route('page.about') }}">Hakkımızda</a>
                <a href="{{ route('blog.index') }}">Blog</a>
                <a href="{{ route('page.contact') }}">İletişim</a>
                <a href="{{ route('page.shipping') }}">Kargo & İade</a>
                <a href="{{ route('page.privacy') }}">Gizlilik Politikası</a>
                <a href="{{ route('page.terms') }}">Kullanım Koşulları</a>
            </div>
            <div class="footer-col">
                <h3>Hesabım</h3>
                @auth
                    <a href="{{ route('account.index') }}">Profilim</a>
                    <a href="{{ route('account.orders') }}">Siparişlerim</a>
                    <a href="{{ route('account.addresses') }}">Adreslerim</a>
                @else
                    <a href="{{ route('login') }}">Giriş Yap</a>
                    <a href="{{ route('register') }}">Kayıt Ol</a>
                @endauth
                <a href="{{ route('order.track') }}">Sipariş Takip</a>
                <a href="{{ route('wishlist.index') }}">Favorilerim</a>
                <a href="{{ route('cart.index') }}">Sepetim</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>{{ \App\Models\Setting::get('copyright_text', '© ' . date('Y') . ' BendyyYatak. Tüm hakları saklıdır.') }}</p>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
@php $wa = \App\Models\Setting::get('whatsapp_number', ''); @endphp
@if($wa)
<a href="https://wa.me/{{ preg_replace('/\D/','',$wa) }}" target="_blank" class="whatsapp-btn" title="WhatsApp Destek"
   onclick="trackWhatsapp(null)">
    <i class="fab fa-whatsapp"></i>
</a>
@endif

<!-- Cart Toast -->
<div class="cart-toast" id="cart-toast">
    <i class="fas fa-shopping-bag"></i>
    <span id="cart-toast-msg">Ürün sepete eklendi!</span>
</div>

<!-- Compare Bar -->
<div class="compare-bar" id="compare-bar">
    <span><i class="fas fa-scale-balanced"></i> <strong id="compare-count">0</strong> ürün karşılaştırmada</span>
    <div style="display:flex;gap:12px;">
        <a href="{{ route('product.compare') }}" class="btn btn-accent btn-sm" style="padding:8px 16px;">Karşılaştır</a>
        <button onclick="clearCompare()" style="background:none;border:none;color:rgba(255,255,255,.6);cursor:pointer;font-size:12px;">Temizle</button>
    </div>
</div>

<!-- Mobile Bottom Navbar -->
<div class="mobile-bottom-nav">
    <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Anasayfa</span>
    </a>
    <a href="javascript:void(0)" class="nav-item" onclick="document.getElementById('mobile-menu').classList.add('show')">
        <i class="fas fa-th-large"></i>
        <span>Kategoriler</span>
    </a>
    <a href="{{ route('search') }}" class="nav-item {{ request()->routeIs('search') ? 'active' : '' }}">
        <i class="fas fa-magnifying-glass"></i>
        <span>Ara</span>
    </a>
    <a href="{{ route('cart.index') }}" class="nav-item {{ request()->routeIs('cart.index') ? 'active' : '' }}">
        <div style="position:relative;display:inline-block;">
            <i class="fas fa-shopping-bag"></i>
            @php $cartCount = array_sum(array_column(session('cart', []), 'quantity')); @endphp
            @if($cartCount > 0) <span class="nav-badge" id="mobile-cart-badge">{{ $cartCount }}</span> @endif
        </div>
        <span>Sepetim</span>
    </a>
    <a href="{{ route('account.index') }}" class="nav-item {{ request()->routeIs('account.*') ? 'active' : '' }}">
        <i class="far fa-user"></i>
        <span>Hesabım</span>
    </a>
</div>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobile-menu" onclick="if(event.target===this)this.classList.remove('show')">
    <div class="mobile-menu-content">
        <!-- Menu Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; padding:20px; border-bottom:1px solid #f0f0f0; background:#fafaf8;">
            <div>
                <h3 style="font-size:18px; font-weight:800; color:var(--primary); margin:0;">Bendyy<span style="color:var(--accent);">Yatak</span></h3>
                <p style="font-size:11px; color:var(--text-muted); margin:4px 0 0;">Kategoriler & Menü</p>
            </div>
            <button onclick="document.getElementById('mobile-menu').classList.remove('show')" style="width:36px;height:36px;background:#fff;border:1px solid #e5e7eb;border-radius:50%;font-size:16px;cursor:pointer;color:var(--text);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Search in Menu -->
        <div style="padding:12px 20px; border-bottom:1px solid #f0f0f0;">
            <form action="{{ route('search') }}" method="GET" style="display:flex;gap:8px;">
                <input type="text" name="q" placeholder="Ürün ara..." style="flex:1;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;font-family:inherit;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">
                <button type="submit" style="background:var(--primary);color:#fff;border:none;border-radius:10px;padding:10px 16px;cursor:pointer;font-size:14px;"><i class="fas fa-magnifying-glass"></i></button>
            </form>
        </div>

        <!-- Categories -->
        <div style="padding:8px 12px; overflow-y:auto; flex:1;">
            <div style="padding:8px 8px 4px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--text-light);">Kategoriler</div>
            @php
                try {
                    $navCats = \App\Models\Category::whereNull('parent_id')->where('is_active', true)->with('children')->orderBy('sort_order')->get();
                } catch (\Exception $e) { $navCats = collect(); }
            @endphp
            @foreach($navCats as $cat)
                <a href="{{ route('category.show', $cat) }}" style="display:flex; align-items:center; gap:12px; padding:12px 10px; border-radius:10px; font-weight:500; font-size:14px; color:var(--text); transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                    <i class="fas fa-chevron-right" style="font-size:10px;color:var(--accent);"></i>
                    {{ $cat->name }}
                </a>
                @if($cat->children->isNotEmpty())
                    @foreach($cat->children as $sub)
                        <a href="{{ route('category.show', $sub) }}" style="display:flex; align-items:center; gap:12px; padding:10px 10px 10px 32px; border-radius:10px; font-weight:400; font-size:13px; color:var(--text-muted); transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                            {{ $sub->name }}
                        </a>
                    @endforeach
                @endif
            @endforeach
            <a href="{{ route('blog.index') }}" style="display:flex; align-items:center; gap:12px; padding:12px 10px; border-radius:10px; font-weight:500; font-size:14px; color:var(--text); transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                <i class="fas fa-chevron-right" style="font-size:10px;color:var(--accent);"></i>
                Blog
            </a>
            
            <!-- Account Links -->
            <div style="margin-top:16px; padding-top:16px; border-top:1px solid #f0f0f0;">
                <div style="padding:8px 8px 4px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--text-light);">Hesabım</div>
                @auth
                    <a href="{{ route('account.index') }}" style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:10px; font-size:13px; color:var(--text); transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                        <i class="far fa-user" style="width:18px;text-align:center;color:var(--text-muted);"></i> Profilim
                    </a>
                    <a href="{{ route('account.orders') }}" style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:10px; font-size:13px; color:var(--text); transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-box" style="width:18px;text-align:center;color:var(--text-muted);"></i> Siparişlerim
                    </a>
                    <a href="{{ route('wishlist.index') }}" style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:10px; font-size:13px; color:var(--text); transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                        <i class="far fa-heart" style="width:18px;text-align:center;color:var(--text-muted);"></i> Favorilerim
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin-top:8px;">
                        @csrf 
                        <button type="submit" style="display:flex;align-items:center;gap:12px;padding:10px;border-radius:10px;width:100%;background:none;border:none;font-size:13px;color:#ef4444;font-weight:500;cursor:pointer;transition:background .15s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-sign-out-alt" style="width:18px;text-align:center;"></i> Çıkış Yap
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:10px; font-size:13px; color:var(--text); transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-sign-in-alt" style="width:18px;text-align:center;color:var(--text-muted);"></i> Giriş Yap
                    </a>
                    <a href="{{ route('register') }}" style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:10px; font-size:13px; color:var(--text); transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-user-plus" style="width:18px;text-align:center;color:var(--text-muted);"></i> Kayıt Ol
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
// Cart count update
function updateCartCount(count) {
    const el = document.getElementById('cart-count');
    if (el) {
        el.textContent = count;
        el.style.display = count > 0 ? 'flex' : 'none';
    }
    const mobileEl = document.getElementById('mobile-cart-badge');
    if (mobileEl) {
        mobileEl.textContent = count;
    }
}

// Cart toast
function showCartToast(msg) {
    const toast = document.getElementById('cart-toast');
    document.getElementById('cart-toast-msg').textContent = msg || 'Ürün sepete eklendi!';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// Add to cart via AJAX
function addToCart(productId, variantId, btn) {
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; }
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.count);
            showCartToast(data.message);
        }
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-shopping-bag"></i> Sepete Ekle'; }
    });
}

// Wishlist toggle
function toggleWishlist(productId, btn) {
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    const inWishlist = wishlist.includes(productId);
    const url = inWishlist ? '{{ route("wishlist.remove") }}' : '{{ route("wishlist.add") }}';

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (inWishlist) {
                const idx = wishlist.indexOf(productId);
                wishlist.splice(idx, 1);
                if (btn) btn.style.color = '';
            } else {
                wishlist.push(productId);
                if (btn) btn.style.color = '#ef4444';
            }
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
            const wCount = document.getElementById('wishlist-count');
            if (wCount) { wCount.textContent = data.count; wCount.style.display = data.count > 0 ? 'flex' : 'none'; }
        }
    });
}

// Compare
let compareList = JSON.parse(sessionStorage.getItem('compareList') || '[]');
function updateCompareBar() {
    const bar = document.getElementById('compare-bar');
    const count = document.getElementById('compare-count');
    if (count) count.textContent = compareList.length;
    if (bar) bar.classList.toggle('show', compareList.length > 0);
}
function toggleCompare(productId, btn) {
    const inList = compareList.includes(productId);
    const url = inList ? '{{ route("product.compare.remove") }}' : '{{ route("product.compare.add") }}';
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (inList) compareList = compareList.filter(id => id != productId);
            else compareList.push(productId);
            sessionStorage.setItem('compareList', JSON.stringify(compareList));
            updateCompareBar();
            if (btn) btn.title = inList ? 'Karşılaştırmaya Ekle' : 'Karşılaştırmadan Çıkar';
        } else {
            alert(data.message || 'Hata oluştu.');
        }
    });
}
function clearCompare() {
    compareList = [];
    sessionStorage.removeItem('compareList');
    updateCompareBar();
}

// WhatsApp tracking
function trackWhatsapp(productId) {
    fetch('{{ route("product.whatsapp.click") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ product_id: productId })
    });
}

updateCompareBar();
</script>

@php $gaId = \App\Models\Setting::get('google_analytics_id', ''); @endphp
@if($gaId)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $gaId }}');
</script>
@endif

@stack('scripts')
</body>
</html>
