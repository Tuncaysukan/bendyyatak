@extends('layouts.app')
@section('title', 'Kargo ve İade Koşulları | BendyyYatak')

@section('content')
<div class="container" style="max-width: 800px; margin: 60px auto; min-height: 50vh;">
    <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 24px; color: var(--primary);">Kargo ve İade Koşulları</h1>
    <div style="line-height: 1.8; color: var(--text); font-size: 15px;">
        <h3 style="font-size: 20px; font-weight: 700; margin: 32px 0 16px;">Teslimat Süreci</h3>
        <p style="margin-bottom: 16px;">Siparişleriniz, onaylandıktan sonra stok durumuna göre 3 ile 7 iş günü içerisinde kargo firmasına teslim edilmektedir.</p>
        <p style="margin-bottom: 16px;">Hacimli ürünlerde (Yatak, Baza vb.) lojistik firmaları ile çalışıldığı için teslimat süresi bölgeye göre farklılık gösterebilir. Lojistik yetkilileri teslimattan önce sizi arayarak randevu oluşturmaktadır.</p>
        
        <h3 style="font-size: 20px; font-weight: 700; margin: 32px 0 16px;">120 Gün Deneme ve İade Şartları</h3>
        <p style="margin-bottom: 16px;">BendyyYatak ürünlerinizi evinizde kendi ortamınızda 120 güne kadar deneyebilirsiniz.</p>
        <ul style="margin-left: 24px; margin-bottom: 16px;">
            <li style="margin-bottom: 8px;">Değişim veya iade taleplerinizi faturası ile birlikte yapmanız gerekmektedir.</li>
            <li style="margin-bottom: 8px;">Ürünün kullanım talimatlarına aykırı davranılmamış, yırtılmış veya dış etkenlerce zarar görmemiş olması gerekmektedir.</li>
            <li style="margin-bottom: 8px;">Özel ölçülü siparişlerde ne yazık ki iade işlemi yapılamamaktadır.</li>
        </ul>
        <p>İade ve değişim süreçleri ile ilgili daha fazla bilgi için bizimle iletişime geçebilirsiniz.</p>
    </div>
</div>
@endsection
