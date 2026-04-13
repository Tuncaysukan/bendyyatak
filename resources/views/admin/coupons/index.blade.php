@extends('layouts.admin')
@section('title', 'Kuponlar')
@section('topbar-title', 'Kupon Yönetimi')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Kuponlar</div>
        <div class="page-subtitle">Toplam {{ $coupons->total() }} kupon</div>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Yeni Kupon</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Kod</th><th>Tür</th><th>İndirim</th><th>Min. Tutar</th><th>Kullanım</th><th>Bitiş</th><th>Durum</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td><code style="background:#f3f4f6;padding:4px 8px;border-radius:5px;font-size:13px;font-weight:700;">{{ $coupon->code }}</code></td>
                    <td>{{ $coupon->type === 'percent' ? 'Yüzde (%)' : 'Sabit (₺)' }}</td>
                    <td><strong>{{ $coupon->type === 'percent' ? '%' . $coupon->value : '₺' . number_format($coupon->value, 2, ',', '.') }}</strong></td>
                    <td>{{ $coupon->min_amount ? '₺' . number_format($coupon->min_amount, 0, ',', '.') : '—' }}</td>
                    <td>{{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}</td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $coupon->expires_at?->format('d.m.Y') ?? '—' }}</td>
                    <td>
                        @if($coupon->is_active && (!$coupon->expires_at || $coupon->expires_at->isFuture()))
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Pasif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete(this)"
                                    data-name="Kupon: {{ $coupon->code }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">
                    <i class="fas fa-ticket" style="font-size:32px;display:block;margin-bottom:12px;"></i>
                    Henüz kupon yok. <a href="{{ route('admin.coupons.create') }}" style="color:var(--primary);">Yeni oluştur</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:0 0 10px;">{{ $coupons->links() }}</div>
</div>
@endsection
