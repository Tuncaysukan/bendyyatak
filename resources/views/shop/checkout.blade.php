@extends('layouts.app')

@section('seo_title', 'Güvenli Ödeme — BendyyYatak')

@section('content')
<div class="container" style="padding-top:40px;padding-bottom:80px;">
    <h1 style="font-size:24px;font-weight:800;margin-bottom:32px;color:var(--text);">Güvenli Ödeme</h1>

    @if($errors->any())
        <div style="background:#fee2e2; color:#b91c1c; padding:16px; border-radius:10px; margin-bottom:24px;">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $err)
                    <li style="font-size:13.5px;">{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf
        <div style="display:grid; grid-template-columns:1fr 380px; gap:40px; align-items:start;">
            
            {{-- Sol Alan: Fatura & Teslimat ve Ödeme --}}
            <div>
                <!-- Form Bilgisi -->
                <div style="background:#fff; border:1px solid var(--border); border-radius:16px; padding:30px; margin-bottom:24px;">
                    <h2 style="font-size:18px; font-weight:700; margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid #f3f4f6;">1. Teslimat ve Fatura Bilgileri</h2>
                    
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Adınız *</label>
                            <input type="text" name="first_name" required value="{{ old('first_name', auth()->user() ? auth()->user()->name : '') }}" style="width:100%; padding:12px 14px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:14px; outline:none; transition:border-color .2s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                        <div>
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Soyadınız *</label>
                            <input type="text" name="last_name" required value="{{ old('last_name') }}" style="width:100%; padding:12px 14px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:14px; outline:none; transition:border-color .2s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">E-Posta *</label>
                            <input type="email" name="email" required value="{{ old('email', auth()->user() ? auth()->user()->email : '') }}" style="width:100%; padding:12px 14px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:14px; outline:none; transition:border-color .2s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                        <div>
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Telefon *</label>
                            <input type="text" name="phone" required value="{{ old('phone') }}" placeholder="05551234567" style="width:100%; padding:12px 14px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:14px; outline:none; transition:border-color .2s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">İl *</label>
                            <input type="text" name="city" required value="{{ old('city') }}" style="width:100%; padding:12px 14px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:14px; outline:none; transition:border-color .2s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                        <div>
                            <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">İlçe *</label>
                            <input type="text" name="district" required value="{{ old('district') }}" style="width:100%; padding:12px 14px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:14px; outline:none; transition:border-color .2s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                    </div>

                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:8px;">Açık Adres (Sokak, Mahalle, No, Daire) *</label>
                        <textarea name="address" required rows="3" style="width:100%; padding:12px 14px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:14px; outline:none; font-family:inherit; resize:vertical; transition:border-color .2s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e5e7eb'">{{ old('address') }}</textarea>
                    </div>
                </div>

                <!-- Ödeme Yöntemi -->
                <div style="background:#fff; border:1px solid var(--border); border-radius:16px; padding:30px;">
                    <h2 style="font-size:18px; font-weight:700; margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid #f3f4f6;">2. Ödeme Yöntemi Seçin</h2>

                    <div style="display:flex; flex-direction:column; gap:16px;">
                        @if($paymentIyzico)
                        <label style="border:1.5px solid #e5e7eb; border-radius:12px; padding:20px; display:flex; align-items:center; gap:16px; cursor:pointer; transition:all .2s; background:#fafafa;">
                            <input type="radio" name="payment_method" value="iyzico" checked style="width:20px; height:20px; accent-color:var(--primary);">
                            <div style="flex:1;">
                                <div style="font-weight:700; font-size:15px; color:#1a1a2e; margin-bottom:4px;">Kredi / Banka Kartı (İyzico 3D)</div>
                                <div style="font-size:12.5px; color:#6b7280;">Tüm Masterpass ve Visa kartlarıyla güvenli ödeme.</div>
                            </div>
                            <i class="far fa-credit-card" style="font-size:24px; color:#9ca3af;"></i>
                        </label>
                        @endif

                        @if($paymentBankTransfer)
                        <label style="border:1.5px solid #e5e7eb; border-radius:12px; padding:20px; display:flex; align-items:center; gap:16px; cursor:pointer; transition:all .2s;">
                            <input type="radio" name="payment_method" value="bank_transfer" style="width:20px; height:20px; accent-color:var(--primary);">
                            <div style="flex:1;">
                                <div style="font-weight:700; font-size:15px; color:#1a1a2e; margin-bottom:4px;">Havale / EFT (-5% Ek İndirim)</div>
                                <div style="font-size:12.5px; color:#6b7280;">Siparişi tamamladıktan sonra banka hesaplarımıza aktarım yapabilirsiniz.</div>
                            </div>
                            <i class="fas fa-money-bill-transfer" style="font-size:24px; color:#9ca3af;"></i>
                        </label>
                        @endif

                        @if($paymentCashOnDel)
                        <label style="border:1.5px solid #e5e7eb; border-radius:12px; padding:20px; display:flex; align-items:center; gap:16px; cursor:pointer; transition:all .2s;">
                            <input type="radio" name="payment_method" value="cash_on_delivery" style="width:20px; height:20px; accent-color:var(--primary);">
                            <div style="flex:1;">
                                <div style="font-weight:700; font-size:15px; color:#1a1a2e; margin-bottom:4px;">Kapıda Ödeme</div>
                                <div style="font-size:12.5px; color:#6b7280;">Ürün teslimatında nakit veya kart ile kuryeye ödeyin.</div>
                            </div>
                            <i class="fas fa-box-open" style="font-size:24px; color:#9ca3af;"></i>
                        </label>
                        @endif
                    </div>
                    
                    @if(empty($paymentIyzico) && empty($paymentBankTransfer) && empty($paymentCashOnDel))
                        <div style="color:#b91c1c; font-size:14px; padding:12px; background:#fee2e2; border-radius:8px;">Sistemde aktif ödeme yöntemi bulunmamaktadır.</div>
                    @endif
                </div>

            </div>

            {{-- Sağ Alan: Sipariş Özeti --}}
            <div style="position:sticky; top:90px;">
                <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:16px; padding:28px;">
                    <h3 style="font-size:16px; font-weight:700; margin-bottom:20px;">Siparişinizdeki Ürünler (<span style="color:var(--primary);">{{ array_sum(array_column($cart, 'quantity')) }}</span>)</h3>
                    
                    <!-- Ürün Listesi -->
                    <div style="display:flex; flex-direction:column; gap:16px; margin-bottom:24px; max-height:260px; overflow-y:auto; padding-right:8px;">
                        @foreach($cart as $item)
                        <div style="display:flex; gap:12px; align-items:center;">
                            <img src="{{ $item['image'] ?? asset('images/placeholder.jpg') }}" alt="{{ $item['name'] }}" style="width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid #eee;">
                            <div style="flex:1; min-width:0;">
                                <div style="font-weight:600; font-size:13.5px; color:#1a1a2e; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item['name'] }}</div>
                                @if($item['variant_name'])
                                    <div style="font-size:11px; color:#6b7280; margin-top:2px;">{{ $item['variant_name'] }}</div>
                                @endif
                                <div style="font-size:11.5px; color:#6b7280; margin-top:4px;">{{ $item['quantity'] }} adet x ₺{{ number_format($item['price'], 2, ',', '.') }}</div>
                            </div>
                            <div style="font-weight:700; font-size:14px; color:#1a1a2e;">₺{{ number_format($item['total'], 2, ',', '.') }}</div>
                        </div>
                        @endforeach
                    </div>

                    <div style="border-top:1px solid #e5e7eb; padding-top:20px; display:flex; flex-direction:column; gap:12px;">
                        <div style="display:flex; justify-content:space-between; font-size:14px;">
                            <span style="color:#4b5563;">Ara Toplam</span>
                            <span style="font-weight:600;">₺{{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>

                        <div style="display:flex; justify-content:space-between; font-size:14px;">
                            <span style="color:#4b5563;">Kargo Ücreti</span>
                            @if($shippingCost > 0)
                                <span style="font-weight:600;">₺{{ number_format($shippingCost, 2, ',', '.') }}</span>
                            @else
                                <span style="font-weight:600; color:#10b981;">Ücretsiz</span>
                            @endif
                        </div>

                        @if($coupon)
                        <div style="display:flex; justify-content:space-between; font-size:14px; color:#15803d;">
                            <span>İndirim ({{ $coupon['code'] }})</span>
                            <span style="font-weight:600;">-₺{{ number_format($discount, 2, ',', '.') }}</span>
                        </div>
                        @endif

                        <div style="display:flex; justify-content:space-between; font-size:18px; font-weight:800; color:#1a1a2e; margin-top:12px; padding-top:16px; border-top:1px solid #e5e7eb;">
                            <span>Ödenecek Tutar</span>
                            <span>₺{{ number_format($total, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <p style="font-size:11.5px; color:#9ca3af; text-align:center; margin-top:24px; line-height:1.5;">
                        Siparişi onaylayarak, <a href="{{ route('page.terms') }}" target="_blank" style="color:var(--primary); text-decoration:underline;">Mesafeli Satış Sözleşmesi</a> ve <a href="{{ route('page.privacy') }}" target="_blank" style="color:var(--primary); text-decoration:underline;">Ön Bilgilendirme Formu</a>'nu okuyup kabul ettiğinizi beyan edersiniz.
                    </p>

                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; font-size:16px; margin-top:20px; border-radius:12px; padding:18px;">
                        Siparişi Tamamla <i class="fas fa-shield-check" style="margin-left:8px;"></i>
                    </button>

                    <div style="display:flex; justify-content:center; gap:8px; margin-top:16px; opacity:0.6;">
                        <i class="fab fa-cc-mastercard" style="font-size:24px;"></i>
                        <i class="fab fa-cc-visa" style="font-size:24px;"></i>
                        <i class="fas fa-lock" style="font-size:24px;"></i>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    // Radio button seçim stilini değiştiren script
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="payment_method"]');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('label[style*="border"]').forEach(label => {
                    label.style.background = 'transparent';
                    label.style.borderColor = '#e5e7eb';
                });
                if(this.checked) {
                    this.parentElement.style.background = '#fafafa';
                    this.parentElement.style.borderColor = 'var(--primary)';
                }
            });
        });
        
        // Form gönderilirken buton durumunu kitletme (Double submit prevention)
        const form = document.getElementById('checkout-form');
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> İşleniyor...';
            btn.disabled = true;
            btn.style.opacity = '0.7';
        });
    });
</script>
@endsection
