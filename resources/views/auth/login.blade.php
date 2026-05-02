<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Grand Azure</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-navy: #1E3A5F;
            --accent-gold: #C5A059;
            --white: #FFFFFF;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(rgba(30, 58, 95, 0.8), rgba(30, 58, 95, 0.8)), url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1600');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            text-align: center;
            color: white;
            animation: fadeIn 0.8s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .brand {
            font-size: 2.2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .brand i { color: var(--accent-gold); margin-right: 10px; }
        .sub-title { color: rgba(255,255,255,0.7); font-size: 0.9rem; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 2px; }
        
        .form-group { text-align: left; margin-bottom: 25px; position: relative; }
        .form-group i { position: absolute; left: 15px; top: 43px; color: var(--accent-gold); }
        
        label { display: block; margin-bottom: 10px; font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.8); text-transform: uppercase; letter-spacing: 1px; }
        
        input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-sizing: border-box;
            font-size: 1rem;
            color: white;
            transition: all 0.3s ease;
        }
        input::placeholder { color: rgba(255,255,255,0.4); }
        input:focus { border-color: var(--accent-gold); outline: none; background: rgba(255, 255, 255, 0.15); }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: var(--accent-gold);
            color: var(--primary-navy);
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 10px 20px rgba(197, 160, 89, 0.3);
        }
        .btn-login:hover { background: #d4ae6a; transform: translateY(-2px); box-shadow: 0 15px 30px rgba(197, 160, 89, 0.4); }
        
        .error { color: #ff6b6b; font-size: 0.85rem; margin-top: 15px; background: rgba(255, 107, 107, 0.1); padding: 10px; border-radius: 10px; border: 1px solid rgba(255, 107, 107, 0.2); }
        
        .footer { margin-top: 30px; font-size: 0.9rem; color: rgba(255,255,255,0.6); }
        .footer a { color: var(--accent-gold); text-decoration: none; font-weight: 700; transition: color 0.3s; }
        .footer a:hover { color: white; }

        .back-home { position: absolute; top: 30px; left: 30px; color: white; text-decoration: none; font-weight: 600; font-size: 0.9rem; }
        .back-home:hover { color: var(--accent-gold); }
    </style>
</head>
<body>
    <a href="/" class="back-home"><i class="fas fa-arrow-left me-2"></i> Back to Website</a>
    
    <div class="login-card">
        <div class="brand"><i class="fas fa-hotel"></i>THE GRAND AZURE</div>
        <div class="sub-title">Luxury Guest Portal</div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" required placeholder="Enter your email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label>Password</label>
                <i class="fas fa-lock"></i>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            
            @if($errors->any())
                <div class="error"><i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}</div>
            @endif
            
            <button type="submit" class="btn-login">SIGN IN</button>
        </form>
        
        <div class="footer">
            New to our resort? <a href="{{ route('register') }}">Join Elite Club</a>
        </div>
    </div>
</body>
</html>
