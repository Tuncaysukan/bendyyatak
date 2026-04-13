@extends('layouts.admin')
@section('title', 'Blog')
@section('topbar-title', 'Blog Yönetimi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Blog Yazıları</div>
        <div class="page-subtitle">Toplam {{ $posts->total() }} yazı</div>
    </div>
    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Yeni Yazı</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Görsel</th><th>Başlık</th><th>Kategori</th><th>Durum</th><th>Tarih</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>
                        @if($post->cover_image)
                            <img src="{{ asset('storage/'.$post->cover_image) }}" style="width:60px;height:40px;object-fit:cover;border-radius:6px;">
                        @else
                            <div style="width:60px;height:40px;background:#f3f4f6;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#d1d5db;"><i class="fas fa-image"></i></div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ Str::limit($post->title, 55) }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">/blog/{{ $post->slug }}</div>
                    </td>
                    <td style="color:var(--text-muted);">{{ $post->category?->name ?? '—' }}</td>
                    <td>
                        @if($post->is_published)
                            <span class="badge badge-success">Yayında</span>
                        @else
                            <span class="badge badge-secondary">Taslak</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $post->published_at?->format('d.m.Y') ?? '—' }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('blog.show', $post) }}" target="_blank" class="btn btn-outline btn-sm" title="Göster"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.blog.edit', $post) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.blog.destroy', $post) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete(this)"
                                    data-name="{{ Str::limit($post->title, 30) }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">
                    <i class="fas fa-pen-nib" style="font-size:32px;display:block;margin-bottom:12px;"></i>
                    Henüz yazı yok. <a href="{{ route('admin.blog.create') }}" style="color:var(--primary);">İlk yazıyı oluştur</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:0 0 10px;">{{ $posts->links() }}</div>
</div>
@endsection
