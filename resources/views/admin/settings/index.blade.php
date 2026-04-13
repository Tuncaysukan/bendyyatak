@extends('layouts.admin')
@section('title', 'Genel Ayarlar')
@section('topbar-title', 'Genel Ayarlar')

@section('content')
<div class="page-header">
    <div class="page-title">Genel Ayarlar</div>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Site Logosu</label>
            @if(App\Models\Setting::get('site_logo'))
                <div style="margin-bottom:10px;">
                    <img src="{{ Storage::url(App\Models\Setting::get('site_logo')) }}" alt="Logo" style="height:40px; background:#f4f5f9; padding:5px; border-radius:4px;">
                </div>
            @endif
            <input type="file" name="site_logo" class="form-control" accept="image/*">
            <div class="form-hint">Sadece PNG/JPG önerilir. Arka planı şeffaf olsun.</div>
        </div>

        <div class="form-group">
            <label class="form-label">Site Adı</label>
            <input type="text" name="site_name" class="form-control" value="{{ App\Models\Setting::get('site_name', 'Bendyy Yatak') }}">
        </div>
        <div class="form-group">
            <label class="form-label">İletişim E-Posta</label>
            <input type="email" name="contact_email" class="form-control" value="{{ App\Models\Setting::get('contact_email', 'info@bendyy.com') }}">
        </div>
        <div class="form-group">
            <label class="form-label">İletişim Telefon</label>
            <input type="text" name="contact_phone" class="form-control" value="{{ App\Models\Setting::get('contact_phone', '') }}">
        </div>
        <div class="form-group">
            <label class="form-label">WhatsApp Numarası</label>
            <input type="text" name="whatsapp_number" class="form-control" placeholder="Örn: 05554443322" value="{{ App\Models\Setting::get('whatsapp_number', '') }}">
            <div class="form-hint">Eğer boş bırakırsanız sistemdeki WhatsApp butonları gizlenir. Sadece rakam veya uluslararası formatta (+90...) girebilirsiniz.</div>
        </div>
        <div class="form-group">
            <label class="form-label">Firma Adresi</label>
            <textarea name="contact_address" class="form-control" rows="2">{{ App\Models\Setting::get('contact_address', '') }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Google Harita iframe (İletişim sayfası için)</label>
            <textarea name="contact_map_iframe" class="form-control" rows="3" placeholder='<iframe src="..."></iframe>'>{{ App\Models\Setting::get('contact_map_iframe', '') }}</textarea>
        </div>
        <div style="margin-top:20px;display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kaydet</button>
        </div>
    </form>
</div>
@endsection
