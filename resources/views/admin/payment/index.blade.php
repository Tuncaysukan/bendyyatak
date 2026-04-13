@extends('layouts.admin')
@section('title', 'Ödeme Kanalları')
@section('topbar-title', 'Ödeme Kanalları & Taksit Planları')

@section('content')
<div class="page-header">
    <div class="page-title">Ödeme Kanalları</div>
</div>

<div class="grid grid-2" style="margin-bottom:24px;">
    {{-- Iyzico --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-credit-card" style="color:var(--primary);margin-right:6px;"></i> Iyzico Entegrasyonu</span>
            @php $iyzicoKey = config('services.iyzico.api_key', env('IYZICO_API_KEY')); @endphp
            @if($iyzicoKey)
                <span class="badge badge-success">Yapılandırıldı</span>
            @else
                <span class="badge badge-danger">Yapılandırılmadı</span>
            @endif
        </div>
        <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px;">
            .env dosyasındaki <code>IYZICO_API_KEY</code> ve <code>IYZICO_SECRET_KEY</code> değerlerini doldurun.
        </p>
        <div style="background:#f9fafb;border-radius:8px;padding:14px;font-size:12.5px;font-family:monospace;color:#374151;">
            <div>IYZICO_API_KEY={{ $iyzicoKey ? '••••••••' : 'buraya_ekleyin' }}</div>
            <div>IYZICO_SECRET_KEY={{ env('IYZICO_SECRET_KEY') ? '••••••••' : 'buraya_ekleyin' }}</div>
            <div>IYZICO_BASE_URL={{ env('IYZICO_BASE_URL', 'https://sandbox-api.iyzipay.com') }}</div>
        </div>
    </div>

    {{-- Kapıda Ödeme --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-money-bill-wave" style="color:var(--success);margin-right:6px;"></i> Kapıda Ödeme</span>
        </div>
        <form action="{{ route('admin.payment.update') }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="cod_enabled"
                           {{ \App\Models\Setting::get('cod_enabled', '1') === '1' ? 'checked' : '' }}>
                    Kapıda ödemeyi etkinleştir
                </label>
            </div>
            <div class="form-group">
                <label class="form-label">Kapıda Ödeme Ücreti (₺)</label>
                <input type="number" name="cod_fee" class="form-control"
                       value="{{ \App\Models\Setting::get('cod_fee', '0') }}" min="0" step="0.01">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Kaydet</button>
        </form>
    </div>
</div>

{{-- Taksit Planları --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-table" style="color:var(--primary);margin-right:6px;"></i> Taksit Planları</span>
        <a href="{{ route('admin.installment.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Yeni Plan</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Banka</th><th>Taksit</th><th>Faiz Oranı (%)</th><th>Durum</th><th>Sıra</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td style="font-weight:600;">{{ $plan->bank_name }}</td>
                    <td><span class="badge badge-primary">{{ $plan->installment_count }} Taksit</span></td>
                    <td>
                        @if($plan->interest_rate == 0)
                            <span class="badge badge-success">Faizsiz</span>
                        @else
                            %{{ $plan->interest_rate }}
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.installment.toggle', $plan) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="badge {{ $plan->is_active ? 'badge-success' : 'badge-danger' }}"
                                    style="border:none;cursor:pointer;font-size:12px;">
                                {{ $plan->is_active ? 'Aktif' : 'Pasif' }}
                            </button>
                        </form>
                    </td>
                    <td style="color:var(--text-muted);">{{ $plan->sort_order }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.installment.edit', $plan) }}" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('admin.installment.destroy', $plan) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete(this)" data-name="{{ $plan->bank_name }} {{ $plan->installment_count }} Taksit">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted);">Taksit planı yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
