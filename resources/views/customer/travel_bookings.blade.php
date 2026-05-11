@extends('layouts.frontend')

@section('content')
<div class="container py-5 mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold">My Travel Bookings</h1>
            <p class="text-muted">Track your upcoming tours and experiences</p>
        </div>
    </div>

    <div class="row" id="travelBookingsGrid">
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p>Loading your tour reservations...</p>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        try {
            const response = await fetch('/api/travel-bookings', {
                headers: { 
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            const bookings = data.data || [];
            const grid = document.getElementById('travelBookingsGrid');

            if (bookings.length === 0) {
                grid.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <div class="mb-4 text-muted"><i class="fas fa-map-marked-alt fa-5x"></i></div>
                        <h3>No travel bookings found</h3>
                        <p>You haven't booked any tour packages yet.</p>
                        <a href="/#travel" class="btn btn-primary rounded-pill px-4 py-2 mt-3">Explore Packages</a>
                    </div>
                `;
                return;
            }

            grid.innerHTML = bookings.map(booking => `
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-bold mb-0">${booking.package.title}</h5>
                                <span class="badge bg-${getStatusColor(booking.status)} rounded-pill">${booking.status.toUpperCase()}</span>
                            </div>
                            <p class="text-muted small mb-3"><i class="fas fa-map-marker-alt me-1"></i> ${booking.package.destination}</p>
                            <div class="bg-light p-3 rounded-3 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">Travel Date:</span>
                                    <span class="small fw-bold">${new Date(booking.travel_date).toLocaleDateString()}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">Guests:</span>
                                    <span class="small fw-bold">${booking.guests} People</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Total Price:</span>
                                    <span class="small fw-bold text-primary">TK ${booking.total_price}</span>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary w-100 rounded-pill btn-sm">VIEW DETAILS</button>
                        </div>
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error(error);
        }
    });

    function getStatusColor(status) {
        switch(status) {
            case 'pending': return 'warning';
            case 'confirmed': return 'success';
            case 'cancelled': return 'danger';
            case 'completed': return 'info';
            default: return 'secondary';
        }
    }
</script>
@endsection
@endsection
