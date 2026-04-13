@extends('layouts.admin')
@section('title', 'Kupon Düzenle')
@section('topbar-title', 'Kupon Düzenle')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Kupon Düzenle</div>
        <div class="page-subtitle"><code>{{ $coupon->code }}</code></div>
    </div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri</a>
</div>

<form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" id="editForm">
    @csrf @method('PUT')
    <div class="grid grid-2">
        <div class="card">
            <div class="card-header"><span class="card-title">Kupon Bilgileri</span></div>
            <div class="form-group">
                <label class="form-label">Kupon Kodu *</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required style="text-transform:uppercase;font-weight:700;letter-spacing:.05em;">
            </div>
            <div class="form-group">
                <label class="form-label">İndirim Türü *</label>
                <select name="type" id="discountType" class="form-control" onchange="updateTypeLabel()">
                    <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Yüzde (%)</option>
                    <option value="fixed"   {{ old('type', $coupon->type) === 'fixed'   ? 'selected' : '' }}>Sabit Tutar (₺)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" id="valueLabel">İndirim Değeri *</label>
                <input type="number" name="value" class="form-control" value="{{ old('value', $coupon->value) }}" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Minimum Sipariş Tutarı (₺)</label>
                <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" min="0" step="0.01">
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">Kullanım & Tarih</span></div>
            <div class="form-group">
                <label class="form-label">Maksimum Kullanım Sayısı</label>
                <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1">
            </div>
            <div class="form-group">
                <label class="form-label">Mevcut Kullanım</label>
                <input type="text" class="form-control" value="{{ $coupon->used_count }}" readonly style="background:#f9fafb;color:var(--text-muted);">
            </div>
            <div class="form-group">
                <label class="form-label">Bitiş Tarihi</label>
                <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
            </div>
            <div class="form-group" style="margin-top:10px;">
                <label class="form-check"><input type="checkbox" name="is_active" {{ $coupon->is_active ? 'checked' : '' }}> Aktif</label>
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;gap:12px;justify-content:flex-end;">
        <button type="button" class="btn btn-danger" onclick="confirmDelete(this)"
                data-name="Kupon: {{ $coupon->code }}" data-form="deleteForm">
            <i class="fas fa-trash"></i> Sil
        </button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline">İptal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Güncelle</button>
    </div>
</form>

<form id="deleteForm" action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function updateTypeLabel() {
    const type = document.getElementById('discountType').value;
    document.getElementById('valueLabel').textContent = type === 'percent' ? 'İndirim Değeri (%) *' : 'İndirim Tutarı (₺) *';
}
updateTypeLabel();

document.querySelectorAll('[data-form]').forEach(btn => {
    btn.addEventListener('click', function() {
        const formId = this.dataset.form;
        Swal.fire({ title: 'Emin misiniz?', html:`<b>${this.dataset.name}</b> silinecek.`, icon:'warning',
            showCancelButton:true, confirmButtonColor:'#ef4444', cancelButtonColor:'#6b7280',
            confirmButtonText:'Evet, Sil', cancelButtonText:'Vazgeç',
        }).then(r => { if (r.isConfirmed) document.getElementById(formId).submit(); });
    });
});
</script>
@endpush
