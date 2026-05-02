<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royale Hotel</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; margin: 0; background: #F8F9FA; }
        nav { background: #1E3A5F; padding: 20px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.5rem; font-weight: 700; }
        .logo span { color: #F5A623; }
        .nav-links a { color: white; text-decoration: none; margin-left: 20px; }
    </style>
</head>
<body>
    <nav>
        <div class="logo">Royale<span>Hotel</span></div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('rooms.browse') }}">Rooms</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
            </form>
        </div>
    </nav>
    @yield('content')
</body>
</html>