<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifremi Unuttum — BendyyYatak</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Inter',sans-serif;background:#fafaf8;min-height:100vh;display:flex;flex-direction:column;}
        .header{background:#1a1a2e;padding:16px 24px;text-align:center;}
        .logo{font-size:22px;font-weight:800;color:#fff;text-decoration:none;}
        .logo span{color:#c8a96e;}
        .auth-wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 16px;}
        .auth-card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:40px;width:100%;max-width:400px;}
        .auth-title{font-size:20px;font-weight:700;color:#1a1a2e;margin-bottom:8px;}
        .auth-sub{font-size:13px;color:#6b7280;margin-bottom:24px;line-height:1.6;}
        .form-group{margin-bottom:16px;}
        .form-label{display:block;font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:6px;}
        .form-control{width:100%;padding:11px 13px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13.5px;font-family:'Inter',sans-serif;color:#1a1a2e;}
        .form-control:focus{outline:none;border-color:#1a1a2e;}
        .btn-auth{width:100%;padding:13px;background:#1a1a2e;color:#fff;border:none;border-radius:9px;font-size:14px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;}
        .alert{padding:11px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;}
        .alert-success{background:#dcfce7;color:#15803d;}
        .alert-danger{background:#fee2e2;color:#b91c1c;}
        .back-link{text-align:center;margin-top:16px;font-size:13px;}
        .back-link a{color:#1a1a2e;font-weight:600;text-decoration:none;}
    </style>
</head>
<body>
<div class="header"><a href="/" class="logo">Bendyy<span>Yatak</span></a></div>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-title">Şifremi Unuttum</div>
        <div class="auth-sub">E-posta adresinizi girin, şifre sıfırlama bağlantısı gönderelim.</div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">E-Posta Adresi</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="ornek@email.com" required>
            </div>
            <button type="submit" class="btn-auth">Sıfırlama Bağlantısı Gönder</button>
        </form>
        <div class="back-link"><a href="{{ route('login') }}">← Giriş sayfasına dön</a></div>
    </div>
</div>
</body>
</html>
