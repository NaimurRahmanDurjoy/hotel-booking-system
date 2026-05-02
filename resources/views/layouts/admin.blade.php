<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Hotel Royale Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-hotel"></i> Royale
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="sidebar-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </li>
            
            @if(Auth::user()->isAdmin())
            <li class="sidebar-item">
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> User Management
                </a>
            </li>
            @endif

            <li class="sidebar-item">
                <a href="{{ Auth::user()->isAdmin() ? route('admin.bookings') : route('manager.dashboard') }}" class="sidebar-link {{ request()->routeIs('*.bookings') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Bookings
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('manager.rooms.index') }}" class="sidebar-link {{ request()->routeIs('*.rooms.*') ? 'active' : '' }}">
                    <i class="fas fa-door-open"></i> Rooms
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('manager.services.index') }}" class="sidebar-link {{ request()->routeIs('*.services.*') ? 'active' : '' }}">
                    <i class="fas fa-concierge-bell"></i> Services
                </a>
            </li>

            <li class="sidebar-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="sidebar-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header class="admin-header">
            <div class="header-left">
                <h2>@yield('header_title', 'Dashboard')</h2>
            </div>
            <div class="header-right">
                <div class="user-profile">
                    <span>Welcome, <strong>{{ Auth::user()->name }}</strong> ({{ ucfirst(Auth::user()->role) }})</span>
                </div>
            </div>
        </header>

        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
