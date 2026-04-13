@extends('layouts.admin')
@section('title', 'Kargo Ayarları')
@section('topbar-title', 'Kargo Ayarları')

@section('content')
<div class="page-header">
    <div class="page-title">Kargo Ayarları</div>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.shipping.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Sabit Kargo Ücreti (₺)</label>
            <input type="number" name="shipping_fee" class="form-control" value="{{ App\Models\Setting::get('shipping_fee', '0') }}" min="0" step="0.01">
            <div class="form-hint">Standart kargo ücreti</div>
        </div>
        <div class="form-group">
            <label class="form-label">Ücretsiz Kargo Limiti (₺)</label>
            <input type="number" name="free_shipping_limit" class="form-control" value="{{ App\Models\Setting::get('free_shipping_limit', '500') }}" min="0" step="0.01">
            <div class="form-hint">Bu tutar ve üzerindeki siparişlerde kargo ücretsiz olur.</div>
        </div>
        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="free_shipping_active" {{ App\Models\Setting::get('free_shipping_active', '1') === '1' ? 'checked' : '' }}>
                Ücretsiz Kargo Limitini Etkinleştir
            </label>
        </div>
        <div style="margin-top:20px;display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kaydet</button>
        </div>
    </form>
</div>
@endsection
