@extends('layouts.admin')
@section('title', 'Slider Düzenle')
@section('topbar-title', 'Slider Yönetimi')

@section('content')
<div class="page-header">
    <div class="page-title">Slider Düzenle</div>
    <div class="page-actions">
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri Dön</a>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        @if($slider->image)
        <div style="margin-bottom: 20px;">
            <img src="{{ Storage::url($slider->image) }}" alt="Slider" style="height:150px; border-radius:8px; object-fit:cover;">
        </div>
        @endif
        
        <div class="form-group">
            <label class="form-label">Yeni Görsel Yükle (Boş bırakırsanız eski görsel silinmez)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        
        <div class="grid grid-2">
            <div class="form-group">
                <label class="form-label">Ana Başlık</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $slider->title) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Alt Başlık</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $slider->subtitle) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Buton Metni</label>
                <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $slider->button_text) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Buton Linki (URL)</label>
                <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $slider->button_link) }}">
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_active" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                Aktif (Görünür) olsun mu?
            </label>
        </div>
        
        <div style="margin-top:20px;display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Güncelle</button>
        </div>
    </form>
</div>
@endsection
