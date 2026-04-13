@extends('layouts.admin')

@section('title', 'Kategoriler')
@section('topbar-title', 'Kategori Yönetimi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Kategoriler</div>
        <div class="page-subtitle">Toplam {{ $categories->count() }} ana kategori</div>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Yeni Kategori
    </a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Görsel</th>
                    <th>Kategori Adı</th>
                    <th>Alt Kategoriler</th>
                    <th>Ürün Sayısı</th>
                    <th>Durum</th>
                    <th>Sıralama</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td style="color:var(--text-muted);">{{ $cat->id }}</td>
                    <td>
                        @if($cat->image)
                            <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover;">
                        @else
                            <div style="width:40px;height:40px;background:#f3f4f6;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#d1d5db;font-size:16px;"><i class="fas fa-folder"></i></div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $cat->name }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">/kategori/{{ $cat->slug }}</div>
                    </td>
                    <td>
                        @if($cat->children->isNotEmpty())
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                @foreach($cat->children as $sub)
                                    <span class="badge badge-secondary">{{ $sub->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $cat->products()->count() }}</span>
                    </td>
                    <td>
                        @if($cat->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Pasif</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);">{{ $cat->sort_order }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">
                        <i class="fas fa-folder-open" style="font-size:32px;display:block;margin-bottom:12px;"></i>
                        Henüz kategori eklenmemiş. <a href="{{ route('admin.categories.create') }}" style="color:var(--primary);">Yeni kategori ekle</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
