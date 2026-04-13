@extends('layouts.admin')
@section('title', 'SEO Ayarları')
@section('topbar-title', 'SEO Ayarları')

@section('content')
<div class="page-header">
    <div class="page-title">SEO Ayarları</div>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.settings.seo.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Varsayılan SEO Başlığı (Title)</label>
            <input type="text" name="seo_title" class="form-control" value="{{ App\Models\Setting::get('seo_title', 'Bendyy Yatak - Kaliteli Uyku') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Varsayılan SEO Açıklaması (Description)</label>
            <textarea name="seo_description" class="form-control" rows="3">{{ App\Models\Setting::get('seo_description', '') }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Varsayılan Anahtar Kelimeler (Keywords)</label>
            <input type="text" name="seo_keywords" class="form-control" value="{{ App\Models\Setting::get('seo_keywords', '') }}">
            <div class="form-hint">Virgül ile ayırın. (Örn: yatak, visco, çift kişilik yatak)</div>
        </div>
        <div class="form-group">
            <label class="form-label">Google Analytics Kodu (GA4 vb.)</label>
            <textarea name="google_analytics" class="form-control" rows="4" placeholder="<script>...</script>">{{ App\Models\Setting::get('google_analytics', '') }}</textarea>
        </div>
        <div style="margin-top:20px;display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kaydet</button>
        </div>
    </form>
</div>
@endsection
