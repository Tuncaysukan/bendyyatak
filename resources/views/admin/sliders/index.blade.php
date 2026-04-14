@extends('layouts.admin')
@section('title', 'Slider Yönetimi')
@section('topbar-title', 'Slider Yönetimi')

@section('content')
<div class="page-header">
    <div class="page-title">Ana Sayfa Slider</div>
    <div class="page-actions">
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Yeni Slider Ekle</a>
    </div>
</div>

<div class="card">
    <form action="{{ route('admin.sliders.order') }}" method="POST">
        @csrf
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Sıra</th>
                        <th style="width: 120px;">Görsel</th>
                        <th>Başlık</th>
                        <th>Durum</th>
                        <th style="width: 120px;">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $slider)
                    <tr>
                        <td>
                            <input type="number" name="order[{{ $slider->id }}]" value="{{ $slider->sort_order }}" class="form-control" style="width:60px; text-align:center; padding: 4px;">
                        </td>
                        <td>
                            <img src="{{ Storage::url($slider->image) }}" alt="Slider" style="height:50px; border-radius:6px; object-fit:cover;">
                        </td>
                        <td>
                            <strong>{{ $slider->title ?: '(Başlıksız)' }}</strong>
                            @if($slider->subtitle)
                                <div style="font-size:12px; color:var(--text-muted);">{{ $slider->subtitle }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $slider->is_active ? 'badge-success' : 'badge-danger' }}">{{ $slider->is_active ? 'Aktif' : 'Pasif' }}</span>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="event.stopPropagation(); confirmDelete(this)" data-url="{{ route('admin.sliders.destroy', $slider) }}" data-name="{{ $slider->title ?: 'Bu slider' }}" data-no-form="1"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:var(--text-muted);">Henüz slider eklenmedi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sliders->count() > 0)
        <div style="margin-top: 16px;">
            <button type="submit" class="btn btn-outline"><i class="fas fa-sort"></i> Sıralamayı Kaydet</button>
        </div>
        @endif
    </form>
</div>

@endsection
