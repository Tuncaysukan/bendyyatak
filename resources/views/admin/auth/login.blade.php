<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi — BendyyYatak</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(108,71,255,.15) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(59,130,246,.1) 0%, transparent 60%);
            pointer-events: none;
        }
        .login-card {
            background: rgba(255,255,255,.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 20px;
            padding: 48px 40px;
            width: 400px;
            position: relative;
            z-index: 1;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 36px;
        }
        .login-logo .icon {
            width: 60px;
            height: 60px;
            background: #6c47ff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: white;
            margin: 0 auto 16px;
        }
        .login-logo h1 { font-size: 22px; font-weight: 700; color: #fff; }
        .login-logo p { font-size: 13px; color: rgba(255,255,255,.5); margin-top: 4px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.7); margin-bottom: 7px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.4); font-size: 14px; }
        .form-control {
            width: 100%;
            padding: 12px 14px 12px 40px;
            background: rgba(255,255,255,.07);
            border: 1.5px solid rgba(255,255,255,.12);
            border-radius: 10px;
            font-size: 14px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            transition: border-color .2s;
        }
        .form-control::placeholder { color: rgba(255,255,255,.35); }
        .form-control:focus { outline: none; border-color: #6c47ff; background: rgba(108,71,255,.1); }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: #6c47ff;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all .2s;
            margin-top: 8px;
        }
        .btn-login:hover { background: #5535d4; transform: translateY(-1px); }
        .alert-danger {
            background: rgba(239,68,68,.15);
            border: 1px solid rgba(239,68,68,.3);
            border-radius: 8px;
            padding: 12px 16px;
            color: #fca5a5;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .back-link { text-align: center; margin-top: 24px; }
        .back-link a { color: rgba(255,255,255,.4); font-size: 12.5px; text-decoration: none; }
        .back-link a:hover { color: rgba(255,255,255,.7); }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">
        <div class="icon"><i class="fas fa-bed"></i></div>
        <h1>BendyyYatak</h1>
        <p>Admin Paneline Giriş Yapın</p>
    </div>

    @if($errors->any())
        <div class="alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">E-Posta Adresi</label>
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="admin@bendyyatak.com" required>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Şifre</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>
        <button type="submit" class="btn-login">
            <i class="fas fa-right-to-bracket"></i> Giriş Yap
        </button>
    </form>

    <div class="back-link">
        <a href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Siteye Geri Dön</a>
    </div>
</div>
</body>
</html>
