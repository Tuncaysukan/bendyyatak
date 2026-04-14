@extends('layouts.app')

@section('seo_title', 'Ödeme Sayfas')
@section('content')
<div class="container" style="max-width: 800px; margin: 40px auto;">
    <div class="card" style="background: white; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 24px; border-bottom: 1px solid #eee;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 700; color: var(--primary);">
                <i class="fas fa-lock" style="color: var(--accent); margin-right: 8px;"></i>
                Güvenli Ödeme
            </h2>
            <p style="margin: 8px 0 0 0; color: var(--text-muted); font-size: 14px;">
                Ödeme bilgileriniz SSL sertifikasý ile korunmaktadýr.
            </p>
        </div>
        
        <div style="padding: 24px;">
            <div id="paytr-iframe-container" style="min-height: 600px;">
                <div style="display: flex; align-items: center; justify-content: center; min-height: 400px;">
                    <div style="text-align: center;">
                        <div style="width: 40px; height: 40px; border: 4px solid var(--accent); border-top: 4px solid transparent; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 16px;"></div>
                        <p style="color: var(--text-muted); font-size: 14px;">Ödeme formu yükleniyor...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const iframeContainer = document.getElementById('paytr-iframe-container');
    const token = '{{ $token }}';
    
    if (token) {
        // PayTR iframe'ini yükle
        const iframe = document.createElement('iframe');
        iframe.src = 'https://www.paytr.com/odeme/guvenli/' + token;
        iframe.style.width = '100%';
        iframe.style.height = '600px';
        iframe.style.border = 'none';
        iframe.style.borderRadius = '8px';
        
        iframe.onload = function() {
            // Iframe yüklendiðinde spinner'ý gizle
            iframeContainer.innerHTML = '';
            iframeContainer.appendChild(iframe);
        };
        
        iframe.onerror = function() {
            iframeContainer.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <div style="color: #ef4444; font-size: 48px; margin-bottom: 16px;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 style="color: var(--primary); margin-bottom: 8px;">Ödeme Formu Yüklenemedi</h3>
                    <p style="color: var(--text-muted); margin-bottom: 20px;">Lütfen internet baðlantýnýzý kontrol edin ve tekrar deneyin.</p>
                    <button onclick="window.location.reload()" class="btn btn-primary">
                        <i class="fas fa-refresh"></i> Tekrar Dene
                    </button>
                </div>
            `;
        };
        
        // Iframe'i ekle (timeout güvenlik önlemi)
        setTimeout(() => {
            if (iframeContainer.querySelector('div')) {
                iframeContainer.innerHTML = '';
                iframeContainer.appendChild(iframe);
            }
        }, 2000);
    } else {
        iframeContainer.innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <div style="color: #ef4444; font-size: 48px; margin-bottom: 16px;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h3 style="color: var(--primary); margin-bottom: 8px;">Ödeme Baþlatýlamadý</h3>
                <p style="color: var(--text-muted); margin-bottom: 20px;">Geçersiz ödeme tokený. Lütfen sipariþinizi tekrar oluþturun.</p>
                <a href="{{ route('cart.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Sepete Geri Dön
                </a>
            </div>
        `;
    }
});

// Ödeme sonucu dinleyici (iframe'den gelen mesajlar)
window.addEventListener('message', function(event) {
    // PayTR domain kontrolü
    if (event.origin !== 'https://www.paytr.com') return;
    
    try {
        const data = typeof event.data === 'string' ? JSON.parse(event.data) : event.data;
        
        if (data.status === 'success') {
            // Ödeme baþarýlý - sipariþ sayfasýna yönlendir
            window.location.href = '{{ route("checkout.success", ["orderNo" => "ORDER_NO"]) }}'.replace('ORDER_NO', data.orderNo || '');
        } else if (data.status === 'failed' || data.status === 'cancel') {
            // Ödeme baþarýsýz - ödeme sayfasýna yönlendir
            window.location.href = '{{ route("checkout.failed") }}';
        }
    } catch (e) {
        console.error('PayTR callback error:', e);
    }
});
</script>
@endsection
