<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-bg {
            background: linear-gradient(rgba(30, 58, 95, 0.8), rgba(30, 58, 95, 0.8)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920');
            background-size: cover;
            background-position: center;
            height: 100vh;
        }
        .room-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .room-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); }
        .premium-section { background: linear-gradient(135deg, #1E3A5F 0%, #2c5282 100%); }
        .navbar { background-color: #fff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#"><i class="fas fa-hotel me-2"></i>Luxury Hotel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#rooms">Rooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <div id="authButtons" class="d-flex ms-3">
                    <button onclick="showLoginModal()" class="btn btn-primary">Login</button>
                    <button onclick="showRegisterModal()" class="btn btn-outline-primary ms-2">Register</button>
                </div>
                <div id="userMenu" class="dropdown ms-3" style="display: none;">
                    <button class="btn btn-link dropdown-toggle text-decoration-none text-dark" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i><span id="userName">User</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                        <li><a class="dropdown-item" href="/bookings">My Bookings</a></li>
                        <li><a class="dropdown-item" href="/chat">Messages</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button onclick="logout()" class="dropdown-item text-danger">Logout</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg d-flex align-items-center">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-4">Welcome to Luxury Hotel</h1>
            <p class="lead mb-5">Experience world-class hospitality and comfort</p>
            <div class="bg-white rounded p-4" style="max-width: 900px; margin: 0 auto;">
                <form id="searchForm" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-dark">Check-in</label>
                        <input type="date" id="checkIn" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-dark">Check-out</label>
                        <input type="date" id="checkOut" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-dark">Guests</label>
                        <select id="guests" class="form-select">
                            <option value="1">1 Guest</option>
                            <option value="2" selected>2 Guests</option>
                            <option value="3">3 Guests</option>
                            <option value="4">4 Guests</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Search</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Rooms Section -->
    <section id="rooms" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark">Our Rooms</h2>
                <p class="text-muted">Choose from our selection of luxurious rooms</p>
            </div>
            <div id="roomsGrid" class="row g-4"></div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark">Our Services</h2>
                <p class="text-muted">Enhance your stay with our premium services</p>
            </div>
            <div id="servicesGrid" class="row g-4"></div>
        </div>
    </section>

    <!-- Premium Section -->
    <section id="premium" class="py-5 premium-section">
        <div class="container">
            <div class="text-center text-white mb-5">
                <h2 class="display-5 fw-bold">Become a Premium Member</h2>
                <p class="lead">Get exclusive discounts and benefits</p>
            </div>
            <div class="row justify-content-center g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <h3 class="card-title text-dark mb-3">Silver Member</h3>
                            <p class="display-4 text-primary fw-bold">5% <span class="h4 text-muted fw-normal">discount</span></p>
                            <ul class="list-unstyled text-start mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>5% off all bookings</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Priority support</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Exclusive offers</li>
                            </ul>
                            <button onclick="subscribePremium('silver')" class="btn btn-secondary w-100">Subscribe</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4">
                            <h3 class="card-title text-dark mb-3">Gold Member</h3>
                            <p class="display-4 text-warning fw-bold">10% <span class="h4 text-muted fw-normal">discount</span></p>
                            <ul class="list-unstyled text-start mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>10% off all bookings</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Priority support</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Exclusive offers</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Free upgrades</li>
                            </ul>
                            <button onclick="subscribePremium('gold')" class="btn btn-warning text-white w-100">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold text-dark mb-4">About Our Hotel</h2>
                    <p class="text-muted mb-4">Welcome to Luxury Hotel, where we provide exceptional hospitality and comfort. Our hotel offers world-class amenities, luxurious rooms, and impeccable service to ensure your stay is unforgettable.</p>
                    <p class="text-muted mb-4">Whether you're traveling for business or leisure, our dedicated staff is here to make your experience exceptional.</p>
                    <div class="row text-center mt-4">
                        <div class="col-4"><h3 class="text-primary fw-bold">500+</h3><p class="text-muted">Rooms</p></div>
                        <div class="col-4"><h3 class="text-primary fw-bold">50+</h3><p class="text-muted">Services</p></div>
                        <div class="col-4"><h3 class="text-primary fw-bold">10k+</h3><p class="text-muted">Customers</p></div>
                    </div>
                </div>
                <div class="col-lg-6"><img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?w=800" alt="Hotel" class="img-fluid rounded shadow"></div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-dark">Contact Us</h2>
                <p class="text-muted">We're here to help</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form class="row g-3">
                        <div class="col-md-6"><input type="text" class="form-control" placeholder="Your Name"></div>
                        <div class="col-md-6"><input type="email" class="form-control" placeholder="Your Email"></div>
                        <div class="col-12"><input type="text" class="form-control" placeholder="Subject"></div>
                        <div class="col-12"><textarea class="form-control" rows="4" placeholder="Message"></textarea></div>
                        <div class="col-12 text-center"><button type="submit" class="btn btn-primary px-5">Send Message</button></div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3"><h5>Luxury Hotel</h5><p class="text-muted">Experience world-class hospitality</p></div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#rooms" class="text-muted text-decoration-none">Rooms</a></li>
                        <li><a href="#services" class="text-muted text-decoration-none">Services</a></li>
                        <li><a href="#about" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="#contact" class="text-muted text-decoration-none">Contact</a></li>
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
            <hr class="my-4">
            <div class="text-center text-muted"><p>&copy; 2024 Luxury Hotel. All rights reserved.</p></div>
        </div>
    </footer>

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
                    <p class="mt-3 text-center">Don't have an account? <button onclick="switchToRegister()" class="btn btn-link">Register</button></p>
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
                    <p class="mt-3 text-center">Already have an account? <button onclick="switchToLogin()" class="btn btn-link">Login</button></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Book Room</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div id="bookingDetails"></div>
                    <form id="bookingForm">
                        <input type="hidden" id="bookingRoomId">
                        <div class="mb-3"><label class="form-label">Check-in Date</label><input type="date" id="bookingCheckIn" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Check-out Date</label><input type="date" id="bookingCheckOut" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Notes (Optional)</label><textarea id="bookingNotes" rows="3" class="form-control"></textarea></div>
                        <button type="submit" class="btn btn-success w-100">Confirm Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let authToken = localStorage.getItem('auth_token');
        let loginModal, registerModal, bookingModal;

        document.addEventListener('DOMContentLoaded', function() {
            loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
            bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
            checkAuth();
            loadRooms();
            loadServices();
            setMinDate();
        });

        function setMinDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('checkIn').min = today;
            document.getElementById('checkOut').min = today;
        }

        function checkAuth() {
            if (authToken) {
                document.getElementById('authButtons').style.display = 'none';
                document.getElementById('userMenu').style.display = 'block';
                fetchUser();
            }
        }

        async function fetchUser() {
            try {
                const response = await fetch('/api/user', {
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                });
                if (response.ok) {
                    const user = await response.json();
                    document.getElementById('userName').textContent = user.name;
                } else { logout(); }
            } catch (error) { console.error('Error:', error); }
        }

        async function loadRooms() {
            try {
                const response = await fetch('/api/rooms');
                const rooms = await response.json();
                const grid = document.getElementById('roomsGrid');
                grid.innerHTML = rooms.map(room => `
                    <div class="col-md-4">
                        <div class="card room-card h-100 border-0 shadow-sm">
                            <div class="card-img-top bg-primary bg-gradient d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-bed text-white" style="font-size: 4rem; opacity: 0.5;"></i>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold">Room ${room.room_number}</h5>
                                    <span class="badge bg-primary">${room.room_type}</span>
                                </div>
                                <p class="card-text text-muted small">${room.description ? room.description.substring(0, 80) : 'Luxurious room with modern amenities'}...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><span class="h4 text-primary fw-bold">$${room.price_per_night}</span><span class="text-muted">/night</span></div>
                                    <button onclick="bookRoom(${room.id})" class="btn btn-primary">Book Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (error) { console.error('Error loading rooms:', error); }
        }

        async function loadServices() {
            try {
                const response = await fetch('/api/services');
                const services = await response.json();
                const grid = document.getElementById('servicesGrid');
                grid.innerHTML = services.map(service => `
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-concierge-bell text-primary" style="font-size: 2rem;"></i>
                                </div>
                                <h5 class="card-title fw-bold">${service.name}</h5>
                                <p class="card-text text-muted small">${service.description ? service.description.substring(0, 60) : 'Premium service'}...</p>
                                <p class="h5 text-primary fw-bold">$${service.price}</p>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (error) { console.error('Error loading services:', error); }
        }

        function showLoginModal() { loginModal.show(); }
        function showRegisterModal() { registerModal.show(); }
        function switchToRegister() { loginModal.hide(); setTimeout(() => registerModal.show(), 300); }
        function switchToLogin() { registerModal.hide(); setTimeout(() => loginModal.show(), 300); }

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                const data = await response.json();
                if (response.ok) {
                    authToken = data.token;
                    localStorage.setItem('auth_token', authToken);
                    loginModal.hide();
                    checkAuth();
                    alert('Login successful!');
                } else { alert(data.message || 'Login failed'); }
            } catch (error) { alert('Login failed. Please try again.'); }
        });

        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const password_confirmation = document.getElementById('registerPasswordConfirmation').value;
            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ name, email, password, password_confirmation })
                });
                const data = await response.json();
                if (response.ok) {
                    authToken = data.token;
                    localStorage.setItem('auth_token', authToken);
                    registerModal.hide();
                    checkAuth();
                    alert('Registration successful!');
                } else { alert(data.message || 'Registration failed'); }
            } catch (error) { alert('Registration failed. Please try again.'); }
        });

        function logout() {
            fetch('/api/logout', {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
            }).then(() => {
                localStorage.removeItem('auth_token');
                authToken = null;
                document.getElementById('authButtons').style.display = 'flex';
                document.getElementById('userMenu').style.display = 'none';
                window.location.href = '/';
            });
        }

        function bookRoom(roomId) {
            if (!authToken) { showLoginModal(); return; }
            document.getElementById('bookingRoomId').value = roomId;
            document.getElementById('bookingCheckIn').value = document.getElementById('checkIn').value;
            document.getElementById('bookingCheckOut').value = document.getElementById('checkOut').value;
            bookingModal.show();
        }

        document.getElementById('bookingForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!authToken) { alert('Please login first'); return; }
            const roomId = document.getElementById('bookingRoomId').value;
            const check_in_date = document.getElementById('bookingCheckIn').value;
            const check_out_date = document.getElementById('bookingCheckOut').value;
            const notes = document.getElementById('bookingNotes').value;
            try {
                const response = await fetch('/api/bookings', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' },
                    body: JSON.stringify({ room_id: roomId, check_in_date, check_out_date, notes })
                });
                const data = await response.json();
                if (response.ok) {
                    alert('Booking successful! Your booking is pending confirmation.');
                    bookingModal.hide();
                } else { alert(data.message || 'Booking failed'); }
            } catch (error) { alert('Booking failed. Please try again.'); }
        });

        async function subscribePremium(tier) {
            if (!authToken) { showLoginModal(); return; }
            if (!confirm(`Subscribe to ${tier} premium for 1 month?`)) return;
            try {
                const response = await fetch('/api/premium/subscribe', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' },
                    body: JSON.stringify({ tier: tier, duration_months: 1 })
                });
                const data = await response.json();
                if (response.ok) {
                    alert(`Successfully subscribed to ${tier} premium! You now get ${tier === 'gold' ? '10' : '5'}% discount on bookings.`);
                } else { alert(data.message || 'Subscription failed'); }
            } catch (error) { alert('Subscription failed. Please try again.'); }
        }

        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('rooms').scrollIntoView({ behavior: 'smooth' });
        });
    </script>
</body>
</html>