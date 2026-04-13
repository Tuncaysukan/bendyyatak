@extends('layouts.admin')
@section('title', 'Kategori Düzenle')
@section('topbar-title', 'Kategori Düzenle')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Kategori Düzenle</div>
        <div class="page-subtitle">{{ $category->name }}</div>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri</a>
</div>

{{-- Güncelleme formu --}}
<form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" id="updateForm">
    @csrf @method('PUT')
    <div class="grid grid-2">
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header"><span class="card-title">Temel Bilgiler</span></div>
                <div class="form-group">
                    <label class="form-label">Kategori Adı *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Üst Kategori</label>
                    <select name="parent_id" class="form-control">
                        <option value="">— Ana Kategori —</option>
                        @foreach($parents as $parent)
                            @if($parent->id !== $category->id)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Açıklama</label>
                    <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Sıralama</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order) }}" min="0">
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        Yayında tut
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_featured" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}>
                        Öne Çıkan Kategori
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_bestseller" {{ old('is_bestseller', $category->is_bestseller) ? 'checked' : '' }}>
                        Popüler Kategori
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="show_on_slider" {{ old('show_on_slider', $category->show_on_slider) ? 'checked' : '' }}>
                        Anasayfada Göster
                    </label>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">SEO Ayarları</span></div>
                <div class="form-group">
                    <label class="form-label">SEO Başlığı</label>
                    <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $category->seo_title) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">SEO Açıklaması</label>
                    <textarea name="seo_description" class="form-control" style="min-height:80px;">{{ old('seo_description', $category->seo_description) }}</textarea>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header"><span class="card-title">Kategori Görseli</span></div>
                @if($category->image)
                <div style="margin-bottom:12px;position:relative;display:inline-block;">
                    <img src="{{ asset('storage/'.$category->image) }}" style="max-width:100%;border-radius:10px;max-height:180px;object-fit:cover;">
                    <div style="font-size:12px;color:var(--text-muted);margin-top:6px;">Mevcut görsel</div>
                    <label class="form-check" style="margin-top:6px;">
                        <input type="checkbox" name="remove_image" value="1"> Görseli Sil
                    </label>
                </div>
                @endif
                <div class="form-group">
                    <label class="form-label">Yeni Görsel</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div class="form-hint">Boş bırakılırsa mevcut görsel korunur.</div>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;gap:12px;justify-content:flex-end;">
        {{-- SIL — formun DIŞINDA, ayrı gizli form tetikleniyor --}}
        <button type="button" class="btn btn-danger" onclick="confirmDelete(this)" data-name="{{ $category->name }}"
                data-form="deleteForm">
            <i class="fas fa-trash"></i> Sil
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">İptal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Güncelle</button>
    </div>
</form>

{{-- Ayrı silme formu — güncelleme formuyla asla iç içe değil --}}
<form id="deleteForm" action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
// confirmDelete'e data-form desteği ekle: butona data-form="deleteForm" verilirse o formu submit eder
document.querySelectorAll('[data-form]').forEach(btn => {
    btn.addEventListener('click', function() {
        const name = this.dataset.name || 'kaydı';
        const formId = this.dataset.form;
        Swal.fire({
            title: 'Emin misiniz?',
            html: `<b>${name}</b> silinecek. Bu işlem geri alınamaz.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash"></i> Evet, Sil',
            cancelButtonText: 'Vazgeç',
        }).then(r => { if (r.isConfirmed) document.getElementById(formId).submit(); });
    });
});
</script>
@endpush
