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

    /* About & Contact Styles */
    .about-image-stack { position: relative; height: 500px; }
    .about-img-1 { position: absolute; width: 80%; height: 80%; object-fit: cover; border-radius: 30px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); z-index: 2; top: 0; left: 0; }
    .about-img-2 { position: absolute; width: 70%; height: 70%; object-fit: cover; border-radius: 30px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); z-index: 1; bottom: 0; right: 0; border: 10px solid #fff; }
    .stat-card { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); border-radius: 20px; transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.2); }
    .contact-form-card { border-radius: 30px; overflow: hidden; box-shadow: 0 40px 100px rgba(0,0,0,0.08); border: none; }
    .contact-info-icon { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 15px; background: rgba(30, 58, 95, 0.05); color: var(--bs-primary); margin-right: 20px; font-size: 1.2rem; }
    .form-control:focus { box-shadow: 0 0 0 4px rgba(30, 58, 95, 0.1); border-color: var(--bs-primary); }

    /* Room Details Modal Styles */
    .modal-content-premium { border-radius: 30px; overflow: hidden; border: none; }
    .room-detail-img { height: 400px; object-fit: cover; width: 100%; }
    .amenity-tag { padding: 8px 16px; background: #f8f9fa; border-radius: 12px; font-size: 0.9rem; color: #444; border: 1px solid #eee; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease; }
    .amenity-tag:hover { background: #fff; border-color: var(--bs-primary); color: var(--bs-primary); transform: translateY(-2px); }
    .amenity-tag i { color: var(--bs-primary); }
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

    <!-- About Section -->
    <section id="about" class="py-5 overflow-hidden">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-image-stack">
                        <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800" class="about-img-1" alt="Hotel Exterior">
                        <img src="https://images.unsplash.com/photo-1544124499-58912cbddaad?w=800" class="about-img-2" alt="Hotel Interior">
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="text-primary fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 2px;">Our Heritage</span>
                    <h2 class="display-4 fw-bold mb-4">Redefining Luxury Since 1995</h2>
                    <p class="lead text-muted mb-4">Located in the heart of the city, Luxury Hotel offers a sanctuary of peace and sophistication. Our commitment to excellence has made us a landmark of hospitality.</p>
                    <div class="row g-4 mb-5">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3"><i class="fas fa-award text-primary fa-xl"></i></div>
                                <div><h5 class="fw-bold mb-0">Award Winning</h5><small class="text-muted">Best Luxury Hotel 2023</small></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3"><i class="fas fa-shield-alt text-success fa-xl"></i></div>
                                <div><h5 class="fw-bold mb-0">Safe & Secure</h5><small class="text-muted">24/7 Security & Care</small></div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">DISCOVER OUR STORY</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Banner -->
    <section class="py-5 text-white" style="background: linear-gradient(135deg, #1E3A5F 0%, #162a45 100%);">
        <div class="container py-4">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-card p-4">
                        <h2 class="display-4 fw-bold mb-1">50+</h2>
                        <p class="mb-0 text-white-50">Premium Rooms</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card p-4">
                        <h2 class="display-4 fw-bold mb-1">12k</h2>
                        <p class="mb-0 text-white-50">Happy Guests</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card p-4">
                        <h2 class="display-4 fw-bold mb-1">15+</h2>
                        <p class="mb-0 text-white-50">Global Awards</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card p-4">
                        <h2 class="display-4 fw-bold mb-1">100%</h2>
                        <p class="mb-0 text-white-50">Satisfaction</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container py-5">
            <div class="section-title-wrapper text-center">
                <span class="text-primary fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 2px;">Get In Touch</span>
                <h2 class="display-5 fw-bold text-dark">Contact Our Concierge</h2>
            </div>
            <div class="row g-5">
                <div class="col-lg-5">
                    <div class="mb-5">
                        <h4 class="fw-bold mb-4">Contact Information</h4>
                        <div class="d-flex mb-4">
                            <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div><h6 class="fw-bold mb-1">Our Location</h6><p class="text-muted mb-0">123 Luxury Avenue, Paradise City, PC 4567</p></div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="contact-info-icon"><i class="fas fa-phone-alt"></i></div>
                            <div><h6 class="fw-bold mb-1">Phone Number</h6><p class="text-muted mb-0">+1 (234) 567 8900<br>+1 (234) 567 8901</p></div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
                            <div><h6 class="fw-bold mb-1">Email Address</h6><p class="text-muted mb-0">info@luxuryhotel.com<br>bookings@luxuryhotel.com</p></div>
                        </div>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-4">Follow Our Journey</h4>
                        <div class="d-flex gap-3">
                            <a href="#" class="btn btn-outline-primary rounded-circle p-3" style="width: 50px; height: 50px;"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn btn-outline-primary rounded-circle p-3" style="width: 50px; height: 50px;"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn btn-outline-primary rounded-circle p-3" style="width: 50px; height: 50px;"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="btn btn-outline-primary rounded-circle p-3" style="width: 50px; height: 50px;"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card contact-form-card p-4 p-md-5">
                        <form id="contactForm">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Your Name</label>
                                    <input type="text" class="form-control border-0 bg-light p-3 rounded-3" placeholder="John Doe" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <input type="email" class="form-control border-0 bg-light p-3 rounded-3" placeholder="john@example.com" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Subject</label>
                                    <input type="text" class="form-control border-0 bg-light p-3 rounded-3" placeholder="Inquiry about suite availability" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Your Message</label>
                                    <textarea class="form-control border-0 bg-light p-3 rounded-3" rows="5" placeholder="Tell us how we can help you..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">SEND MESSAGE <i class="fas fa-paper-plane ms-2"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Room Details Modal -->
    <div class="modal fade" id="roomDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content modal-content-premium border-0 shadow-lg">
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div id="roomDetailsImage"></div>
                        </div>
                        <div class="col-lg-6 p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div id="roomDetailsHeader"></div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div id="roomDetailsContent"></div>
                            <div class="mt-5 pt-4 border-top">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <p class="text-muted small mb-0">Total per night</p>
                                        <h3 class="fw-bold text-primary mb-0" id="roomDetailsPrice"></h3>
                                    </div>
                                    <div class="col-6">
                                        <button id="bookNowBtn" class="btn btn-primary btn-lg w-100 py-3 rounded-pill fw-bold shadow">BOOK THIS ROOM</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    let roomDetailsModal;

    document.addEventListener('DOMContentLoaded', function() {
        bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
        roomDetailsModal = new bootstrap.Modal(document.getElementById('roomDetailsModal'));
        loadRooms();
        loadServices();
        setMinDate();
    });

    // Search Form Handler
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const checkIn = document.getElementById('checkIn').value;
        const checkOut = document.getElementById('checkOut').value;
        const guests = document.getElementById('guests').value;
        loadRooms(checkIn, checkOut, guests);
        
        // Scroll to rooms section
        document.getElementById('rooms').scrollIntoView({ behavior: 'smooth' });
    });

    function setMinDate() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('checkIn').min = today;
        document.getElementById('checkOut').min = today;
    }

    async function loadRooms(checkIn = '', checkOut = '', guests = '') {
        try {
            let url = '/api/rooms?';
            if (checkIn) url += `check_in=${checkIn}&`;
            if (checkOut) url += `check_out=${checkOut}&`;
            if (guests) url += `guests=${guests}`;

            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });
            const rooms = await response.json();
            const grid = document.getElementById('roomsGrid');

            if (rooms.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center py-5"><h4 class="text-muted">No rooms available for the selected criteria.</h4></div>';
                return;
            }
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
                                <a href="#" onclick="event.preventDefault(); showRoomDetails(${room.id})" class="text-primary fw-semibold small text-decoration-none" style="letter-spacing: 1px;">
                                    View details → </a>
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

    async function showRoomDetails(roomId) {
        try {
            const response = await fetch(`/api/rooms/${roomId}`);
            const room = await response.json();
            
            document.getElementById('roomDetailsImage').innerHTML = room.image 
                ? `<img src="${room.image}" class="room-detail-img" alt="Room ${room.room_number}">`
                : `<div class="room-detail-img bg-primary d-flex align-items-center justify-content-center text-white"><i class="fas fa-bed fa-5x"></i></div>`;
            
            document.getElementById('roomDetailsHeader').innerHTML = `
                <h2 class="fw-bold mb-0">Room ${room.room_number}</h2>
                <span class="badge bg-primary px-3 py-2 text-uppercase" style="letter-spacing: 1px;">${room.room_type}</span>
            `;
            
            document.getElementById('roomDetailsPrice').innerText = `TK ${room.price_per_night}`;
            
            document.getElementById('roomDetailsContent').innerHTML = `
                <p class="lead text-muted mb-4">${room.description || 'Experience ultimate luxury in our meticulously designed rooms.'}</p>
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-4">
                        <div class="d-flex align-items-center">
                            <div class="contact-info-icon m-0 me-3"><i class="fas fa-user-friends"></i></div>
                            <div><p class="small text-muted mb-0">Capacity</p><h6 class="fw-bold mb-0">${room.capacity} Guests</h6></div>
                        </div>
                    </div>
                </div>
                <h5 class="fw-bold mb-3">Room Amenities</h5>
                <div class="d-flex flex-wrap gap-2">
                    ${Array.isArray(room.amenities) ? room.amenities.map(amenity => {
                        let icon = 'fa-star';
                        if (amenity.toLowerCase().includes('wifi')) icon = 'fa-wifi';
                        if (amenity.toLowerCase().includes('breakfast')) icon = 'fa-coffee';
                        if (amenity.toLowerCase().includes('tv')) icon = 'fa-tv';
                        if (amenity.toLowerCase().includes('air')) icon = 'fa-wind';
                        return `<span class="amenity-tag"><i class="fas ${icon}"></i> ${amenity}</span>`;
                    }).join('') : '<span class="text-muted">Standard amenities included.</span>'}
                </div>
            `;
            
            document.getElementById('bookNowBtn').onclick = () => {
                roomDetailsModal.hide();
                bookRoom(room.id);
            };
            
            roomDetailsModal.show();
        } catch (error) {
            console.error('Error loading room details:', error);
        }
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
                Swal.fire({
                    icon: 'success',
                    title: 'Booking Successful!',
                    text: 'Your reservation is pending confirmation. You can track it in your dashboard.',
                    confirmButtonColor: '#1E3A5F'
                }).then(() => {
                    window.location.href = '/customer/bookings';
                });
            } else { 
                if (response.status === 401) {
                    Swal.fire({ icon: 'warning', title: 'Session Expired', text: 'Please login again to continue.' });
                    showLoginModal();
                } else {
                    Swal.fire({ icon: 'error', title: 'Booking Failed', text: result.message || 'We could not complete your booking.' });
                }
            }
        } catch (error) { 
            console.error('Booking Error:', error);
            Swal.fire({ icon: 'error', title: 'System Error', text: 'An unexpected error occurred. Please try again.' });
        }
    });

    async function subscribePremium(tier) {
        @guest
            showLoginModal();
            return;
        @endguest
        
        const confirmSub = await Swal.fire({
            title: `Enroll in ${tier.toUpperCase()}?`,
            text: `Unlock exclusive benefits and premium discounts today!`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Subscribe!',
            confirmButtonColor: '#1E3A5F'
        });

        if (!confirmSub.isConfirmed) return;

        const response = await fetch('/api/premium/subscribe', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' },
            body: JSON.stringify({ tier: tier, duration_months: 1 })
        });

        if (response.ok) { 
            Swal.fire({ icon: 'success', title: 'Welcome to Elite!', text: 'Your subscription is now active.', confirmButtonColor: '#1E3A5F' }); 
        } else { 
            Swal.fire({ icon: 'error', title: 'Enrollment Failed', text: 'Something went wrong. Please try again later.' }); 
        }
    }

    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'success',
            title: 'Message Received',
            text: 'Our concierge team has received your message and will respond shortly.',
            confirmButtonColor: '#1E3A5F'
        });
        this.reset();
    });
</script>
@endsection