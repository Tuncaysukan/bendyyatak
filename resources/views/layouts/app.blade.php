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
                        accent: '#c8a96e',
                    }
                }
            }
        }
    </script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #1a1a2e;
            --primary-dark: #0d0d1a;
            --accent: #c8a96e;
            --accent-light: #f5edd8;
            --accent-dark: #a8893e;
            --bg: #fafaf8;
            --card: #ffffff;
            --text: #1a1a2e;
            --text-muted: #6b7280;
            --text-light: #9ca3af;
            --border: #e5e7eb;
            --success: #10b981;
            --radius: 12px;
        }
        html, body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); overflow-x: hidden; width: 100%; position: relative; margin: 0; padding: 0; }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; }
        /* ─── TOPBAR ─── */
        .topbar {
            background: var(--primary);
            color: rgba(255,255,255,.7);
            font-size: 12px;
            padding: 8px 0;
            text-align: center;
        }
        /* ─── HEADER ─── */
        .header {
            background: #fff;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 200;
        }
        .header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            height: 70px;
            display: flex;
            align-items: center;
            gap: 32px;
        }
        .logo {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.5px;
            flex-shrink: 0;
        }
        .logo span { color: var(--accent); }
        .main-nav { display: flex; align-items: center; gap: 28px; flex: 1; }
        .main-nav a { font-size: 13.5px; font-weight: 500; color: var(--text); transition: color .2s; position: relative; }
        .main-nav a:hover { color: var(--accent-dark); }
        .main-nav .dropdown { position: relative; }
        .main-nav .dropdown-menu {
            position: absolute;
            top: calc(100% + 12px);
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 16px 40px rgba(0,0,0,.12);
            padding: 8px;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transition: all .2s;
            z-index: 100;
        }
        .main-nav .dropdown:hover .dropdown-menu { opacity: 1; visibility: visible; top: calc(100% + 8px); }
        .dropdown-menu a {
            display: block;
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 13px;
            transition: background .2s;
        }
        .dropdown-menu a:hover { background: var(--accent-light); color: var(--accent-dark); }
        /* Search */
        .header-search {
            display: flex;
            align-items: center;
            background: #f4f5f9;
            border-radius: 99px;
            padding: 8px 16px;
            gap: 8px;
            flex: 1;
            max-width: 360px;
            margin: 0 auto;
        }
        .header-search input {
            border: none;
            background: transparent;
            font-size: 13.5px;
            font-family: 'Inter', sans-serif;
            outline: none;
            flex: 1;
            color: var(--text);
        }
        .header-search i { color: var(--text-muted); font-size: 14px; }
        /* Header icons */
        .header-icons { display: flex; align-items: center; gap: 20px; flex-shrink: 0; }
        .header-icon-btn {
            position: relative;
            color: var(--text);
            font-size: 18px;
            transition: color .2s;
            cursor: pointer;
        }
        .header-icon-btn:hover { color: var(--accent-dark); }
        .header-icon-btn .badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background: var(--accent);
            color: var(--primary);
            font-size: 10px;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* ─── CONTAINER ─── */
        .container-custom { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        /* ─── BREADCRUMB ─── */
        .breadcrumb { padding: 12px 0; font-size: 12.5px; color: var(--text-muted); }
        .breadcrumb a { color: var(--text-muted); }
        .breadcrumb span { margin: 0 6px; }
        /* ─── PRODUCT CARD ─── */
        .product-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            transition: transform .25s, box-shadow .25s;
            position: relative;
        }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,.1); }
        .product-card-img {
            aspect-ratio: 1 / 1;
            overflow: hidden;
            background: #f5f5f0;
            position: relative;
        }
        .product-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
        .product-card:hover .product-card-img img { transform: scale(1.04); }
        .product-card-badges { position: absolute; top: 12px; left: 12px; display: flex; flex-direction: column; gap: 6px; }
        .product-card-actions { position: absolute; top: 12px; right: 12px; display: flex; flex-direction: column; gap: 8px; opacity: 0; transition: opacity .2s; }
        .product-card:hover .product-card-actions { opacity: 1; }
        .card-action-btn {
            width: 34px;
            height: 34px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: var(--text);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,.12);
            border: none;
            transition: all .2s;
        }
        .card-action-btn:hover { background: var(--accent); color: var(--primary); }
        .product-card-body { padding: 16px; }
        .product-card-cat { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px; }
        .product-card-name { font-size: 14px; font-weight: 600; line-height: 1.4; margin-bottom: 10px; }
        .product-card-price { display: flex; align-items: center; gap: 10px; }
        .price-main { font-size: 17px; font-weight: 700; color: var(--text); }
        .price-old { font-size: 13px; color: var(--text-muted); text-decoration: line-through; }
        .badge-pill { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 99px; font-size: 11px; font-weight: 700; }
        .badge-discount { background: #ff4646; color: white; }
        .badge-featured { background: var(--accent); color: var(--primary); }
        .badge-new { background: var(--primary); color: white; }
        .product-card-cart {
            width: 100%;
            margin-top: 12px;
            padding: 10px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all .2s;
        }
        .product-card-cart:hover { background: var(--accent); color: var(--primary); }
        /* ─── SECTION ─── */
        .section { padding: 60px 0; }
        .section-header { text-align: center; margin-bottom: 40px; }
        .section-label { font-size: 12px; color: var(--accent-dark); font-weight: 700; letter-spacing: .1em; text-transform: uppercase; margin-bottom: 10px; }
        .section-title { font-size: 32px; font-weight: 800; line-height: 1.25; color: var(--text); }
        .section-sub { font-size: 15px; color: var(--text-muted); margin-top: 12px; }
        /* ─── GRID ─── */
        .products-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .products-grid-3 { grid-template-columns: repeat(3, 1fr); }
        /* ─── BUTTONS ─── */
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; font-family: 'Inter', sans-serif; transition: all .2s; text-decoration: none; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-accent { background: var(--accent); color: var(--primary); }
        .btn-accent:hover { background: var(--accent-dark); }
        .btn-outline { background: transparent; border: 2px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-lg { padding: 14px 32px; font-size: 15px; }
        /* ─── TRUST BADGES ─── */
        .trust-bar {
            background: var(--primary);
            color: rgba(255,255,255,.85);
            padding: 24px 0;
        }
        .trust-items { display: flex; justify-content: center; gap: 60px; }
        .trust-item { display: flex; align-items: center; gap: 14px; }
        .trust-item i { font-size: 22px; color: var(--accent); }
        .trust-item-text strong { display: block; font-size: 13.5px; font-weight: 600; color: #fff; }
        .trust-item-text span { font-size: 11.5px; color: rgba(255,255,255,.5); }
        /* ─── FOOTER ─── */
        .footer { background: var(--primary); color: rgba(255,255,255,.7); padding: 60px 0 0; margin-top: 80px; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; }
        .footer-brand h2 { font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 12px; }
        .footer-brand h2 span { color: var(--accent); }
        .footer-brand p { font-size: 13px; line-height: 1.7; }
        .footer-col h3 { font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 16px; text-transform: uppercase; letter-spacing: .06em; }
        .footer-col a { display: block; font-size: 13px; color: rgba(255,255,255,.6); margin-bottom: 9px; transition: color .2s; }
        .footer-col a:hover { color: var(--accent); }
        .footer-social { display: flex; gap: 12px; margin-top: 20px; }
        .footer-social a { width: 36px; height: 36px; border: 1px solid rgba(255,255,255,.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; color: rgba(255,255,255,.6); transition: all .2s; }
        .footer-social a:hover { background: var(--accent); border-color: var(--accent); color: var(--primary); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.08); padding: 20px 0; margin-top: 40px; text-align: center; font-size: 12px; }
        /* ─── ALERTS ─── */
        .alert { padding: 14px 18px; border-radius: 10px; margin-bottom: 16px; font-size: 13.5px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #dcfce7; color: #15803d; }
        .alert-danger  { background: #fee2e2; color: #b91c1c; }
        /* ─── CART NOTIFICATION ─── */
        .cart-toast {
            position: fixed;
            bottom: 90px;
            right: 24px;
            background: var(--primary);
            color: white;
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 500;
            box-shadow: 0 8px 24px rgba(0,0,0,.2);
            z-index: 1000;
            transform: translateY(100px);
            opacity: 0;
            transition: all .3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cart-toast.show { transform: translateY(0); opacity: 1; }
        /* ─── WHATSAPP ─── */
        .whatsapp-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 54px;
            height: 54px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 4px 20px rgba(37,211,102,.4);
            z-index: 500;
            transition: transform .2s;
        }
        .whatsapp-btn:hover { transform: scale(1.1); }
        /* ─── COMPARE BAR ─── */
        .compare-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--primary);
            color: white;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 400;
            transform: translateY(100%);
            transition: transform .3s;
        }
        .compare-bar.show { transform: translateY(0); }
        /* ─── MOBILE BOTTOM NAV ─── */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: #fff;
            box-shadow: 0 -4px 12px rgba(0,0,0,0.06);
            z-index: 1000;
            padding-bottom: env(safe-area-inset-bottom);
        }
        .mobile-bottom-nav .nav-item {
            flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 12px 0; color: var(--text-muted); font-size: 10px; font-weight: 600; gap: 5px; transition: color .2s;
        }
        .mobile-bottom-nav .nav-item.active { color: var(--primary); }
        .mobile-bottom-nav .nav-item i { font-size: 18px; }
        .mobile-bottom-nav .nav-badge {
            position: absolute; top: -5px; right: -10px; background: var(--accent); color: var(--primary);
            font-size: 9px; font-weight: 700; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; border-radius: 50%;
        }
        .mobile-menu-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); z-index: 1001;
            opacity: 0; visibility: hidden; transition: all .3s;
        }
        .mobile-menu-overlay.show { opacity: 1; visibility: visible; }
        .mobile-menu-content {
            position: absolute; top: 0; bottom: 0; left: -300px; width: 280px;
            background: #fff; transition: left .3s; box-shadow: 5px 0 15px rgba(0,0,0,0.1);
            display: flex; flex-direction: column;
        }
        .mobile-menu-overlay.show .mobile-menu-content { left: 0; }
        /* ─── RESPONSIVE ─── */
        @media (max-width: 1024px) {
            .products-grid { grid-template-columns: repeat(3, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .main-nav { display: none; }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .trust-items { flex-direction: column; gap: 20px; align-items: center; }
            .section-title { font-size: 24px; }
            .footer-grid { grid-template-columns: 1fr; }
            .topbar { font-size: 11px; padding: 6px 0; }
            .tb-hide-mobile { display: none !important; }
            .header-inner { flex-wrap: wrap; height: auto; padding: 12px 20px; gap: 12px; justify-content: space-between; }
            .header-search { order: 3; max-width: 100%; width: 100%; flex-basis: 100%; margin-top: 4px; padding: 12px 16px; border: 1px solid var(--border); }
            .header-search input { font-size: 15px; }
            .header-icons { gap: 14px; }
            .logo { font-size: 22px; }
            .mobile-bottom-nav { display: flex; }
            body { padding-bottom: 60px; }
            .whatsapp-btn { bottom: 80px; right: 16px; width: 48px; height: 48px; font-size: 22px; }
        }
        @media (max-width: 480px) {
            .products-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="container">
        <span><i class="fas fa-truck fa-xs"></i> ₺{{ number_format(\App\Models\Setting::get('free_shipping_limit', 2000), 0, ',', '.') }} üzeri ücretsiz kargo</span>
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
                <img src="{{ Storage::url(\App\Models\Setting::get('site_logo')) }}" alt="{{ \App\Models\Setting::get('site_name', 'BendyyYatak') }}" style="height: 40px;">
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
                    <span>₺{{ number_format(\App\Models\Setting::get('free_shipping_limit', 2000), 0, ',', '.') }} ve üzeri</span>
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
        <i class="fas fa-bars"></i>
        <span>Kategoriler</span>
    </a>
    <a href="{{ route('cart.index') }}" class="nav-item {{ request()->routeIs('cart.index') ? 'active' : '' }}">
        <div style="position:relative;">
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
<div class="mobile-menu-overlay" id="mobile-menu">
    <div class="mobile-menu-content">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid #eee;">
            <h3 style="font-size:16px; font-weight:800; color:var(--primary);">Menü</h3>
            <button onclick="document.getElementById('mobile-menu').classList.remove('show')" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--text-muted);"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:10px; overflow-y:auto; flex:1;">
            @php
                try {
                    $navCats = \App\Models\Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get();
                } catch (\Exception $e) { $navCats = collect(); }
            @endphp
            @foreach($navCats as $cat)
                <a href="{{ route('category.show', $cat) }}" style="display:block; padding:12px 10px; border-bottom:1px solid #f9f9f9; font-weight:600; font-size:14px; color:var(--text);">
                    {{ $cat->name }}
                </a>
            @endforeach
            <a href="{{ route('blog.index') }}" style="display:block; padding:12px 10px; font-weight:600; font-size:14px; color:var(--text); border-bottom:1px solid #f9f9f9;">Blog</a>
            
            <div style="margin-top: 20px; padding: 10px;">
                <h4 style="font-size: 11px; text-transform: uppercase; color: var(--text-light); margin-bottom: 10px;">Hesabım</h4>
                @auth
                    <a href="{{ route('account.index') }}" style="display:block; padding:8px 0; font-size:14px; color:var(--text);"><i class="far fa-user" style="width:20px;"></i> Profilim</a>
                    <a href="{{ route('account.orders') }}" style="display:block; padding:8px 0; font-size:14px; color:var(--text);"><i class="fas fa-box" style="width:20px;"></i> Siparişlerim</a>
                    <form action="{{ route('logout') }}" method="POST" style="margin-top:10px;">
                        @csrf <button type="submit" style="background:none; border:none; padding:8px 0; font-size:14px; color:#ef4444; font-weight:600; cursor:pointer;"><i class="fas fa-sign-out-alt" style="width:20px;"></i> Çıkış Yap</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" style="display:block; padding:8px 0; font-size:14px; color:var(--text);"><i class="fas fa-sign-in-alt" style="width:20px;"></i> Giriş Yap</a>
                    <a href="{{ route('register') }}" style="display:block; padding:8px 0; font-size:14px; color:var(--text);"><i class="fas fa-user-plus" style="width:20px;"></i> Kayıt Ol</a>
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
