@extends('layouts.frontend')

@section('content')
<div class="hero-bg py-5" style="height: 250px; display: flex; align-items: center; background-image: linear-gradient(rgba(30, 58, 95, 0.8), rgba(30, 58, 95, 0.9)), url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1600'); margin-bottom: 50px;">
    <div class="container text-white">
        <h1 class="display-5 fw-bold mb-0">My Luxury Stays</h1>
        <p class="lead opacity-75">Review your journey and upcoming reservations with us.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="fas fa-history text-primary fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Stays</h6>
                        <h4 class="fw-bold mb-0" id="totalStays">...</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="fas fa-check-circle text-success fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Member Status</h6>
                        <h4 class="fw-bold mb-0" id="memberTier">...</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                        <i class="fas fa-star text-warning fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Loyalty Points</h6>
                        <h4 class="fw-bold mb-0" id="loyaltyPoints">...</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header bg-white py-4 px-4 border-0 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0"><i class="fas fa-list-ul me-2 text-primary"></i>Recent Reservations</h4>
            <a href="/#rooms" class="btn btn-primary px-4 rounded-pill fw-bold btn-sm shadow-sm"><i class="fas fa-plus me-2"></i>New Booking</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small fw-bold text-muted" style="letter-spacing: 1px;">
                        <tr>
                            <th class="ps-4 py-3">Reference</th>
                            <th class="py-3">Room Details</th>
                            <th class="py-3">Period</th>
                            <th class="py-3">Total Amount</th>
                            <th class="py-3">Status</th>
                            <th class="text-end pe-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTable">
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="viewBookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-primary text-white py-4 px-4 border-0">
                <h5 class="modal-title fw-bold">Reservation Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="bookingDetailsContent">
                <!-- Loaded via JS -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const authToken = localStorage.getItem('auth_token');
    let viewModal;
    
    document.addEventListener('DOMContentLoaded', () => {
        viewModal = new bootstrap.Modal(document.getElementById('viewBookingModal'));
        loadBookings();
        loadMemberStatus();
    });
    
    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
    }

    async function loadMemberStatus() {
        if (!authToken) return;
        try {
            const res = await fetch('/api/premium/status', {
                headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
            });
            const data = await res.json();
            document.getElementById('memberTier').textContent = data.premium ? (data.subscription?.tier?.toUpperCase() || 'PREMIUM') : 'REGULAR';
            document.getElementById('totalStays').textContent = data.completed_bookings || 0;
            document.getElementById('loyaltyPoints').textContent = (data.completed_bookings || 0) * 100;
        } catch (e) { console.error(e); }
    }
    
    async function loadBookings() {
        try {
            const response = await fetch('/api/bookings/my', {
                headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
            });
            const bookings = await response.json();
            const tbody = document.getElementById('bookingsTable');
            
            if (!bookings || bookings.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted"><i class="fas fa-calendar-times fa-3x mb-3 opacity-25"></i><br>No reservations found. Start your journey with us today!</td></tr>';
                return;
            }
            
            tbody.innerHTML = bookings.map(booking => {
                const statusClass = booking.status === 'confirmed' ? 'success' : (booking.status === 'pending' ? 'warning' : 'danger');
                return `
                <tr>
                    <td class="ps-4 fw-bold text-primary">#BK-${booking.id}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-2 me-3"><i class="fas fa-door-open text-primary"></i></div>
                            <div>
                                <div class="fw-bold">Room ${booking.room?.room_number || 'N/A'}</div>
                                <div class="small text-muted">${booking.room?.room_type || 'Luxury Suite'}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="small fw-bold text-dark"><i class="far fa-calendar-alt me-1 text-primary"></i> ${formatDate(booking.check_in_date)}</div>
                        <div class="small text-muted ps-3">to ${formatDate(booking.check_out_date)}</div>
                    </td>
                    <td><span class="fw-bold text-dark">TK ${parseFloat(booking.total_price).toLocaleString()}</span></td>
                    <td><span class="badge bg-${statusClass} bg-opacity-10 text-${statusClass} border border-${statusClass} border-opacity-25 px-3 py-2 rounded-pill">${booking.status.toUpperCase()}</span></td>
                    <td class="text-end pe-4">
                        <button onclick="viewBooking(${booking.id})" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">Details</button>
                    </td>
                </tr>`;
            }).join('');
        } catch (error) { 
            console.error('Error:', error);
            document.getElementById('bookingsTable').innerHTML = '<tr><td colspan="6" class="text-center py-5 text-danger">Failed to load reservations.</td></tr>';
        }
    }

    async function viewBooking(id) {
        const content = document.getElementById('bookingDetailsContent');
        content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
        viewModal.show();
        
        try {
            const response = await fetch(`/api/bookings/my`, {
                headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
            });
            const bookings = await response.json();
            const booking = bookings.find(b => b.id == id);
            
            if (booking) {
                content.innerHTML = `
                    <div class="booking-detail-item mb-4 text-center">
                        <div class="display-6 fw-bold text-primary mb-1">TK ${parseFloat(booking.total_price).toLocaleString()}</div>
                        <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">PAID & SECURED</div>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">Check-In</label>
                            <div class="fw-bold text-dark"><i class="fas fa-sign-in-alt text-primary me-1"></i> ${formatDate(booking.check_in_date)}</div>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">Check-Out</label>
                            <div class="fw-bold text-dark"><i class="fas fa-sign-out-alt text-primary me-1"></i> ${formatDate(booking.check_out_date)}</div>
                        </div>
                    </div>
                    <div class="bg-light rounded-4 p-4 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Room Type</span>
                            <span class="fw-bold text-dark">${booking.room?.room_type || 'Luxury Suite'}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Room Number</span>
                            <span class="fw-bold text-dark">${booking.room?.room_number || 'N/A'}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Booking Reference</span>
                            <span class="fw-bold text-dark">#BK-${booking.id}</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="small text-muted mb-4"><i class="fas fa-info-circle me-1"></i> Show this reference during check-in.</p>
                        <button class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" onclick="window.print()"><i class="fas fa-print me-2"></i>Download Receipt</button>
                    </div>
                `;
            }
        } catch (e) { console.error(e); }
    }
</script>
@endsection