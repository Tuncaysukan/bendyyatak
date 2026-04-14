@extends('layouts.admin')

@section('title', 'PayTR Ayarlari')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">PayTR Ayarlari</h1>
        <p class="page-subtitle">PayTR ödeme sistemi API bilgilerini ve ayarlarini yönetin</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

<div class="grid grid-2">
    <!-- API Bilgileri -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-key" style="color: var(--primary); margin-right: 8px;"></i> API Bilgileri</h3>
        </div>
        <form action="{{ route('admin.paytr.settings.save') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Merchant ID *</label>
                <input type="text" name="paytr_merchant_id" class="form-control" 
                       value="{{ old('paytr_merchant_id', \App\Models\Setting::get('paytr_merchant_id')) }}" 
                       placeholder="örn: 123456">
                <div class="form-hint">PayTR panelinden aldiginiz Merchant ID</div>
            </div>

            <div class="form-group">
                <label class="form-label">Merchant Key *</label>
                <input type="text" name="paytr_merchant_key" class="form-control" 
                       value="{{ old('paytr_merchant_key', \App\Models\Setting::get('paytr_merchant_key')) }}" 
                       placeholder="örn: aBcDeFgHiJkLmN">
                <div class="form-hint">PayTR panelinden aldiginiz Merchant Key</div>
            </div>

            <div class="form-group">
                <label class="form-label">Merchant Salt *</label>
                <input type="text" name="paytr_merchant_salt" class="form-control" 
                       value="{{ old('paytr_merchant_salt', \App\Models\Setting::get('paytr_merchant_salt')) }}" 
                       placeholder="örn: 123abc456def">
                <div class="form-hint">PayTR panelinden aldiginiz Merchant Salt</div>
            </div>

            <div class="form-group">
                <label class="form-label">Test Modu</label>
                <label class="form-check">
                    <input type="checkbox" name="paytr_test_mode" value="1" 
                           {{ old('paytr_test_mode', \App\Models\Setting::get('paytr_test_mode')) == '1' ? 'checked' : '' }}>
                    Test modunu aktif et
                </label>
                <div class="form-hint">Gelistirme asamasinda test modunu acin</div>
            </div>

            <div class="form-group">
                <label class="form-label">PayTR Aktif</label>
                <label class="form-check">
                    <input type="checkbox" name="paytr_active" value="1" 
                           {{ old('paytr_active', \App\Models\Setting::get('paytr_active')) == '1' ? 'checked' : '' }}>
                    PayTR ile ödemeyi aktif et
                </label>
                <div class="form-hint">Müsteriler PayTR ile taksitli ödeme yapabilir</div>
            </div>

            <div class="form-group">
                <label class="form-label">Maksimum Taksit Sayisi</label>
                <select name="paytr_max_installment" class="form-control">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('paytr_max_installment', \App\Models\Setting::get('paytr_max_installment', '12')) == (string)$i ? 'selected' : '' }}>
                            {{ $i == 1 ? 'Tek Çekim' : $i . ' Taksit' }}
                        </option>
                    @endfor
                </select>
                <div class="form-hint">Müsterilerin seçebilecegi maksimum taksit sayisi</div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Ayarlari Kaydet
            </button>
        </form>
    </div>

    <!-- PayTR Durumu -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-pie" style="color: var(--primary); margin-right: 8px;"></i> PayTR Durumu</h3>
        </div>

        @php
            $merchantId = \App\Models\Setting::get('paytr_merchant_id');
            $merchantKey = \App\Models\Setting::get('paytr_merchant_key');
            $merchantSalt = \App\Models\Setting::get('paytr_merchant_salt');
            $isConfigured = $merchantId && $merchantKey && $merchantSalt;
        @endphp

        @if($isConfigured)
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> PayTR API bilgileri tanimlandi ve aktif.
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> PayTR API bilgileri eksik! Soldaki formu doldurun.
            </div>
        @endif
        
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-credit-card"></i>
            </div>
            <div>
                <div class="stat-value">{{ \App\Models\PaytrTransaction::count() }}</div>
                <div class="stat-label">Toplam Islem</div>
            </div>
        </div>

        <div style="margin-top: 16px;">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="stat-value">{{ \App\Models\PaytrTransaction::where('status', 'success')->count() }}</div>
                    <div class="stat-label">Basarili Ödeme</div>
                </div>
            </div>
        </div>

        <div style="margin-top: 16px;">
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <div class="stat-value">₺{{ number_format((float) \App\Models\PaytrTransaction::where('status', 'success')->sum('amount'), 2, ',', '.') }}</div>
                    <div class="stat-label">Toplam Ciro</div>
                </div>
            </div>
        </div>

        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
            <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">Hizli Islemler</h4>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('admin.paytr.index') }}" class="btn btn-outline">
                    <i class="fas fa-list"></i> Tüm Ödemeleri Gör
                </a>
                <a href="{{ route('admin.paytr.report') }}" class="btn btn-outline">
                    <i class="fas fa-chart-bar"></i> Raporlar
                </a>
                <a href="{{ route('admin.paytr.export') }}" class="btn btn-outline">
                    <i class="fas fa-download"></i> Excel Export
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bilgi Kartlari -->
<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-circle-info" style="color: var(--primary); margin-right: 8px;"></i> PayTR Kurulum Bilgileri</h3>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <div>
            <h4 style="font-size: 14px; font-weight: 600; color: var(--primary); margin-bottom: 8px;">
                <i class="fas fa-info-circle"></i> API Bilgileri Nasil Alinir?
            </h4>
            <ol style="font-size: 13px; line-height: 1.6; color: var(--text); padding-left: 20px;">
                <li>PayTR paneline girin</li>
                <li>"Ayarlar" > "API Ayarlari" bölümüne gidin</li>
                <li>Merchant ID, Key ve Salt bilgilerini kopyalayin</li>
                <li>Bu bilgileri yukaridaki forma girin</li>
            </ol>
        </div>

        <div>
            <h4 style="font-size: 14px; font-weight: 600; color: var(--primary); margin-bottom: 8px;">
                <i class="fas fa-shield-alt"></i> Güvenlik Notu
            </h4>
            <p style="font-size: 13px; line-height: 1.6; color: var(--text);">
                API bilgileriniz güvende tutulur. Bu bilgiler sadece PayTR ile iletisim kurmak için kullanilir ve 
                hiçbir sekilde üçüncü sahisla paylasilmaz.
            </p>
        </div>

        <div>
            <h4 style="font-size: 14px; font-weight: 600; color: var(--primary); margin-bottom: 8px;">
                <i class="fas fa-cog"></i> Callback URL
            </h4>
            <p style="font-size: 13px; line-height: 1.6; color: var(--text);">
                PayTR panelindeki callback URL olarak asagidakini girin:<br>
                <code style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-size: 12px; user-select: all;">
                    {{ url('/paytr/callback') }}
                </code>
            </p>
        </div>
    </div>
</div>
@endsection
