<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - The Grand Azure Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <a href="/" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-hotel"></i> Azure
            </a>
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
            <li class="sidebar-item">
                <a href="{{ route('admin.premium_plans') }}" class="sidebar-link {{ request()->routeIs('admin.premium_plans') ? 'active' : '' }}">
                    <i class="fas fa-crown"></i> Premium Plans
                </a>
            </li>
            @endif

            <li class="sidebar-item">
                <a href="{{ route('manager.hotels.index') }}" class="sidebar-link {{ request()->routeIs('*.hotels.*') ? 'active' : '' }}">
                    <i class="fas fa-hotel"></i> My Hotels
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ Auth::user()->isAdmin() ? route('admin.bookings') : route('manager.bookings') }}" class="sidebar-link {{ request()->routeIs('*.bookings') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Bookings
                </a>
            </li>

            @if(Auth::user()->isAdmin())
            <li class="sidebar-item">
                <a href="{{ route('manager.travel-packages.index') }}" class="sidebar-link {{ request()->routeIs('*.travel-packages.*') ? 'active' : '' }}">
                    <i class="fas fa-map-marked-alt"></i> Travel Packages
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('manager.cars.index') }}" class="sidebar-link {{ request()->routeIs('*.cars.*') ? 'active' : '' }}">
                    <i class="fas fa-car"></i> Car Rentals
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('manager.car_bookings.index') }}" class="sidebar-link {{ request()->routeIs('*.car_bookings.*') ? 'active' : '' }}">
                    <i class="fas fa-key"></i> Car Bookings
                </a>
            </li>

            <li class="sidebar-item">
                <a href="{{ route('manager.travel_bookings.index') }}" class="sidebar-link {{ request()->routeIs('*.travel_bookings.*') ? 'active' : '' }}">
                    <i class="fas fa-route"></i> Tour Bookings
                </a>
            </li>
            @endif

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
                <a href="{{ route('manager.chat') }}" class="sidebar-link {{ request()->routeIs('manager.chat') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i> Guest Messages
                </a>
            </li>

            <li class="sidebar-item">
                <form id="admin-logout-form" method="POST" action="{{ route('logout') }}" onsubmit="localStorage.removeItem('auth_token');">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" class="sidebar-link">
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

    <script>
        // Synchronize API auth_token in localStorage for authenticated web users
        document.addEventListener('DOMContentLoaded', function() {
            const loggedInUserId = '{{ Auth::id() }}';
            const storedUserId = localStorage.getItem('auth_user_id');
            const storedToken = localStorage.getItem('auth_token');

            if (!storedToken || storedUserId !== loggedInUserId) {
                fetch('{{ route("get-token") }}')
                    .then(response => {
                        if (response.ok) return response.json();
                        throw new Error('Failed to get API token');
                    })
                    .then(data => {
                        if (data.token) {
                            localStorage.setItem('auth_token', data.token);
                            localStorage.setItem('auth_user_id', loggedInUserId);
                            console.log('API Token synchronized successfully.');
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error syncing API token:', error));
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
