@extends('layouts.admin')
@section('title', 'Blog Yazısı Oluştur')
@section('topbar-title', 'Yeni Blog Yazısı')

@section('content')
<div class="page-header">
    <div class="page-title">Yeni Blog Yazısı</div>
    <a href="{{ route('admin.blog.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri</a>
</div>

<form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-2">
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header"><span class="card-title">Yazı İçeriği</span></div>
                <div class="form-group">
                    <label class="form-label">Başlık *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Yazı başlığı...">
                </div>
                <div class="form-group">
                    <label class="form-label">Özet (Excerpt)</label>
                    <textarea name="excerpt" class="form-control" rows="3" placeholder="Kısa özet...">{{ old('excerpt') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">İçerik *</label>
                    <textarea name="content" class="form-control" rows="14" required placeholder="Blog yazısının içeriği...">{{ old('content') }}</textarea>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">SEO</span></div>
                <div class="form-group">
                    <label class="form-label">SEO Başlığı</label>
                    <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">SEO Açıklaması</label>
                    <textarea name="seo_description" class="form-control" rows="3">{{ old('seo_description') }}</textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="card">
                <div class="card-header"><span class="card-title">Yayın Ayarları</span></div>
                <div class="form-group">
                    <label class="form-label">Yayın Tarihi</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="form-group">
                    <label class="form-check"><input type="checkbox" name="is_published" checked> Hemen Yayınla</label>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Kapak Görseli</span></div>
                <div class="form-group">
                    <input type="file" name="cover_image" class="form-control" accept="image/*" id="coverInput">
                </div>
                <img id="coverPreview" style="display:none;max-width:100%;border-radius:10px;margin-top:8px;">
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;justify-content:flex-end;gap:12px;">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline">İptal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Yayınla</button>
    </div>
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
</script>
@endpush
