@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'Dashboard Overview')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>Total Users</h3>
            <p>{{ number_format($stats['total_users']) }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-gold">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <h3>Total Bookings</h3>
            <p>{{ number_format($stats['total_bookings']) }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-green">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-info">
            <h3>Total Revenue</h3>
            <p>TK {{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-blue">
            <i class="fas fa-door-open"></i>
        </div>
        <div class="stat-info">
            <h3>Available Rooms</h3>
            <p>{{ $stats['available_rooms'] }} / {{ $stats['total_rooms'] }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-blue">
            <i class="fas fa-map-marked-alt"></i>
        </div>
        <div class="stat-info">
            <h3>Travel Packages</h3>
            <p>{{ $stats['total_travel_packages'] }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-gold">
            <i class="fas fa-route"></i>
        </div>
        <div class="stat-info">
            <h3>Tour Bookings</h3>
            <p>{{ $stats['total_travel_bookings'] }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-green">
            <i class="fas fa-car"></i>
        </div>
        <div class="stat-info">
            <h3>Car Fleet</h3>
            <p>{{ $stats['total_cars'] }}</p>
        </div>
    </div>
</div>

<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Recent Bookings</h3>
        <a href="{{ route('admin.bookings') }}" class="btn-premium" style="text-decoration: none; font-size: 0.8rem;">View All</a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Room</th>
                    <th>Dates</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $booking)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $booking->user->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $booking->user->email }}</div>
                    </td>
                    <td>{{ $booking->room->room_number }} ({{ ucfirst($booking->room->room_type) }})</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                    <td>TK{{ number_format($booking->total_price, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">No recent bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
