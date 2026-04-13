@extends('layouts.admin')
@section('title', 'Blog Düzenle')
@section('topbar-title', 'Blog Yazısı Düzenle')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Blog Yazısı Düzenle</div>
        <div class="page-subtitle">{{ Str::limit($post->title, 50) }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('blog.show', $post) }}" target="_blank" class="btn btn-outline"><i class="fas fa-eye"></i> Görüntüle</a>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri</a>
    </div>
</div>

<form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" id="editForm">
    @csrf @method('PUT')
    <div class="grid grid-2">
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header"><span class="card-title">Yazı İçeriği</span></div>
                <div class="form-group">
                    <label class="form-label">Başlık *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Özet (Excerpt)</label>
                    <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">İçerik *</label>
                    <textarea name="content" class="form-control" rows="14" required>{{ old('content', $post->content) }}</textarea>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">SEO</span></div>
                <div class="form-group">
                    <label class="form-label">SEO Başlığı</label>
                    <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $post->seo_title) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">SEO Açıklaması</label>
                    <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description', $post->seo_description) }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header"><span class="card-title">Yayın Ayarları</span></div>
                <div class="form-group">
                    <label class="form-label">Yayın Tarihi</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                           value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="is_published" {{ $post->is_published ? 'checked' : '' }}> Yayında
                    </label>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Kapak Görseli</span></div>
                @if($post->cover_image)
                <div style="margin-bottom:12px;">
                    <img src="{{ asset('storage/'.$post->cover_image) }}" style="max-width:100%;border-radius:10px;max-height:180px;object-fit:cover;">
                    <label class="form-check" style="margin-top:8px;">
                        <input type="checkbox" name="remove_cover"> Görseli Kaldır
                    </label>
                </div>
                @endif
                <div class="form-group">
                    <input type="file" name="cover_image" class="form-control" accept="image/*" id="coverInput">
                    <div class="form-hint">Yeni görsel yüklerseniz eskinin üzerine yazılır.</div>
                </div>
                <img id="coverPreview" style="display:none;max-width:100%;border-radius:10px;margin-top:8px;">
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;gap:12px;justify-content:flex-end;">
        <button type="button" class="btn btn-danger" onclick="confirmDelete(this)"
                data-name="{{ Str::limit($post->title, 30) }}" data-form="deleteForm">
            <i class="fas fa-trash"></i> Sil
        </button>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline">İptal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Güncelle</button>
    </div>
</form>

<form id="deleteForm" action="{{ route('admin.blog.destroy', $post) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.getElementById('coverInput')?.addEventListener('change', function() {
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('coverPreview');
        preview.src = e.target.result;
        preview.style.display = 'block';
    };
    if (this.files[0]) reader.readAsDataURL(this.files[0]);
});

document.querySelectorAll('[data-form]').forEach(btn => {
    btn.addEventListener('click', function() {
        const name = this.dataset.name;
        const formId = this.dataset.form;
        Swal.fire({
            title: 'Emin misiniz?',
            html: `<b>${name}</b> silinecek.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Evet, Sil',
            cancelButtonText: 'Vazgeç',
        }).then(r => { if (r.isConfirmed) document.getElementById(formId).submit(); });
    });
});
</script>
@endpush
