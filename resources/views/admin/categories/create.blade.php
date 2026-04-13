@extends('layouts.admin')

@section('title', 'Yeni Kategori')
@section('topbar-title', 'Yeni Kategori Oluştur')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Yeni Kategori</div>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">
        <i class="fas fa-arrow-left"></i> Geri Dön
    </a>
</div>

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-2">
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header"><span class="card-title">Temel Bilgiler</span></div>
                <div class="form-group">
                    <label class="form-label">Kategori Adı *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Örn: Ortopedik Yatak">
                </div>
                <div class="form-group">
                    <label class="form-label">Üst Kategori</label>
                    <select name="parent_id" class="form-control">
                        <option value="">— Ana Kategori (üst kategori yok) —</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-control" placeholder="Kategori açıklaması...">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Sıralama</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        Kategoriyi yayında tut
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                        Öne Çıkan Kategori
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_bestseller" {{ old('is_bestseller') ? 'checked' : '' }}>
                        Popüler Kategori
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="show_on_slider" {{ old('show_on_slider') ? 'checked' : '' }}>
                        Anasayfada Göster
                    </label>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">SEO Ayarları</span></div>
                <div class="form-group">
                    <label class="form-label">SEO Başlığı</label>
                    <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title') }}" placeholder="Arama motoru başlığı">
                </div>
                <div class="form-group">
                    <label class="form-label">SEO Açıklaması</label>
                    <textarea name="seo_description" class="form-control" placeholder="Arama motoru açıklaması (150-160 karakter önerilir)" style="min-height:80px;">{{ old('seo_description') }}</textarea>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header"><span class="card-title">Kategori Görseli</span></div>
                <div class="form-group">
                    <label class="form-label">Görsel</label>
                    <input type="file" name="image" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp">
                    <div class="form-hint">Önerilen: 600x400 px, max 2MB, JPG/PNG/WebP</div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;justify-content:flex-end;gap:12px;">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">İptal</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Kategori Oluştur
        </button>
    </div>
</form>
@endsection
