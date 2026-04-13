<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol — BendyyYatak</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Inter',sans-serif;background:#fafaf8;min-height:100vh;display:flex;flex-direction:column;}
        .header{background:#1a1a2e;padding:16px 24px;display:flex;align-items:center;justify-content:center;}
        .logo{font-size:22px;font-weight:800;color:#fff;text-decoration:none;}
        .logo span{color:#c8a96e;}
        .auth-wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 16px;}
        .auth-card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:40px;width:100%;max-width:420px;}
        .auth-title{font-size:22px;font-weight:700;color:#1a1a2e;margin-bottom:6px;}
        .auth-sub{font-size:13px;color:#6b7280;margin-bottom:28px;}
        .form-group{margin-bottom:16px;}
        .form-label{display:block;font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:6px;}
        .input-wrap{position:relative;}
        .input-wrap i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:14px;}
        .form-control{width:100%;padding:11px 13px 11px 38px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13.5px;font-family:'Inter',sans-serif;color:#1a1a2e;transition:border-color .2s;}
        .form-control:focus{outline:none;border-color:#1a1a2e;}
        .btn-auth{width:100%;padding:13px;background:#1a1a2e;color:#fff;border:none;border-radius:9px;font-size:14px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:background .2s;margin-top:4px;}
        .btn-auth:hover{background:#c8a96e;}
        .auth-footer{text-align:center;margin-top:20px;font-size:13px;color:#6b7280;}
        .auth-footer a{color:#1a1a2e;font-weight:600;text-decoration:none;}
        .alert-danger{background:#fee2e2;border:1px solid #fecaca;border-radius:8px;padding:11px 14px;color:#b91c1c;font-size:13px;margin-bottom:16px;}
    </style>
</head>
<body>
<div class="header">
    <a href="/" class="logo">Bendyy<span>Yatak</span></a>
</div>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-title">Hesap Oluştur</div>
        <div class="auth-sub">Ücretsiz kayıt olun, alışverişe başlayın</div>

        @if($errors->any())
            <div class="alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Ad Soyad</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ad Soyad" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">E-Posta Adresi</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="ornek@email.com" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Telefon (İsteğe bağlı)</label>
                <div class="input-wrap">
                    <i class="fas fa-phone"></i>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="05xx xxx xx xx">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Şifre</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="En az 8 karakter" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Şifre Tekrar</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Şifreyi tekrar girin" required>
                </div>
            </div>
            <button type="submit" class="btn-auth">Kayıt Ol</button>
        </form>

        <div class="auth-footer">
            Zaten hesabınız var mı? <a href="{{ route('login') }}">Giriş Yapın</a>
        </div>
    </div>
</div>
</body>
</html>
