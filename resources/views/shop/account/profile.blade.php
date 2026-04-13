@extends('layouts.account')
@section('title', 'Profil Bilgilerim')

@section('account_content')
<div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 12px; margin-bottom: 20px;">
    <h2 style="font-size: 20px; margin: 0;">Profil Bilgilerim</h2>
</div>

<div class="card" style="padding: 24px; margin-bottom: 24px; background: white; border: 1px solid #eee; border-radius: 8px;">
    @if(session('success'))
        <div style="background:#dcfce7; color:#15803d; padding:12px; border-radius:6px; margin-bottom:16px;">{{ session('success') }}</div>
    @endif
    
    <form action="{{ route('account.profile.update') }}" method="POST">
        @csrf
        <div style="margin-bottom: 16px;">
            <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">Ad Soyad</label>
            <input type="text" name="name" value="{{ $user->name }}" required style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
        </div>
        <div style="margin-bottom: 16px;">
            <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">E-Posta (Değiştirilemez)</label>
            <input type="email" value="{{ $user->email }}" disabled style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px; background:#f9f9f9;">
        </div>
        <div style="margin-bottom: 16px;">
            <label style="display:block; font-weight:600; font-size:13px; margin-bottom:6px;">Telefon</label>
            <input type="text" name="phone" value="{{ $user->phone }}" style="width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px;">
        </div>
        
        <button type="submit" style="background:var(--primary); color:white; padding:10px 20px; border:none; border-radius:6px; font-weight:bold; cursor:pointer;">
            Bilgileri Güncelle
        </button>
    </form>
</div>
@endsection
