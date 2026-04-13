@extends('layouts.app')
@section('title', 'Hesabım')

@section('content')
<div class="container" style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    <div class="account-layout" style="display:flex; gap: 30px; flex-wrap: wrap;">
        <!-- Sidebar -->
        <div class="account-sidebar" style="width: 250px; flex-shrink: 0;">
            <div style="background: white; border: 1px solid #eee; border-radius: 8px; overflow: hidden;">
                <div style="padding: 20px; background: #f9f9f9; text-align: center; border-bottom: 1px solid #eee;">
                    <div style="width: 60px; height: 60px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; margin: 0 auto 12px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <h3 style="font-size: 16px; margin:0;">{{ auth()->user()->name }}</h3>
                    <p style="color: #666; font-size: 13px; margin: 4px 0 0;">{{ auth()->user()->email }}</p>
                </div>
                <div style="display: flex; flex-direction: column;">
                    <a href="{{ route('account.index') }}" style="padding: 12px 20px; border-bottom: 1px solid #f0f0f0; text-decoration: none; color: {{ request()->routeIs('account.index') ? 'var(--primary)' : '#333' }}; font-weight: {{ request()->routeIs('account.index') ? '600' : 'normal' }}; background: {{ request()->routeIs('account.index') ? '#f3f4fa' : 'transparent' }};">
                        <i class="fas fa-home" style="width: 24px; text-align: center; margin-right: 8px;"></i> Hesap Özeti
                    </a>
                    <a href="{{ route('account.orders') }}" style="padding: 12px 20px; border-bottom: 1px solid #f0f0f0; text-decoration: none; color: {{ request()->routeIs('account.orders*') ? 'var(--primary)' : '#333' }}; font-weight: {{ request()->routeIs('account.orders*') ? '600' : 'normal' }}; background: {{ request()->routeIs('account.orders*') ? '#f3f4fa' : 'transparent' }};">
                        <i class="fas fa-shopping-bag" style="width: 24px; text-align: center; margin-right: 8px;"></i> Siparişlerim
                    </a>
                    <a href="{{ route('account.addresses') }}" style="padding: 12px 20px; border-bottom: 1px solid #f0f0f0; text-decoration: none; color: {{ request()->routeIs('account.addresses*') ? 'var(--primary)' : '#333' }}; font-weight: {{ request()->routeIs('account.addresses*') ? '600' : 'normal' }}; background: {{ request()->routeIs('account.addresses*') ? '#f3f4fa' : 'transparent' }};">
                        <i class="fas fa-map-marker-alt" style="width: 24px; text-align: center; margin-right: 8px;"></i> Adreslerim
                    </a>
                    <a href="{{ route('account.profile') }}" style="padding: 12px 20px; border-bottom: 1px solid #f0f0f0; text-decoration: none; color: {{ request()->routeIs('account.profile') ? 'var(--primary)' : '#333' }}; font-weight: {{ request()->routeIs('account.profile') ? '600' : 'normal' }}; background: {{ request()->routeIs('account.profile') ? '#f3f4fa' : 'transparent' }};">
                        <i class="fas fa-user" style="width: 24px; text-align: center; margin-right: 8px;"></i> Profil Bilgilerim
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 12px 20px; background: transparent; border: none; text-align: left; color: var(--danger); font-size: 15px; cursor: pointer;">
                            <i class="fas fa-sign-out-alt" style="width: 24px; text-align: center; margin-right: 8px;"></i> Çıkış Yap
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div style="flex: 1; min-width: 0;">
            @yield('account_content')
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media(max-width: 768px) {
    .account-sidebar { width: 100% !important; margin-bottom: 10px; }
    .account-layout { flex-direction: column !important; gap: 16px !important; }
}
</style>
@endpush
