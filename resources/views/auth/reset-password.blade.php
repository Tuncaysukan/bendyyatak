<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre Sıfırla — BendyyYatak</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Inter',sans-serif;background:#fafaf8;min-height:100vh;display:flex;flex-direction:column;}
        .header{background:#1a1a2e;padding:16px 24px;text-align:center;}
        .logo{font-size:22px;font-weight:800;color:#fff;text-decoration:none;}
        .logo span{color:#c8a96e;}
        .auth-wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 16px;}
        .auth-card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:40px;width:100%;max-width:400px;}
        .auth-title{font-size:20px;font-weight:700;color:#1a1a2e;margin-bottom:24px;}
        .form-group{margin-bottom:16px;}
        .form-label{display:block;font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:6px;}
        .form-control{width:100%;padding:11px 13px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13.5px;font-family:'Inter',sans-serif;}
        .form-control:focus{outline:none;border-color:#1a1a2e;}
        .btn-auth{width:100%;padding:13px;background:#1a1a2e;color:#fff;border:none;border-radius:9px;font-size:14px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;}
        .alert-danger{background:#fee2e2;border-radius:8px;padding:11px 14px;color:#b91c1c;font-size:13px;margin-bottom:16px;}
    </style>
</head>
<body>
<div class="header"><a href="/" class="logo">Bendyy<span>Yatak</span></a></div>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-title">Yeni Şifre Belirle</div>

        @if($errors->any())
            <div class="alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="form-group">
                <label class="form-label">E-Posta Adresi</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Yeni Şifre</label>
                <input type="password" name="password" class="form-control" placeholder="En az 8 karakter" required>
            </div>
            <div class="form-group">
                <label class="form-label">Yeni Şifre Tekrar</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn-auth">Şifremi Sıfırla</button>
        </form>
    </div>
</div>
</body>
</html>
