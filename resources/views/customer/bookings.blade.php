@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">My Bookings</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold"><i class="fas fa-calendar-alt text-primary me-2"></i>My Bookings</h2>
                <a href="/#rooms" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>New Booking</a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Booking ID</th>
                                    <th>Room</th>
                                    <th>Stay Dates</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
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
    </div>
</div>
@endsection

@section('scripts')
<script>
    const authToken = localStorage.getItem('auth_token');
    
    document.addEventListener('DOMContentLoaded', loadBookings);
    
    async function loadBookings() {
        try {
            const response = await fetch('/api/bookings/my', {
                headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
            });
            const bookings = await response.json();
            const tbody = document.getElementById('bookingsTable');
            
            if (!bookings || bookings.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">You haven\'t made any bookings yet.</td></tr>';
                return;
            }
            
            tbody.innerHTML = bookings.map(booking => {
                const statusClass = booking.status === 'confirmed' ? 'success' : (booking.status === 'pending' ? 'warning' : 'danger');
                return `<tr>
                    <td class="ps-4 fw-bold text-primary">#${booking.id}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-2 me-3"><i class="fas fa-bed text-primary"></i></div>
                            <div>
                                <div class="fw-bold">Room ${booking.room?.room_number || 'N/A'}</div>
                                <div class="small text-muted">${booking.room?.room_type || 'Standard'}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="small fw-bold">${booking.check_in_date}</div>
                        <div class="small text-muted">to ${booking.check_out_date}</div>
                    </td>
                    <td><span class="fw-bold">TK ${booking.total_price}</span></td>
                    <td><span class="badge bg-${statusClass} bg-opacity-10 text-${statusClass} px-3 py-2">${booking.status.toUpperCase()}</span></td>
                    <td class="text-end pe-4">
                        <button onclick="viewBooking(${booking.id})" class="btn btn-outline-primary btn-sm">Details</button>
                    </td>
                </tr>`;
            }).join('');
        } catch (error) { 
            console.error('Error:', error);
            document.getElementById('bookingsTable').innerHTML = '<tr><td colspan="6" class="text-center py-5 text-danger">Failed to load bookings.</td></tr>';
        }
    }
    
    function viewBooking(id) { alert('Booking Details: #' + id); }
</script>
@endsection