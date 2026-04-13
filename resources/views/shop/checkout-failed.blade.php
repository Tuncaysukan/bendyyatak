@extends('layouts.app')

@section('seo_title', 'Ödeme Başarısız — BendyyYatak')

@section('content')
<div class="container" style="padding-top:60px;padding-bottom:100px; max-width: 800px; text-align: center;">
    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:60px 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
        
        <div style="width: 80px; height: 80px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <i class="fas fa-times" style="font-size: 40px; color: #b91c1c;"></i>
        </div>

        <h1 style="font-size:32px; font-weight:800; margin-bottom:16px; color:var(--text);">Ödeme Başarısız Oldu</h1>
        <p style="font-size: 16px; color: #6b7280; line-height: 1.6; margin-bottom: 32px;">
            Ödeme işleminiz sırasında bir hata oluştu. Lütfen bilgilerinizi kontrol edip tekrar deneyin veya farklı bir ödeme yöntemi seçin.
        </p>

        <div style="display: flex; gap: 16px; justify-content: center;">
            <a href="{{ route('checkout.index') }}" class="btn btn-primary" style="padding: 14px 28px; border-radius: 99px;">
                <i class="fas fa-undo"></i> Tekrar Dene
            </a>
            <a href="{{ route('cart.index') }}" class="btn btn-outline" style="padding: 14px 28px; border-radius: 99px;">
                Sepete Dön
            </a>
        </div>
    </div>
</div>
@endsection
