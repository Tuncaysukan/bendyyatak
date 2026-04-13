@extends('layouts.admin')
@section('title', 'Yorumlar')
@section('topbar-title', 'Yorum Yönetimi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Yorumlar</div>
        <div class="page-subtitle">Toplam {{ $reviews->total() }} yorum</div>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div class="form-group" style="margin:0;min-width:160px;">
            <label class="form-label">Durum</label>
            <select name="status" class="form-control">
                <option value="">Tümü</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Bekleyenler</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Onaylananlar</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Reddedilenler</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrele</button>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline">Temizle</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Ürün</th><th>Müşteri</th><th>Puan</th><th>Yorum</th><th>Tarih</th><th>Durum</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>
                        @if($review->product)
                            <a href="{{ route('admin.products.edit', $review->product) }}" style="color:var(--primary);text-decoration:none;font-weight:500;font-size:13px;">
                                {{ Str::limit($review->product->name, 30) }}
                            </a>
                        @else <span style="color:var(--text-muted);">—</span> @endif
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $review->name }}</div>
                        <div style="font-size:11.5px;color:var(--text-muted);">{{ $review->email }}</div>
                    </td>
                    <td>
                        @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="color:{{ $i <= $review->rating ? '#f59e0b' : '#e5e7eb' }};font-size:12px;"></i>@endfor
                    </td>
                    <td style="max-width:260px;color:var(--text-muted);font-size:13px;">{{ Str::limit($review->comment, 80) }}</td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $review->created_at->format('d.m.Y') }}</td>
                    <td>
                        @if($review->is_approved)
                            <span class="badge badge-success">Onaylı</span>
                        @else
                            <span class="badge badge-warning">Bekliyor</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            @if(!$review->is_approved)
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Onayla"><i class="fas fa-check"></i></button>
                            </form>
                            @endif
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete(this)"
                                    data-name="{{ $review->name }} yorumu"
                                    title="Sil"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">Yorum yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:0 0 10px;">{{ $reviews->links() }}</div>
</div>
@endsection
