@extends('layouts.admin')
@section('title', 'Taksit Planı Düzenle')
@section('topbar-title', 'Taksit Planları')

@section('content')
<div class="page-header">
    <div class="page-title">Taksit Planı Düzenle: {{ $plan->bank_name }} - {{ $plan->installment_count }} Taksit</div>
    <div class="page-actions">
        <a href="{{ route('admin.payment.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Geri Dön</a>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.installment.update', $plan) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label">Banka Adı</label>
            <input type="text" class="form-control" value="{{ $plan->bank_name }}" disabled>
        </div>
        <div class="form-group">
            <label class="form-label">Taksit Sayısı</label>
            <input type="number" class="form-control" value="{{ $plan->installment_count }}" disabled>
        </div>
        <div class="form-group">
            <label class="form-label">Faiz Oranı (%) *</label>
            <input type="number" name="interest_rate" class="form-control" value="{{ old('interest_rate', $plan->interest_rate) }}" min="0" step="0.01" required>
        </div>
        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_active" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                Aktif
            </label>
        </div>
        
        <div style="margin-top:20px;display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Güncelle</button>
        </div>
    </form>
</div>
@endsection
