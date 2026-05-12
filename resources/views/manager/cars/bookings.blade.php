@extends('layouts.admin')

@section('title', 'Manage Car Bookings')
@section('header_title', 'Car Rental Bookings')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Incoming Car Reservations</h3>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Car</th>
                    <th>Dates</th>
                    <th>Locations</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $booking->user->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $booking->user->email }}</div>
                    </td>
                    <td>{{ $booking->car->name }}</td>
                    <td>
                        <div style="font-size: 0.9rem;">{{ \Carbon\Carbon::parse($booking->pickup_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->return_date)->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <div style="font-size: 0.8rem;">Pick: {{ $booking->pickup_city }}</div>
                        <div style="font-size: 0.8rem;">Drop: {{ $booking->dropoff_city }}</div>
                    </td>
                    <td>TK {{ number_format($booking->total_price, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('manager.car_bookings.update', $booking->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="form-control" style="font-size: 0.8rem; padding: 5px;">
                                <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirm</option>
                                <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Complete</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancel</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">No car bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 20px;">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
