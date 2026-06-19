<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - ELEDJI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #1C1C1C;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-logo {
            width: 70px;
            height: 70px;
            background: #C0392B;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: white;
            font-weight: 800;
            font-size: 18px;
        }
        .login-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 8px;
        }
        .login-subtitle {
            text-align: center;
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 32px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-color: #C0392B;
            box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.1);
        }
        .form-input.error {
            border-color: #EF4444;
        }
        .error-message {
            color: #EF4444;
            font-size: 12px;
            margin-top: 6px;
        }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #C0392B;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-login:hover {
            background: #a32f23;
        }
        .back-link {
            text-align: center;
            margin-top: 24px;
        }
        .back-link a {
            color: #6B7280;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link a:hover {
            color: #C0392B;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">EL<span style="font-size:10px">edji</span></div>
        <h1 class="login-title">Accès Admin</h1>
        <p class="login-subtitle">Connectez-vous au panel d'administration</p>

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf

            @if($errors->any())
                <div style="background: #FEE2E2; color: #991B1B; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" placeholder="admin@eledji.com" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
        </form>

        <div class="back-link">
            <a href="{{ route('home') }}">← Retour au site</a>
        </div>
    </div>
</body>
</html>