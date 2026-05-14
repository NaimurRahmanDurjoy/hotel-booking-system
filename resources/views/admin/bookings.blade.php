@extends('layouts.admin')

@section('title', 'Booking Management')
@section('header_title', 'All Bookings')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Manage Hotel Bookings</h3>
        <div class="filter-group">
            <select id="statusFilter" class="form-control" onchange="filterBookings()">
                <option value="All Statuses" {{ request('status') == 'All Statuses' ? 'selected' : '' }}>All Statuses</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Room Details</th>
                    <th>Stay Dates</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>#BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div style="font-weight: 600;">{{ $booking->user->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $booking->user->email }}</div>
                    </td>
                    <td>
                        <div>Room {{ $booking->room->room_number }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ ucfirst($booking->room->room_type) }}</div>
                    </td>
                    <td>
                        <div>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">
                            {{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays($booking->check_out_date) }} Nights
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 700;">TK {{ number_format($booking->total_price, 2) }}</div>
                        @if($booking->discount_applied > 0)
                            <div style="font-size: 0.75rem; color: var(--accent);">Discount Applied</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 10px;">
                            @if($booking->status === 'pending')
                                <button onclick="updateBookingStatus({{ $booking->id }}, 'confirmed')" class="btn-premium" style="background-color: var(--accent); padding: 5px 10px; font-size: 0.75rem;">Confirm</button>
                                <button onclick="updateBookingStatus({{ $booking->id }}, 'rejected')" class="btn-premium" style="background-color: #E74C3C; padding: 5px 10px; font-size: 0.75rem;">Reject</button>
                            @endif
                            @if($booking->status === 'confirmed')
                                <button onclick="updateBookingStatus({{ $booking->id }}, 'completed')" class="btn-premium" style="background-color: #2ECC71; padding: 5px 10px; font-size: 0.75rem;">Complete</button>
                            @endif
                            <button onclick="viewBookingDetails({{ $booking->id }})" style="border: none; background: none; color: var(--primary); cursor: pointer;" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        <div class="pagination-info">
            Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} bookings
        </div>
        <div>
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="detailsModal" class="modal-overlay">
    <div class="modal-content-card">
        <div class="modal-header-flex">
            <h3><i class="fas fa-info-circle"></i> Booking Details</h3>
            <button class="close-modal" onclick="closeDetailsModal()">&times;</button>
        </div>
        <div id="bookingDetailsContent">
            <!-- Loaded via AJAX -->
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p>Loading details...</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeDetailsModal()">Close</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function filterBookings() {
        const status = document.getElementById('statusFilter').value;
        window.location.href = `{{ route('admin.bookings') }}?status=${status}`;
    }

    const detailsModal = document.getElementById('detailsModal');

    async function viewBookingDetails(id) {
        detailsModal.style.display = 'flex';
        const content = document.getElementById('bookingDetailsContent');
        content.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading details...</p></div>';

        try {
            const response = await fetch(`/api/bookings/${id}`, {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('auth_token')}`, 'Accept': 'application/json' }
            });
            const booking = await response.json();
            
            let servicesHtml = 'None';
            if (booking.services && booking.services.length > 0) {
                servicesHtml = booking.services.map(s => `<li>${s.name} (TK ${s.pivot.price} x ${s.pivot.quantity})</li>`).join('');
                servicesHtml = `<ul style="margin: 0; padding-left: 20px;">${servicesHtml}</ul>`;
            }

            content.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <h5 class="fw-bold mb-2">Customer Info</h5>
                        <p class="mb-1"><strong>Name:</strong> ${booking.user.name}</p>
                        <p class="mb-3"><strong>Email:</strong> ${booking.user.email}</p>
                        
                        <h5 class="fw-bold mb-2">Room Info</h5>
                        <p class="mb-1"><strong>Number:</strong> Room ${booking.room.room_number}</p>
                        <p class="mb-3"><strong>Type:</strong> ${booking.room.room_type}</p>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-2">Stay Info</h5>
                        <p class="mb-1"><strong>Dates:</strong> ${new Date(booking.check_in_date).toLocaleDateString()} - ${new Date(booking.check_out_date).toLocaleDateString()}</p>
                        <p class="mb-3"><strong>Status:</strong> <span class="badge badge-${booking.status}">${booking.status.toUpperCase()}</span></p>
                        
                        <h5 class="fw-bold mb-2">Pricing</h5>
                        <p class="mb-1"><strong>Total:</strong> TK ${parseFloat(booking.total_price).toLocaleString()}</p>
                        <p class="mb-0"><strong>Discount:</strong> TK ${parseFloat(booking.discount_applied).toLocaleString()}</p>
                    </div>
                </div>
                <hr style="margin: 20px 0;">
                <h5 class="fw-bold mb-2">Additional Services</h5>
                <div>${servicesHtml}</div>
                <hr style="margin: 20px 0;">
                <h5 class="fw-bold mb-2">Guest Notes</h5>
                <p class="text-muted" style="font-style: italic;">${booking.notes || 'No notes provided.'}</p>
            `;
        } catch (error) {
            content.innerHTML = '<p class="text-danger text-center">Failed to load booking details.</p>';
        }
    }

    function closeDetailsModal() {
        detailsModal.style.display = 'none';
    }

    async function updateBookingStatus(id, status) {
        const confirmUpdate = await Swal.fire({
            title: `Update Booking Status?`,
            text: `Are you sure you want to mark this booking as ${status.toUpperCase()}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'rejected' ? '#d33' : '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${status}`
        });

        if (!confirmUpdate.isConfirmed) return;
        
        try {
            const response = await fetch(`/api/bookings/${id}`, {
                method: 'PUT',
                headers: { 
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            });
            
            const result = await response.json();
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated',
                    text: result.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Update Failed', text: result.message || 'The booking status could not be updated.' });
            }
        } catch (error) {
            console.error('Update Error:', error);
            Swal.fire({ icon: 'error', title: 'System Error', text: 'An error occurred while communicating with the server.' });
        }
    }
</script>
@endsection
