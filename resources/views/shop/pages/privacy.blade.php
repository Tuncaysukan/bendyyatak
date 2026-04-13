@extends('layouts.app')
@section('title', 'Gizlilik Sözleşmesi | BendyyYatak')

@section('content')
<div class="container" style="max-width: 800px; margin: 60px auto; min-height: 50vh;">
    <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 24px; color: var(--primary);">Gizlilik Sözleşmesi</h1>
    <div style="line-height: 1.8; color: var(--text); font-size: 15px;">
        <h3 style="font-size: 20px; font-weight: 700; margin: 32px 0 16px;">1. Bilgilerin Toplanması</h3>
        <p style="margin-bottom: 16px;">Sitemize üye olurken, sipariş verirken veya bültenimize abone olurken isim, e-posta adresi, telefon numarası ve adres gibi temel iletişim bilgilerinizi bizimle paylaşırsınız. Bu bilgiler, size daha iyi hizmet verebilmek amacıyla toplanmaktadır.</p>
        
        <h3 style="font-size: 20px; font-weight: 700; margin: 32px 0 16px;">2. Bilgilerin Kullanımı</h3>
        <p style="margin-bottom: 16px;">Toplanan bilgiler şu amaçlarla kullanılır:</p>
        <ul style="margin-left: 24px; margin-bottom: 16px;">
            <li style="margin-bottom: 8px;">Siparişlerinizi işleme almak ve teslimatınızı sağlamak.</li>
            <li style="margin-bottom: 8px;">Sizinle iletişime geçmek ve müşteri desteği sağlamak.</li>
            <li style="margin-bottom: 8px;">Kişiselleştirilmiş alışveriş deneyimi sunmak.</li>
        </ul>
        
        <h3 style="font-size: 20px; font-weight: 700; margin: 32px 0 16px;">3. Bilgilerin Güvenliği</h3>
        <p style="margin-bottom: 16px;">BendyyYatak, kredi kartı bilgileriniz de dâhil olmak üzere tüm kişisel verilerinizi SSL (Secure Socket Layer) teknolojisi ile şifreler. Bilgileriniz hiçbir koşulda üçüncü şahıslara veya şirketlere satılmaz.</p>
    </div>
</div>
@endsection
