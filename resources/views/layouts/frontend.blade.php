<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-navy: #1E3A5F;
            --accent-gold: #F5A623;
        }
        body { font-family: 'Outfit', sans-serif; }
        .navbar { background-color: #fff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        .hero-bg {
            background: linear-gradient(rgba(30, 58, 95, 0.8), rgba(30, 58, 95, 0.8)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920');
            background-size: cover;
            background-position: center;
        }
        .footer { background-color: #1a1a1a; color: #fff; }
        @yield('styles')
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/"><i class="fas fa-hotel me-2"></i>Luxury Hotel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/#rooms">Rooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#contact">Contact</a></li>
                </ul>
                
                @guest
                <div class="d-flex ms-3">
                    <button onclick="showLoginModal()" class="btn btn-primary">Login</button>
                    <button onclick="showRegisterModal()" class="btn btn-outline-primary ms-2">Register</button>
                </div>
                @endguest

                @auth
                <div class="dropdown ms-3">
                    <button class="btn btn-link dropdown-toggle text-decoration-none text-dark" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i><span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu">
                        @if(Auth::user()->isAdmin())
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                        @elseif(Auth::user()->isManager())
                            <li><a class="dropdown-item" href="{{ route('manager.dashboard') }}">Manager Panel</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('bookings.index') }}">My Bookings</a></li>
                        <li><a class="dropdown-item" href="{{ route('chat') }}">Messages</a></li>
                        <li><a class="dropdown-item" href="{{ route('premium') }}">Premium Membership</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <div style="margin-top: 80px;">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3"><h5>Luxury Hotel</h5><p class="text-muted">Experience world-class hospitality</p></div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/#rooms" class="text-muted text-decoration-none">Rooms</a></li>
                        <li><a href="/#services" class="text-muted text-decoration-none">Services</a></li>
                        <li><a href="/#about" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="/#contact" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-map-marker-alt me-2"></i>123 Hotel Street, City</li>
                        <li><i class="fas fa-phone me-2"></i>+1 234 567 890</li>
                        <li><i class="fas fa-envelope me-2"></i>info@luxuryhotel.com</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Follow Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-muted"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-instagram fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="text-center text-muted"><p>&copy; 2024 Luxury Hotel. All rights reserved.</p></div>
        </div>
    </footer>

    @guest
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Login</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="loginForm">
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" id="loginEmail" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password" id="loginPassword" class="form-control" required></div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Register</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="registerForm">
                        <div class="mb-3"><label class="form-label">Name</label><input type="text" id="registerName" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" id="registerEmail" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password" id="registerPassword" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Confirm Password</label><input type="password" id="registerPasswordConfirmation" class="form-control" required></div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        @guest
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
        function showLoginModal() { loginModal.show(); }
        function showRegisterModal() { registerModal.show(); }

        document.getElementById('loginForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const response = await fetch('/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ email, password })
            });
            if (response.ok) { location.reload(); } else { alert('Login failed'); }
        });

        document.getElementById('registerForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const password_confirmation = document.getElementById('registerPasswordConfirmation').value;
            const response = await fetch('/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ name, email, password, password_confirmation })
            });
            if (response.ok) { location.reload(); } else { alert('Registration failed'); }
        });
        @endguest
    </script>
    @yield('scripts')
</body>
</html>
