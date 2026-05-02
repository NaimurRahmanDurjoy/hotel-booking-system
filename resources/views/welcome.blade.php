@extends('layouts.frontend')

@section('content')
    <!-- Hero Section -->
    <section class="hero-bg d-flex align-items-center" style="height: 100vh;">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-4">Welcome to Luxury Hotel</h1>
            <p class="lead mb-5">Experience world-class hospitality and comfort</p>
            <div class="bg-white rounded p-4 shadow" style="max-width: 900px; margin: 0 auto;">
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
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 py-2"><i class="fas fa-search me-1"></i>Search Rooms</button>
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
    <section id="premium" class="py-5 bg-navy text-white" style="background: #1E3A5F;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Become a Premium Member</h2>
                <p class="lead">Get exclusive discounts and benefits</p>
            </div>
            <div class="row justify-content-center g-4 text-dark">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-3">Silver Member</h3>
                            <p class="display-4 text-primary fw-bold">5% <span class="h4 text-muted fw-normal">discount</span></p>
                            <ul class="list-unstyled text-start mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>5% off all bookings</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Priority support</li>
                            </ul>
                            <button onclick="subscribePremium('silver')" class="btn btn-primary w-100">Subscribe</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-3">Gold Member</h3>
                            <p class="display-4 text-warning fw-bold">10% <span class="h4 text-muted fw-normal">discount</span></p>
                            <ul class="list-unstyled text-start mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>10% off all bookings</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Free upgrades</li>
                            </ul>
                            <button onclick="subscribePremium('gold')" class="btn btn-warning text-white w-100">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Book Room</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
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
@endsection

@section('scripts')
<script>
    let authToken = localStorage.getItem('auth_token');
    let bookingModal;

    document.addEventListener('DOMContentLoaded', function() {
        bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
        loadRooms();
        loadServices();
        setMinDate();
    });

    function setMinDate() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('checkIn').min = today;
        document.getElementById('checkOut').min = today;
    }

    async function loadRooms() {
        try {
            const response = await fetch('/api/rooms');
            const rooms = await response.json();
            const grid = document.getElementById('roomsGrid');
            grid.innerHTML = rooms.map(room => `
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <div class="card-img-top bg-primary bg-gradient d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-bed text-white" style="font-size: 4rem; opacity: 0.5;"></i>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold">Room ${room.room_number}</h5>
                                <span class="badge bg-primary">${room.room_type}</span>
                            </div>
                            <p class="card-text text-muted small">${room.description || 'Luxurious room with modern amenities'}</p>
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
                            <p class="card-text text-muted small">${service.description}</p>
                            <p class="h5 text-primary fw-bold">$${service.price}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        } catch (error) { console.error('Error loading services:', error); }
    }

    function bookRoom(roomId) {
        @guest
            showLoginModal();
            return;
        @endguest
        document.getElementById('bookingRoomId').value = roomId;
        document.getElementById('bookingCheckIn').value = document.getElementById('checkIn').value;
        document.getElementById('bookingCheckOut').value = document.getElementById('checkOut').value;
        bookingModal.show();
    }

    document.getElementById('bookingForm').addEventListener('submit', async function(e) {
        e.preventDefault();
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
            if (response.ok) {
                alert('Booking successful! Your booking is pending confirmation.');
                bookingModal.hide();
            } else { alert('Booking failed'); }
        } catch (error) { alert('Booking failed'); }
    });

    async function subscribePremium(tier) {
        @guest
            showLoginModal();
            return;
        @endguest
        if (!confirm(`Subscribe to ${tier} premium?`)) return;
        const response = await fetch('/api/premium/subscribe', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' },
            body: JSON.stringify({ tier: tier, duration_months: 1 })
        });
        if (response.ok) { alert('Subscription successful!'); } else { alert('Subscription failed'); }
    }
</script>
@endsection