<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — BendyyYatak</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6c47ff;
            --primary-dark: #5535d4;
            --primary-light: #ede9ff;
            --sidebar-bg: #0f0f1a;
            --sidebar-text: #a0a0b8;
            --sidebar-hover: #1a1a2e;
            --sidebar-active: #6c47ff;
            --bg: #f4f5f9;
            --card: #ffffff;
            --text: #1a1a2e;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --sidebar-w: 260px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: var(--sidebar-w); background: var(--sidebar-bg); position: fixed; top: 0; left: 0; height: 100vh; overflow-y: auto; z-index: 100; transition: transform .3s; display: flex; flex-direction: column; }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }
        .sidebar-logo { padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,.06); }
        .sidebar-logo a { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .sidebar-logo .logo-icon { width: 40px; height: 40px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: white; flex-shrink: 0; }
        .sidebar-logo .logo-text { font-size: 15px; font-weight: 700; color: #fff; line-height: 1.2; }
        .sidebar-logo .logo-sub { font-size: 11px; color: var(--sidebar-text); font-weight: 400; }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-label { font-size: 10px; font-weight: 600; color: #4b4b6a; text-transform: uppercase; letter-spacing: .08em; padding: 16px 8px 8px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 8px; color: var(--sidebar-text); text-decoration: none; font-size: 13.5px; font-weight: 500; margin-bottom: 2px; transition: all .2s; }
        .nav-item:hover { background: var(--sidebar-hover); color: #fff; }
        .nav-item.active { background: var(--primary); color: #fff; }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }
        .nav-badge { margin-left: auto; background: var(--danger); color: #fff; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 10px; }

        /* Main */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-width: 0; }

        /* Topbar */
        .topbar { background: var(--card); border-bottom: 1px solid var(--border); padding: 0 24px; height: 64px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-title { font-size: 16px; font-weight: 600; color: var(--text); }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-admin { display: flex; align-items: center; gap: 10px; }
        .topbar-admin .avatar { width: 36px; height: 36px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; font-weight: 600; }
        .topbar-admin .name { font-size: 13px; font-weight: 500; }

        /* Content */
        .content { padding: 24px; flex: 1; }
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .page-title { font-size: 22px; font-weight: 700; color: var(--text); }
        .page-subtitle { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

        /* Cards */
        .card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 20px; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
        .card-title { font-size: 15px; font-weight: 600; }
        .grid { display: grid; gap: 20px; }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }

        /* Stats */
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .stat-icon.purple { background: var(--primary-light); color: var(--primary); }
        .stat-icon.green  { background: #dcfce7; color: var(--success); }
        .stat-icon.orange { background: #fef3c7; color: var(--warning); }
        .stat-icon.blue   { background: #dbeafe; color: var(--info); }
        .stat-value { font-size: 22px; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 4px; }

        /* Tables */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        thead th { background: #f9fafb; padding: 10px 14px; text-align: left; font-weight: 600; font-size: 11.5px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; border-bottom: 2px solid var(--border); }
        tbody td { padding: 12px 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tbody tr:hover { background: #fafafa; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: 11.5px; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-danger  { background: #fee2e2; color: #b91c1c; }
        .badge-info    { background: #dbeafe; color: #1d4ed8; }
        .badge-primary { background: var(--primary-light); color: var(--primary); }
        .badge-secondary { background: #f3f4f6; color: #4b5563; }
        .badge-indigo  { background: #e0e7ff; color: #4338ca; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 600; border: none; cursor: pointer; text-decoration: none; transition: all .2s; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-danger  { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }

        /* Forms */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .form-control { width: 100%; padding: 9px 13px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13.5px; font-family: 'Inter', sans-serif; color: var(--text); background: #fff; transition: border-color .2s; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(108,71,255,.15); }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%236b7280' d='M6 8L0 0h12z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px; }
        textarea.form-control { resize: vertical; min-height: 100px; }
        .form-hint { font-size: 11.5px; color: var(--text-muted); margin-top: 4px; }
        .form-check { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13.5px; }
        .form-check input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; accent-color: var(--primary); }

        /* Alerts */
        .alert { padding: 13px 16px; border-radius: 8px; font-size: 13.5px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 10px; }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

        /* Pagination */
        .pagination { display: flex; align-items: center; gap: 6px; padding: 20px 0 4px; }
        .pagination a, .pagination span { padding: 6px 12px; border-radius: 6px; font-size: 13px; border: 1.5px solid var(--border); color: var(--text); text-decoration: none; }
        .pagination .active span { background: var(--primary); border-color: var(--primary); color: white; }
        .pagination a:hover { border-color: var(--primary); color: var(--primary); }

        /* Toggle switch */
        .toggle { position: relative; display: inline-block; width: 42px; height: 24px; }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-slider { position: absolute; cursor: pointer; inset: 0; background: #d1d5db; border-radius: 99px; transition: .25s; }
        .toggle-slider::before { content: ''; position: absolute; width: 18px; height: 18px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: .25s; }
        .toggle input:checked + .toggle-slider { background: var(--primary); }
        .toggle input:checked + .toggle-slider::before { transform: translateX(18px); }

        @media (max-width: 1024px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('admin.dashboard') }}">
            <div class="logo-icon"><i class="fas fa-bed"></i></div>
            <div>
                <div class="logo-text">BendyyYatak</div>
                <div class="logo-sub">Admin Paneli</div>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Genel</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>

        <div class="nav-label">Katalog</div>
        <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-folder-tree"></i> Kategoriler
        </a>
        <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> Ürünler
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Yorumlar
        </a>

        <div class="nav-label">Siparişler</div>
        <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i> Siparişler
            @php $pendingCount = \App\Models\Order::where('status','pending')->count(); @endphp
            @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Müşteriler
        </a>
        <a href="{{ route('admin.coupons.index') }}" class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <i class="fas fa-ticket"></i> Kuponlar
        </a>
        <a href="{{ route('admin.messages.index') }}" class="nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i> Gelen Kutusu
            @php $unreadMsgs = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
            @if($unreadMsgs > 0)
                <span class="nav-badge">{{ $unreadMsgs }}</span>
            @endif
        </a>

        <div class="nav-label">Raporlar</div>
        <a href="{{ route('admin.reports.sales') }}" class="nav-item {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Satış Raporları
        </a>
        <a href="{{ route('admin.reports.views') }}" class="nav-item {{ request()->routeIs('admin.reports.views') ? 'active' : '' }}">
            <i class="fas fa-eye"></i> Görüntülenme
        </a>

        <div class="nav-label">İçerik</div>
        <a href="{{ route('admin.sliders.index') }}" class="nav-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
            <i class="fas fa-images"></i> Ana Sayfa Slider
        </a>
        <a href="{{ route('admin.blog.index') }}" class="nav-item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
            <i class="fas fa-pen-nib"></i> Blog
        </a>

        <div class="nav-label">Sistem</div>
        <a href="{{ route('admin.payment.index') }}" class="nav-item {{ request()->routeIs('admin.payment.*') || request()->routeIs('admin.installment.*') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i> Ödeme Kanalları
        </a>
        <a href="{{ route('admin.paytr.settings') }}" class="nav-item {{ request()->routeIs('admin.paytr.*') ? 'active' : '' }}" style="padding-left: 36px;">
            <i class="fas fa-money-check-dollar"></i> PayTR Ayarları
        </a>
        <a href="{{ route('admin.shipping.index') }}" class="nav-item {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}">
            <i class="fas fa-truck"></i> Kargo Ayarları
        </a>
        <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="fas fa-gear"></i> Site Ayarları
        </a>
        <a href="{{ route('admin.settings.seo') }}" class="nav-item {{ request()->routeIs('admin.settings.seo*') ? 'active' : '' }}">
            <i class="fas fa-magnifying-glass"></i> SEO Ayarları
        </a>
    </nav>

    <div style="padding: 16px 12px; border-top: 1px solid rgba(255,255,255,.06);">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-item" style="width:100%; border:none; background:none; cursor:pointer; color: var(--sidebar-text);">
                <i class="fas fa-right-from-bracket"></i> Çıkış Yap
            </button>
        </form>
    </div>
</aside>

<!-- Main -->
<div class="main">
    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <span class="topbar-title">@yield('topbar-title', 'Admin Panel')</span>
        </div>
        <div class="topbar-right">
            <a href="{{ url('/') }}" target="_blank" style="color: var(--text-muted); font-size: 20px; text-decoration: none;" title="Siteyi Görüntüle">
                <i class="fas fa-external-link"></i>
            </a>
            <div class="topbar-admin">
                <div class="avatar">{{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}</div>
                <span class="name">{{ session('admin_name', 'Admin') }}</span>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-circle-exclamation"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
// ─── BendyyYatak Admin Helpers ───────────────────────────────────────────────
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/** Silme onayı: <button onclick="confirmDelete(this)" data-url="/path" data-name="Ürün Adı"> */
function confirmDelete(btn) {
    const url  = btn.dataset.url  || btn.closest('form')?.action;
    const name = btn.dataset.name || 'Bu kaydı';
    Swal.fire({
        title: 'Emin misiniz?',
        html: `<b>${name}</b> silinecek. Bu işlem geri alınamaz.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash"></i> Evet, Sil',
        cancelButtonText: 'Vazgeç',
        customClass: { popup: 'swal-admin' },
    }).then(r => {
        if (r.isConfirmed) {
            // data-no-form yoksa ve form içindeyse submit et
            if (!btn.dataset.noForm) {
                const form = btn.closest('form');
                if (form) { form.submit(); return; }
            }
            // yoksa dinamik form oluştur ve submit et
            const f = document.createElement('form');
            f.method = 'POST';
            f.action = url;
            f.style.display = 'none';
            f.innerHTML = `<input type="hidden" name="_token" value="${csrfToken}"><input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(f);
            f.submit();
        }
    });
}

/** Durum/Toggle onayı */
function confirmToggle(btn, msg) {
    Swal.fire({
        title: 'Durum Değiştir',
        text: msg || 'Bu kaydın durumunu değiştirmek istediğinize emin misiniz?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6c47ff',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Evet',
        cancelButtonText: 'Vazgeç',
    }).then(r => { if (r.isConfirmed) btn.closest('form').submit(); });
}

/** Başarı / Hata toast */
function adminToast(icon, title) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
}

// Flash messages → SweetAlert toast
@if(session('success'))
    adminToast('success', @json(session('success')));
@endif
@if(session('error'))
    adminToast('error', @json(session('error')));
@endif
</script>
@stack('scripts')
</body>
</html>
