@extends('layouts.app')
@section('title', 'İletişim')

@section('content')
<div class="container" style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    <h1 style="text-align: center; margin-bottom: 40px; font-size: 32px; color: var(--text);">İletişim</h1>

    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        <!-- İletişim Formu -->
        <div style="flex: 1; min-width: 300px; background: white; border: 1px solid #eee; border-radius: 8px; padding: 30px;">
            <h2 style="font-size: 24px; margin-bottom: 20px;">Bize Ulaşın</h2>
            
            @if(session('success'))
                <div style="background:#dcfce7; color:#15803d; padding:12px; border-radius:6px; margin-bottom:20px;">{{ session('success') }}</div>
            @endif

            <form action="{{ route('page.contact.post') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display:block; font-weight:600; font-size:14px; margin-bottom:8px;">Ad Soyad *</label>
                    <input type="text" name="name" required style="width:100%; padding:10px 14px; border:1px solid #ccc; border-radius:6px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display:block; font-weight:600; font-size:14px; margin-bottom:8px;">E-Posta *</label>
                    <input type="email" name="email" required style="width:100%; padding:10px 14px; border:1px solid #ccc; border-radius:6px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display:block; font-weight:600; font-size:14px; margin-bottom:8px;">Konu *</label>
                    <input type="text" name="subject" required style="width:100%; padding:10px 14px; border:1px solid #ccc; border-radius:6px;">
                </div>
                <div style="margin-bottom: 24px;">
                    <label style="display:block; font-weight:600; font-size:14px; margin-bottom:8px;">Mesajınız *</label>
                    <textarea name="message" rows="5" required style="width:100%; padding:10px 14px; border:1px solid #ccc; border-radius:6px; resize:vertical;"></textarea>
                </div>
                <button type="submit" style="background:var(--primary); color:white; padding:12px 24px; border:none; border-radius:6px; font-weight:bold; cursor:pointer; width:100%;">
                    Mesajı Gönder
                </button>
            </form>
        </div>

        <!-- İletişim Bilgileri -->
        <div style="width: 350px; flex-shrink: 0; background: #f9fafb; border-radius: 8px; padding: 30px;">
            <h3 style="font-size: 20px; margin-bottom: 24px;">İletişim Bilgilerimiz</h3>
            
            <div style="margin-bottom: 20px; display: flex; gap: 12px;">
                <i class="fas fa-envelope" style="color: var(--primary); font-size: 20px; margin-top: 2px;"></i>
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">E-Posta</div>
                    <a href="mailto:{{ App\Models\Setting::get('contact_email') }}" style="color: #666; text-decoration: none;">{{ App\Models\Setting::get('contact_email', 'info@bendyy.com') }}</a>
                </div>
            </div>
            
            <div style="margin-bottom: 20px; display: flex; gap: 12px;">
                <i class="fas fa-phone" style="color: var(--primary); font-size: 20px; margin-top: 2px;"></i>
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">Telefon</div>
                    <a href="tel:{{ App\Models\Setting::get('contact_phone') }}" style="color: #666; text-decoration: none;">{{ App\Models\Setting::get('contact_phone', '0850 123 45 67') }}</a>
                </div>
            </div>
            
            <div style="margin-bottom: 24px; display: flex; gap: 12px;">
                <i class="fas fa-map-marker-alt" style="color: var(--primary); font-size: 20px; margin-top: 2px;"></i>
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">Adres</div>
                    <div style="color: #666; line-height: 1.5;">{{ App\Models\Setting::get('contact_address', 'Merkez Mah. Yatak Cad. No:1 İstanbul') }}</div>
                </div>
            </div>
            
            @if(App\Models\Setting::get('contact_map_iframe'))
            <div style="border-radius: 8px; overflow: hidden; height: 200px; width: 100%;">
                {!! App\Models\Setting::get('contact_map_iframe') !!}
            </div>
            <style>
                /* Iframe'in kutuya tam sığmasını sağlamak için */
                iframe { width: 100% !important; height: 100% !important; border: 0; }
            </style>
            @endif
        </div>
    </div>
</div>
@endsection
