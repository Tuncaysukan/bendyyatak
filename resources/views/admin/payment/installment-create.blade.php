@extends('layouts.admin')
@section('title', 'Yeni Taksit Planı')
@section('topbar-title', 'Taksit Planları')

@section('content')
<div class="page-header">
    <div class="page-title">Yeni Taksit Planı Ekle</div>
    <div class="page-actions">
        <a href="{{ route('admin.payment.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri Dön</a>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.installment.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Banka Adı *</label>
            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Taksit Sayısı *</label>
            <input type="number" name="installment_count" class="form-control" value="{{ old('installment_count') }}" min="2" max="36" required>
        </div>
        <div class="form-group">
            <label class="form-label">Faiz Oranı (%) *</label>
            <input type="number" name="interest_rate" class="form-control" value="{{ old('interest_rate', '0') }}" min="0" step="0.01" required>
        </div>
        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                Aktif
            </label>
        </div>
        
        <div style="margin-top:20px;display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kaydet</button>
        </div>
    </form>
</div>
@endsection
