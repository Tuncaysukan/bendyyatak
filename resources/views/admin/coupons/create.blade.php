@extends('layouts.admin')
@section('title', 'Yeni Kupon')
@section('topbar-title', 'Yeni Kupon Oluştur')

@section('content')
<div class="page-header">
    <div class="page-title">Yeni Kupon</div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri</a>
</div>

<form action="{{ route('admin.coupons.store') }}" method="POST">
    @csrf
    <div class="grid grid-2">
        <div class="card">
            <div class="card-header"><span class="card-title">Kupon Bilgileri</span></div>
            <div class="form-group">
                <label class="form-label">Kupon Kodu *</label>
                <div style="display:flex;gap:8px;">
                    <input type="text" name="code" id="couponCode" class="form-control" value="{{ old('code') }}"
                           placeholder="YILBASI20" required style="text-transform:uppercase;">
                    <button type="button" onclick="generateCode()" class="btn btn-outline" style="white-space:nowrap;flex-shrink:0;">
                        <i class="fas fa-dice"></i> Oluştur
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">İndirim Türü *</label>
                <select name="type" id="discountType" class="form-control" onchange="updateTypeLabel()">
                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Yüzde (%)</option>
                    <option value="fixed"   {{ old('type') === 'fixed'   ? 'selected' : '' }}>Sabit Tutar (₺)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" id="valueLabel">İndirim Değeri (%) *</label>
                <input type="number" name="value" class="form-control" value="{{ old('value') }}" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Minimum Sipariş Tutarı (₺)</label>
                <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount') }}" min="0" step="0.01">
                <div class="form-hint">Boş bırakılırsa limit yok</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">Kullanım Ayarları</span></div>
            <div class="form-group">
                <label class="form-label">Maksimum Kullanım Sayısı</label>
                <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}" min="1">
                <div class="form-hint">Boş bırakılırsa sınırsız</div>
            </div>
            <div class="form-group">
                <label class="form-label">Kişi Başı Kullanım Limiti</label>
                <input type="number" name="usage_per_user" class="form-control" value="{{ old('usage_per_user', 1) }}" min="1">
            </div>
            <div class="form-group">
                <label class="form-label">Başlangıç Tarihi</label>
                <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at', now()->format('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Bitiş Tarihi</label>
                <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                <div class="form-hint">Boş bırakılırsa süresiz</div>
            </div>
            <div class="form-group">
                <label class="form-label">Açıklama (İç Not)</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Yalnızca admin görür...">{{ old('description') }}</textarea>
            </div>
            <div class="form-group" style="margin-top:10px;">
                <label class="form-check"><input type="checkbox" name="is_active" checked> Aktif</label>
            </div>
        </div>
    </div>

    <div style="margin-top:20px;display:flex;justify-content:flex-end;gap:12px;">
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline">İptal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kuponu Kaydet</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
function generateCode() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let code = '';
    for (let i = 0; i < 8; i++) code += chars[Math.floor(Math.random() * chars.length)];
    document.getElementById('couponCode').value = code;
}
function updateTypeLabel() {
    const type = document.getElementById('discountType').value;
    document.getElementById('valueLabel').textContent = type === 'percent' ? 'İndirim Değeri (%) *' : 'İndirim Tutarı (₺) *';
}
</script>
@endpush
