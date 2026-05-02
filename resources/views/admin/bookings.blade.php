@extends('layouts.admin')

@section('title', 'Booking Management')
@section('header_title', 'All Bookings')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Manage Hotel Bookings</h3>
        <div class="filter-group">
            <select class="btn-premium" style="background-color: var(--white); color: var(--text-dark); border: 1px solid #ddd;">
                <option>All Statuses</option>
                <option>Pending</option>
                <option>Confirmed</option>
                <option>Completed</option>
                <option>Cancelled</option>
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
                            <button style="border: none; background: none; color: var(--primary); cursor: pointer;" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $bookings->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function updateBookingStatus(id, status) {
        if (!confirm(`Are you sure you want to set this booking as ${status}?`)) return;
        
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
                alert(result.message);
                location.reload();
            } else {
                alert(result.message || 'Update failed');
            }
        } catch (error) {
            console.error('Update Error:', error);
            alert('An error occurred while updating the booking.');
        }
    }
</script>
@endsection
