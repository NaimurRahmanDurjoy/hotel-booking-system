@extends('layouts.frontend')

@section('styles')
<style>
    .room-card { transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1); border: none !important; border-radius: 20px !important; overflow: hidden; background: #fff; position: relative; }
    .room-card:hover { transform: translateY(-12px); box-shadow: 0 30px 60px rgba(0,0,0,0.15) !important; }
    .room-image-container { position: relative; overflow: hidden; height: 260px !important; }
    .room-card:hover .room-image-container img { transform: scale(1.1) rotate(1deg); }
    .room-image-container img { transition: transform 0.8s ease; }
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

    /* Search Section Enhancements */
    .search-container-premium {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 30px;
        padding: 40px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        max-width: 1000px;
        margin: 0 auto;
    }
    .search-input-group {
        position: relative;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    .search-input-group:focus-within {
        background: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: var(--bs-primary);
    }
    .search-input-group i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--bs-primary);
        font-size: 0.9rem;
        z-index: 5;
    }
    .search-input-group .form-control, 
    .search-input-group .form-select {
        border: none !important;
        padding: 12px 15px 12px 45px !important;
        background: transparent !important;
        font-weight: 500;
        color: #333;
    }
    .search-btn-premium {
        background: var(--bs-primary);
        color: #fff;
        border: none;
        border-radius: 15px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(30, 58, 95, 0.2);
    }
    .search-btn-premium:hover {
        background: #162a45;
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(30, 58, 95, 0.3);
    }

    /* Premium Section Enhancements */
    .premium-section {
        background: linear-gradient(135deg, #1E3A5F 0%, #0F172A 100%);
        position: relative;
        overflow: hidden;
    }

    /* Luxury Room Cards */
    .room-card { transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); background: #fff; }
    .room-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px rgba(30, 58, 95, 0.15) !important; }
    .room-img-zoom { transition: transform 0.8s ease; }
    .room-card:hover .room-img-zoom { transform: scale(1.1); }
    
    .price-tag-luxury { position: absolute; bottom: 0; right: 0; background: var(--primary-navy); color: #fff; padding: 12px 25px; border-top-left-radius: 30px; z-index: 5; box-shadow: -5px -5px 15px rgba(0,0,0,0.1); }
    .room-type-badge-luxury { position: absolute; bottom: 20px; left: 20px; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); color: var(--primary-navy); padding: 6px 18px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; letter-spacing: 1px; z-index: 5; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    
    .room-features-luxury { display: flex; flex-wrap: wrap; gap: 12px; }
    .feature-item { font-size: 0.75rem; font-weight: 600; color: #666; background: #f8f9fa; padding: 6px 14px; border-radius: 50px; display: flex; align-items: center; }
    .feature-item i { color: var(--primary-navy); margin-right: 6px; font-size: 0.85rem; }
    
    .btn-navy { background: var(--primary-navy); color: #fff; border: none; }
    .btn-navy:hover { background: #162a45; color: #fff; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(30, 58, 95, 0.2); }
    .btn-outline-navy { border: 2px solid var(--primary-navy); color: var(--primary-navy); background: transparent; }
    .btn-outline-navy:hover { background: var(--primary-navy); color: #fff; transform: translateY(-2px); }
    
    .bg-navy-gradient { background: linear-gradient(135deg, #1E3A5F 0%, #0d1b2d 100%); }
    .transition-all { transition: all 0.3s ease; }
    .premium-section::before {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(245, 166, 35, 0.1) 0%, transparent 70%);
        top: -100px;
        right: -100px;
        border-radius: 50%;
    }
    .premium-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        color: #fff;
    }
    .premium-card:hover {
        transform: translateY(-15px) scale(1.02);
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(245, 166, 35, 0.3);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    }
    .premium-badge {
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .eligible-badge { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
    .not-eligible-badge { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
    .premium-price { font-size: 3.5rem; font-weight: 800; line-height: 1; }
    .premium-benefit-item { display: flex; align-items: center; margin-bottom: 12px; font-size: 0.95rem; opacity: 0.9; }
    .premium-benefit-icon { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background: rgba(245, 166, 35, 0.2); color: #F5A623; border-radius: 50%; margin-right: 12px; font-size: 0.7rem; }
    .premium-benefit-icon { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; background: rgba(245, 166, 35, 0.2); color: #F5A623; border-radius: 50%; margin-right: 12px; font-size: 0.7rem; }

    /* Loyalty Progress Styling */
    .loyalty-progress-box {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 15px;
        margin-top: 20px;
        position: relative;
    }
    .progress-mini {
        height: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        margin: 10px 0;
        overflow: hidden;
    }
    .progress-mini-bar {
        height: 100%;
        background: linear-gradient(90deg, #F5A623, #FFD700);
        box-shadow: 0 0 10px rgba(245, 166, 35, 0.5);
        border-radius: 10px;
        transition: width 1s ease-in-out;
    }
    .lock-icon-float {
        position: absolute;
        top: -10px;
        right: 15px;
        background: #1e3a5f;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        border: 1px solid rgba(255,255,255,0.2);
        color: #F5A623;
    }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-bg d-flex align-items-center" style="height: 100vh;">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-4" style="text-shadow: 0 2px 15px rgba(0,0,0,0.4);">Discover Your Perfect Stay</h1>
            <p class="lead mb-5 opacity-90 fw-light" style="letter-spacing: 1px;">Luxury, Comfort, and Unforgettable Memories</p>
            <div class="search-container-premium">
                <form id="searchForm" class="row g-3">
                    <div class="col-md-3 text-start">
                        <label class="form-label text-white fw-bold small mb-2 opacity-75">CHECK-IN</label>
                        <div class="search-input-group">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" id="checkIn" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3 text-start">
                        <label class="form-label text-white fw-bold small mb-2 opacity-75">CHECK-OUT</label>
                        <div class="search-input-group">
                            <i class="fas fa-calendar-check"></i>
                            <input type="date" id="checkOut" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2 text-start">
                        <label class="form-label text-white fw-bold small mb-2 opacity-75">GUESTS</label>
                        <div class="search-input-group">
                            <i class="fas fa-user-friends"></i>
                            <select id="guests" class="form-select">
                                <option value="1">1 Person</option>
                                <option value="2" selected>2 People</option>
                                <option value="3">3 People</option>
                                <option value="4">Family</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn search-btn-premium w-100 py-3">
                            <i class="fas fa-search me-2"></i>FIND YOUR ROOM
                        </button>
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
    <section id="premium" class="py-5 premium-section">
        <div class="container py-5">
            <div class="text-center mb-5">
                <span class="text-accent fw-bold text-uppercase mb-2 d-block" style="letter-spacing: 3px; color: #F5A623;">Exclusive Loyalty</span>
                <h2 class="display-4 fw-bold text-white mb-3">Become a Premium Member</h2>
                <p class="lead text-white-50 mx-auto" style="max-width: 600px;">Unlock a world of privilege, exclusive discounts, and personalized experiences crafted just for you.</p>
            </div>
            <div class="row justify-content-center g-4" id="premiumPlansContainer">
                <div class="col-12 text-center text-white-50">
                    <div class="spinner-border text-accent mb-3" role="status"></div>
                    <p>Loading membership privileges...</p>
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
                    <p class="lead text-muted mb-4">Located in the heart of the city, The Grand Azure offers a sanctuary of peace and sophistication. Our commitment to excellence has made us a landmark of hospitality.</p>
                    <div class="row g-4 mb-5">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3"><i class="fas fa-award text-primary fa-xl"></i></div>
                                <div><h5 class="fw-bold mb-0">Award Winning</h5><small class="text-muted">Best Luxury Hotel 2024</small></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3"><i class="fas fa-shield-alt text-success fa-xl"></i></div>
                                <div><h5 class="fw-bold mb-0">Safe & Secure</h5><small class="text-muted">24/7 Security & Care</small></div>
                            </div>
                        </div>
                    </div>
                    <!-- <button class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">DISCOVER OUR STORY</button> -->
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
                            <div><h6 class="fw-bold mb-1">Our Location</h6><p class="text-muted mb-0">House 12, Road 5, Dhanmondi, Dhaka 1205, Bangladesh</p></div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="contact-info-icon"><i class="fas fa-phone-alt"></i></div>
                            <div><h6 class="fw-bold mb-1">Phone Number</h6><p class="text-muted mb-0">+880 1712 345678<br>+880 1912 345678</p></div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
                            <div><h6 class="fw-bold mb-1">Email Address</h6><p class="text-muted mb-0">info@luxuryhotel.com.bd<br>bookings@luxuryhotel.com.bd</p></div>
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
                                    <input type="text" id="contactName" class="form-control border-0 bg-light p-3 rounded-3" placeholder="John Doe" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <input type="email" id="contactEmail" class="form-control border-0 bg-light p-3 rounded-3" placeholder="john@example.com" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Subject</label>
                                    <input type="text" id="contactSubject" class="form-control border-0 bg-light p-3 rounded-3" placeholder="Inquiry about suite availability" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Your Message</label>
                                    <textarea id="contactMessage" class="form-control border-0 bg-light p-3 rounded-3" rows="5" placeholder="Tell us how we can help you..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" id="contactSubmitBtn" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">SEND MESSAGE <i class="fas fa-paper-plane ms-2"></i></button>
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
            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-7">
                            <div id="roomDetailsImage" style="height: 600px; background: #f1f5f9;"></div>
                        </div>
                        <div class="col-lg-5 d-flex flex-column">
                            <div class="p-4 p-md-5 flex-grow-1 overflow-auto" style="max-height: 600px;">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div id="roomDetailsHeader"></div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div id="roomDetailsContent"></div>
                            </div>
                            <div class="mt-auto p-4 p-md-5 bg-light border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted small mb-0">Total per night</p>
                                        <h3 class="fw-bold text-primary mb-0" id="roomDetailsPrice"></h3>
                                    </div>
                                    <button id="bookNowBtn" class="btn btn-primary btn-lg px-5 rounded-pill fw-bold shadow-sm">BOOK NOW</button>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header bg-navy-gradient text-white border-0 py-4 px-4">
                    <h5 class="modal-title fw-bold"><i class="fas fa-concierge-bell me-2 text-warning"></i>Reserve Your Stay</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 p-md-5">
                    <form id="bookingForm">
                        <input type="hidden" id="bookingRoomId">
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Check-In Date</label>
                                <div class="position-relative">
                                    <i class="fas fa-calendar-alt position-absolute" style="left: 15px; top: 15px; color: var(--accent-gold); z-index: 5;"></i>
                                    <input type="date" id="bookingCheckIn" class="form-control bg-light border-0 py-3 ps-5 rounded-3" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Check-Out Date</label>
                                <div class="position-relative">
                                    <i class="fas fa-calendar-check position-absolute" style="left: 15px; top: 15px; color: var(--accent-gold); z-index: 5;"></i>
                                    <input type="date" id="bookingCheckOut" class="form-control bg-light border-0 py-3 ps-5 rounded-3" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Special Requests</label>
                                <textarea id="bookingNotes" rows="3" class="form-control border-0 bg-light p-3 rounded-3" placeholder="Any dietary needs, room preferences, etc."></textarea>
                            </div>
                            <div class="col-12 pt-2">
                                <button type="submit" class="btn btn-navy w-100 py-3 rounded-pill fw-bold shadow-lg">
                                    <i class="fas fa-check-circle me-2"></i>CONFIRM RESERVATION
                                </button>
                            </div>
                        </div>
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
        
        async function loadPremiumPlans() {
            try {
                const plansRes = await fetch('/api/premium-plans');
                const plans = await plansRes.json();
                
                let userStatus = { completed_bookings: 0, premium: false };
                if (authToken) {
                    const statusRes = await fetch('/api/premium/status', {
                        headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                    });
                    if (statusRes.ok) userStatus = await statusRes.json();
                }

                const container = document.getElementById('premiumPlansContainer');
                
                if (plans.length === 0) {
                    container.innerHTML = '<div class="col-12 text-center text-white"><p>No plans available at the moment.</p></div>';
                    return;
                }

                container.innerHTML = plans.map(plan => {
                    const meetsRequirement = userStatus.completed_bookings >= plan.min_bookings;
                    const isAlreadyPremium = userStatus.premium && userStatus.subscription?.tier === plan.tier_key;
                    const isGold = plan.tier_key === 'gold';

                    return `
                        <div class="col-md-5">
                            <div class="card h-100 premium-card ${!meetsRequirement ? 'opacity-75' : ''}">
                                <div class="card-body p-4 p-lg-5 d-flex flex-column h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h3 class="fw-bold mb-0">${plan.name}</h3>
                                        <span class="premium-badge ${meetsRequirement ? 'eligible-badge' : 'not-eligible-badge'}">
                                            <i class="fas ${meetsRequirement ? 'fa-unlock' : 'fa-lock'} me-1"></i>
                                            ${plan.min_bookings}+ Stays
                                        </span>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="premium-price ${isGold ? 'text-warning' : 'text-info'}">
                                            ${plan.discount_percentage}%
                                        </div>
                                        <div class="text-white-50 text-uppercase fw-bold small mt-1" style="letter-spacing: 2px;">
                                            Exclusive Discount
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <p class="text-white-50 mb-3 small fw-bold">MEMBERSHIP PRIVILEGES</p>
                                        ${(plan.benefits || []).map(benefit => `
                                            <div class="premium-benefit-item">
                                                <div class="premium-benefit-icon">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                ${benefit}
                                            </div>
                                        `).join('')}
                                    </div>

                                    <div class="mt-auto">
                                        ${isAlreadyPremium ? `
                                            <button class="btn btn-warning text-dark w-100 py-3 rounded-pill fw-bold shadow-lg opacity-75 mb-3" disabled>
                                                <i class="fas fa-check-circle me-2"></i>Active Membership
                                            </button>
                                            <button onclick="cancelPremium()" class="btn btn-outline-danger w-100 py-2 rounded-pill fw-bold border-2" style="font-size: 0.75rem; letter-spacing: 1px;">
                                                UNSUBSCRIBE MEMBERSHIP
                                            </button>
                                        ` : `
                                            <button onclick="subscribePremium('${plan.tier_key}')" 
                                                    class="btn ${isGold ? 'btn-warning text-dark' : 'btn-info'} w-100 py-3 rounded-pill fw-bold shadow-lg"
                                                    ${!meetsRequirement ? 'disabled' : ''}>
                                                ${meetsRequirement ? '<i class="fas fa-crown me-2"></i> ACTIVATE PRIVILEGES' : '<i class="fas fa-lock me-2"></i> MEMBERSHIP LOCKED'}
                                            </button>
                                        `}
                                        
                                        ${!meetsRequirement ? `
                                            <div class="loyalty-progress-box">
                                                <div class="lock-icon-float">
                                                    <i class="fas fa-lock"></i>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="small text-white-50">Loyalty Progress</span>
                                                    <span class="small fw-bold text-warning">${userStatus.completed_bookings}/${plan.min_bookings}</span>
                                                </div>
                                                <div class="progress-mini">
                                                    <div class="progress-mini-bar" style="width: ${(userStatus.completed_bookings / plan.min_bookings) * 100}%"></div>
                                                </div>
                                                <p class="small text-white-50 mb-0 mt-2" style="font-size: 0.75rem;">
                                                    <i class="fas fa-sparkles me-1 text-warning"></i>
                                                    Complete <strong>${plan.min_bookings - userStatus.completed_bookings} more</strong> stay(s) to unlock
                                                </p>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            } catch (error) {
                console.error('Error loading plans:', error);
            }
        }

        loadRooms();
        loadServices();
        loadPremiumPlans();
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

            const [roomsRes, statusRes] = await Promise.all([
                fetch(url, { headers: { 'Accept': 'application/json' } }),
                authToken ? fetch('/api/premium/status', {
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                }) : Promise.resolve({ ok: false })
            ]);

            const rooms = await roomsRes.json();
            let userStatus = { premium: false, discount: 0 };
            
            if (statusRes.ok) {
                const statusData = await statusRes.json();
                userStatus.premium = statusData.premium;
                if (statusData.premium) {
                    userStatus.discount = statusData.discount || 0;
                    userStatus.tier = statusData.subscription?.tier;
                }
            }

            const grid = document.getElementById('roomsGrid');

            if (rooms.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center py-5"><h4 class="text-muted">No rooms available for the selected criteria.</h4></div>';
                return;
            }
            grid.innerHTML = rooms.map(room => {
                const originalPrice = parseFloat(room.price_per_night);
                const discountedPrice = userStatus.premium ? (originalPrice * (1 - userStatus.discount / 100)).toFixed(0) : originalPrice;
                const hasDiscount = userStatus.premium && userStatus.discount > 0;

                return `
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 room-card border-0 shadow-lg overflow-hidden">
                        <div class="room-image-container position-relative overflow-hidden" style="height: 280px;">
                            <div class="price-tag-luxury">
                                ${hasDiscount ? `
                                    <div class="d-flex flex-column align-items-end" style="line-height: 1.1;">
                                        <span class="text-decoration-line-through opacity-75 small" style="font-size: 0.75rem;">TK ${originalPrice}</span>
                                        <span class="fw-bold">TK ${discountedPrice}<span class="small fw-normal">/night</span></span>
                                    </div>
                                ` : `<span class="fw-bold">TK ${originalPrice}</span><span class="small fw-normal">/night</span>`}
                            </div>
                            <div class="room-type-badge-luxury">${room.room_type}</div>
                            ${hasDiscount ? `
                                <div class="position-absolute top-0 start-0 m-3" style="z-index: 10;">
                                    <span class="badge ${userStatus.tier === 'gold' ? 'bg-warning text-dark' : 'bg-info'} rounded-pill shadow-lg py-2 px-3 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">
                                        <i class="fas fa-crown me-1"></i> ${userStatus.discount}% MEMBER PRICE
                                    </span>
                                </div>
                            ` : ''}
                            ${room.image 
                                ? `<img src="${room.image}" class="w-100 h-100 object-fit-cover room-img-zoom" alt="Room ${room.room_number}">`
                                : `<div class="w-100 h-100 d-flex align-items-center justify-content-center bg-navy-gradient">
                                     <i class="fas fa-bed text-white opacity-25" style="font-size: 5rem;"></i>
                                   </div>`
                            }
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h4 class="fw-bold mb-0" style="color: var(--primary-navy);">Room ${room.room_number}</h4>
                                <div class="text-warning small">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="text-muted small mb-4" style="line-height: 1.6; height: 45px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                ${room.description || 'Experience the ultimate luxury in our meticulously designed rooms featuring premium amenities.'}
                            </p>
                            <div class="room-features-luxury mb-4">
                                <div class="feature-item"><i class="fas fa-user-friends"></i> ${room.capacity} Guests</div>
                                ${Array.isArray(room.amenities) ? room.amenities.slice(0, 2).map(amenity => {
                                    let icon = 'fa-check-circle';
                                    if (amenity.toLowerCase().includes('wifi')) icon = 'fa-wifi';
                                    if (amenity.toLowerCase().includes('coffee')) icon = 'fa-coffee';
                                    return `<div class="feature-item"><i class="fas ${icon}"></i> ${amenity}</div>`;
                                }).join('') : ''}
                            </div>
                            <div class="d-flex gap-2 mt-auto">
                                <button onclick="showRoomDetails(${room.id})" class="btn btn-outline-navy flex-grow-1 rounded-pill fw-bold btn-sm py-3 transition-all">DETAILS</button>
                                <button onclick="openBookingModal(${room.id})" class="btn btn-navy flex-grow-1 rounded-pill fw-bold btn-sm py-3 shadow-lg transition-all">BOOK NOW</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            }).join('');
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
                ? `<img src="${room.image}" class="w-100 h-100 object-fit-cover" alt="Room ${room.room_number}">`
                : `<div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-gradient text-white"><i class="fas fa-bed fa-5x"></i></div>`;
            
            document.getElementById('roomDetailsHeader').innerHTML = `
                <h2 class="fw-bold mb-1">Room ${room.room_number}</h2>
                <span class="badge bg-primary px-3 py-2 text-uppercase rounded-pill" style="letter-spacing: 1px; font-size: 0.7rem;">${room.room_type}</span>
            `;
            
            document.getElementById('roomDetailsPrice').innerText = `TK ${room.price_per_night}`;
            
            document.getElementById('roomDetailsContent').innerHTML = `
                <p class="text-muted mb-4" style="line-height: 1.6;">${room.description || 'Experience the ultimate luxury in our meticulously designed rooms.'}</p>
                <div class="row g-4 mb-4">
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
                openBookingModal(room.id);
            };
            
            roomDetailsModal.show();
        } catch (error) {
            console.error('Error loading room details:', error);
        }
    }

    function openBookingModal(roomId) {
        @guest
            window.location.href = '/login';
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
            window.location.href = '/login';
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
            Swal.fire({ 
                icon: 'success', 
                title: 'Welcome to Elite!', 
                text: 'Your subscription is now active.', 
                confirmButtonColor: '#1E3A5F' 
            }).then(() => {
                window.location.reload(); // Reload to apply all membership changes
            }); 
        } else { 
            Swal.fire({ icon: 'error', title: 'Enrollment Failed', text: 'Something went wrong. Please try again later.' }); 
        }
    }

    async function cancelPremium() {
        const confirmCancel = await Swal.fire({
            title: 'Are you sure?',
            text: "You will lose your exclusive discounts and premium privileges!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Cancel it!',
            cancelButtonText: 'Keep Membership',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#1E3A5F'
        });

        if (!confirmCancel.isConfirmed) return;

        try {
            const response = await fetch('/api/premium/cancel', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'Authorization': `Bearer ${authToken}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Cancelled!',
                    text: 'Your premium membership has been terminated.',
                    confirmButtonColor: '#1E3A5F'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Action Failed', text: 'Something went wrong. Please try again.' });
            }
        } catch (error) {
            console.error('Cancel Error:', error);
        }
    }

    document.getElementById('contactForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('contactSubmitBtn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> SENDING...';
        btn.disabled = true;

        const data = {
            name: document.getElementById('contactName').value,
            email: document.getElementById('contactEmail').value,
            subject: document.getElementById('contactSubject').value,
            message: document.getElementById('contactMessage').value
        };

        try {
            const response = await fetch('/api/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Message Sent!',
                    text: result.message,
                    confirmButtonColor: '#1E3A5F'
                });
                this.reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: result.message || 'Something went wrong while sending your message.'
                });
            }
        } catch (error) {
            console.error('Contact Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'System Error',
                text: 'We could not connect to the server. Please try again later.'
            });
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });
</script>
@endsection