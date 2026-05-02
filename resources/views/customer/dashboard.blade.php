@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-12">
            <h2 class="fw-bold mb-4">Welcome back, {{ Auth::user()->name }}!</h2>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-calendar-check text-primary fa-2x"></i>
                        </div>
                        <h4 class="mb-0">My Bookings</h4>
                    </div>
                    <p class="text-muted">View your current and past room reservations.</p>
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary w-100">View Bookings</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-crown text-warning fa-2x"></i>
                        </div>
                        <h4 class="mb-0">Premium</h4>
                    </div>
                    <p class="text-muted">Check your membership status and benefits.</p>
                    <a href="{{ route('premium') }}" class="btn btn-outline-warning w-100">Membership Info</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                            <i class="fas fa-comment-dots text-info fa-2x"></i>
                        </div>
                        <h4 class="mb-0">Messages</h4>
                    </div>
                    <p class="text-muted">Chat with our staff about your stay.</p>
                    <a href="{{ route('chat') }}" class="btn btn-outline-info w-100">Open Chat</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection