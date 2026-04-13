@extends('layouts.admin')
@section('title', 'Yeni Slider Ekle')
@section('topbar-title', 'Slider Yönetimi')

@section('content')
<div class="page-header">
    <div class="page-title">Yeni Slider Ekle</div>
    <div class="page-actions">
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri Dön</a>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label class="form-label">Görsel (Masaüstü Önerilen: 1920x800) *</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        
        <div class="grid grid-2">
            <div class="form-group">
                <label class="form-label">Ana Başlık</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Alt Başlık</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Buton Metni</label>
                <input type="text" name="button_text" class="form-control" value="{{ old('button_text') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Buton Linki (URL)</label>
                <input type="text" name="button_link" class="form-control" value="{{ old('button_link') }}" placeholder="/urunler vs.">
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                Aktif (Görünür) olsun mu?
            </label>
        </div>
        
        <div style="margin-top:20px;display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kaydet</button>
        </div>
    </form>
</div>
@endsection
