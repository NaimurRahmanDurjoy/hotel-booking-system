@extends('layouts.frontend')

@section('styles')
<style>
    .room-card { transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); border: none !important; border-radius: 20px !important; overflow: hidden; background: #fff; position: relative; }
    .room-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.12) !important; }
    .room-image-container { position: relative; overflow: hidden; height: 260px !important; }
    .room-card:hover .room-image-container img { transform: scale(1.1); }
    .room-image-container img { transition: transform 0.6s ease; }
    .price-tag { position: absolute; top: 20px; right: 20px; background: rgba(30, 58, 95, 0.9); color: #fff; padding: 8px 15px; border-radius: 50px; font-weight: 700; z-index: 10; backdrop-filter: blur(5px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.2); }
    .room-type-badge { position: absolute; bottom: 20px; left: 20px; background: var(--bs-primary); color: #fff; padding: 5px 15px; border-radius: 5px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; z-index: 10; }
    .room-details { display: flex; gap: 15px; margin: 15px 0; color: #666; font-size: 0.85rem; }
    .room-details span i { color: var(--bs-primary); margin-right: 5px; }
    .section-title-wrapper { position: relative; margin-bottom: 60px; }
    .section-title-wrapper::after { content: ''; position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); width: 60px; height: 4px; background: var(--bs-primary); border-radius: 2px; }
    .hero-bg { background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)), url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1920') no-repeat center center/cover !important; }
    
    .service-card { transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1); border: none !important; border-radius: 24px !important; overflow: hidden; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important; }
    .service-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px rgba(0,0,0,0.15) !important; }
    .service-image-container { height: 240px; overflow: hidden; position: relative; }
    .service-card:hover .service-image-container img { transform: scale(1.1) rotate(1deg); }
    .service-image-container img { transition: transform 0.8s ease; }
    .service-price-badge { position: absolute; top: 20px; right: 20px; background: rgba(30, 58, 95, 0.9); color: #fff; padding: 6px 16px; border-radius: 50px; font-weight: 700; z-index: 10; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.3); }
    .service-card:hover .card-body h4 { color: var(--bs-primary) !important; transition: color 0.3s ease; }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-bg d-flex align-items-center" style="height: 100vh;">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-4" style="text-shadow: 0 2px 15px rgba(0,0,0,0.4);">Discover Your Perfect Stay</h1>
            <p class="lead mb-5 opacity-90 fw-light" style="letter-spacing: 1px;">Luxury, Comfort, and Unforgettable Memories</p>
            <div class="bg-white rounded-4 p-4 shadow-lg" style="max-width: 950px; margin: 0 auto; backdrop-filter: blur(10px); background: rgba(255,255,255,0.95) !important; border: 1px solid rgba(255,255,255,0.3);">
                <form id="searchForm" class="row g-3">
                    <div class="col-md-3 text-start">
                        <label class="form-label text-dark fw-bold small mb-1">CHECK-IN</label>
                        <input type="date" id="checkIn" class="form-control border-0 bg-light py-2" required>
                    </div>
                    <div class="col-md-3 text-start">
                        <label class="form-label text-dark fw-bold small mb-1">CHECK-OUT</label>
                        <input type="date" id="checkOut" class="form-control border-0 bg-light py-2" required>
                    </div>
                    <div class="col-md-2 text-start">
                        <label class="form-label text-dark fw-bold small mb-1">GUESTS</label>
                        <select id="guests" class="form-select border-0 bg-light py-2">
                            <option value="1">1 Person</option>
                            <option value="2" selected>2 People</option>
                            <option value="3">3 People</option>
                            <option value="4">Family</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow border-0"><i class="fas fa-search me-2"></i>FIND YOUR ROOM</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Rooms Section -->
    <section id="rooms" class="py-5 bg-light">
        <div class="container py-4">
            <div class="section-title-wrapper text-center">
                <span class="text-primary fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 2px; font-size: 0.9rem;">Accommodation</span>
                <h2 class="display-5 fw-bold text-dark">Luxury Rooms & Suites</h2>
            </div>
            <div id="roomsGrid" class="row g-4"></div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container py-4">
            <div class="section-title-wrapper text-center">
                <span class="text-primary fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 2px; font-size: 0.9rem;">Experience</span>
                <h2 class="display-5 fw-bold text-dark">Our Premium Services</h2>
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
            const response = await fetch('/api/rooms', {
                headers: { 'Accept': 'application/json' }
            });
            const rooms = await response.json();
            const grid = document.getElementById('roomsGrid');
            grid.innerHTML = rooms.map(room => `
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 room-card shadow-sm">
                        <div class="room-image-container">
                            <div class="price-tag">TK ${room.price_per_night}<span class="small fw-normal">/night</span></div>
                            <div class="room-type-badge">${room.room_type}</div>
                            ${room.image 
                                ? `<img src="${room.image}" class="w-100 h-100 object-fit-cover" alt="Room ${room.room_number}">`
                                : `<div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-gradient opacity-75">
                                     <i class="fas fa-bed text-white" style="font-size: 4rem;"></i>
                                   </div>`
                            }
                        </div>
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-2">Room ${room.room_number}</h4>
                            <p class="text-muted small mb-0" style="height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                ${room.description || 'Experience the ultimate luxury in our meticulously designed rooms.'}
                            </p>
                            <div class="room-details">
                                <span><i class="fas fa-user-friends"></i> ${room.capacity} Guests</span>
                                ${Array.isArray(room.amenities) ? room.amenities.map(amenity => {
                                    let icon = 'fa-star';
                                    if (amenity.toLowerCase().includes('wifi')) icon = 'fa-wifi';
                                    if (amenity.toLowerCase().includes('breakfast')) icon = 'fa-coffee';
                                    if (amenity.toLowerCase().includes('tv')) icon = 'fa-tv';
                                    if (amenity.toLowerCase().includes('air')) icon = 'fa-wind';
                                    return `<span><i class="fas ${icon}"></i> ${amenity}</span>`;
                                }).slice(0, 3).join('') : ''}
                            </div>
                            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                <a href="#" class="text-primary fw-bold text-decoration-none small" style="letter-spacing: 1px;">VIEW DETAILS</a>
                                <button onclick="bookRoom(${room.id})" class="btn btn-primary px-4 rounded-pill shadow-sm fw-bold border-0" style="font-size: 0.85rem;">BOOK NOW</button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        } catch (error) { console.error('Error loading rooms:', error); }
    }

    async function loadServices() {
        try {
            const response = await fetch('/api/services', {
                headers: { 'Accept': 'application/json' }
            });
            const services = await response.json();
            const grid = document.getElementById('servicesGrid');
            grid.innerHTML = services.map(service => `
                <div class="col-md-4">
                    <div class="card h-100 service-card shadow-sm border-0">
                        <div class="service-image-container" style="height: 240px !important;">
                            <div class="price-tag" style="top: 15px; right: 15px; font-size: 0.9rem;">TK ${service.price}</div>
                            ${service.image 
                                ? `<img src="${service.image}" class="w-100 h-100 object-fit-cover" alt="${service.name}">`
                                : `<div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-10">
                                     <i class="fas fa-concierge-bell text-primary" style="font-size: 3.5rem; opacity: 0.2;"></i>
                                   </div>`
                            }
                        </div>
                        <div class="card-body p-4 text-center">
                            <h4 class="fw-bold mb-3" style="color: #1E3A5F;">${service.name}</h4>
                            <p class="text-muted mb-0" style="font-size: 0.9rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                ${service.description}
                            </p>
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
            
            const result = await response.json();
            if (response.ok) {
                alert('Booking successful! Your booking is pending confirmation.');
                bookingModal.hide();
                window.location.href = '/customer/bookings'; // Redirect to see their bookings
            } else { 
                if (response.status === 401) {
                    alert('Session expired. Please login again.');
                    showLoginModal();
                } else {
                    alert(result.message || 'Booking failed'); 
                }
            }
        } catch (error) { 
            console.error('Booking Error:', error);
            alert('An error occurred during booking. Please try again.'); 
        }
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