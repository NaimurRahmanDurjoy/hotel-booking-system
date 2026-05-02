@extends('layouts.frontend')

@section('styles')
<style>
    .plan-card { border: none; border-radius: 20px; transition: all 0.3s; background: white; overflow: hidden; }
    .plan-card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
    .status-card { border: none; border-radius: 15px; background: #f8f9fa; border-left: 5px solid #1E3A5F; }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Premium Membership</li>
                </ol>
            </nav>
            
            <div class="text-center mb-5">
                <h2 class="fw-bold display-5">Unlock <span class="text-primary">Premium</span> Benefits</h2>
                <p class="text-muted lead">Choose the plan that fits your travel style and enjoy exclusive discounts.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-md-5 col-lg-4">
                    <div class="card plan-card h-100 shadow-sm">
                        <div class="card-body p-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-bold">SILVER</span>
                                <i class="fas fa-crown text-secondary fa-2x opacity-25"></i>
                            </div>
                            <h3 class="fw-bold mb-1">5% OFF</h3>
                            <p class="text-muted small mb-4">Per booking discount</p>
                            
                            <hr class="my-4 opacity-50">
                            
                            <ul class="list-unstyled mb-5">
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>5% off all room bookings</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>Priority customer support</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>Early check-in availability</li>
                            </ul>
                            
                            <button onclick="subscribePremium('silver')" class="btn btn-outline-primary w-100 py-3 fw-bold">Get Silver Access</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 col-lg-4">
                    <div class="card plan-card h-100 shadow-sm border-primary border-2">
                        <div class="card-body p-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-bold">GOLD</span>
                                <i class="fas fa-crown text-warning fa-2x"></i>
                            </div>
                            <h3 class="fw-bold mb-1">10% OFF</h3>
                            <p class="text-muted small mb-4">Maximum value & benefits</p>
                            
                            <hr class="my-4 opacity-50">
                            
                            <ul class="list-unstyled mb-5">
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>10% off all room bookings</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>Free room upgrades (if available)</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>Exclusive lounge access</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>Late check-out guaranteed</li>
                            </ul>
                            
                            <button onclick="subscribePremium('gold')" class="btn btn-primary w-100 py-3 fw-bold shadow">Get Gold Access</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5 justify-content-center">
                <div class="col-lg-8">
                    <div class="card status-card p-4">
                        <div class="d-flex align-items-center">
                            <div class="me-4">
                                <i class="fas fa-info-circle text-primary fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Your Current Status</h5>
                                <p id="currentStatus" class="mb-0 text-muted">Checking your subscription status...</p>
                            </div>
                        </div>
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
    
    document.addEventListener('DOMContentLoaded', loadStatus);

    async function loadStatus() {
        try {
            const response = await fetch('/api/premium/status', {
                headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
            });
            const data = await response.json();
            const statusText = data.premium 
                ? `<span class="text-primary fw-bold">${data.discount}% discount</span> is currently active on your account.` 
                : 'You don\'t have an active premium subscription yet. Choose a plan above to start saving.';
            document.getElementById('currentStatus').innerHTML = statusText;
        } catch (error) {
            document.getElementById('currentStatus').textContent = 'Unable to load subscription status.';
        }
    }

    async function subscribePremium(tier) {
        const result = await Swal.fire({
            title: `Enroll in ${tier.toUpperCase()}?`,
            text: `Do you want to subscribe to the ${tier.toUpperCase()} membership tier?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1E3A5F',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Enroll Now!'
        });

        if (!result.isConfirmed) return;
        
        try {
            const response = await fetch('/api/premium/subscribe', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' },
                body: JSON.stringify({ tier: tier, duration_months: 1 })
            });
            const data = await response.json();
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Welcome to Elite Status!',
                    text: 'Your premium subscription has been activated successfully.',
                    confirmButtonColor: '#1E3A5F'
                });
                loadStatus();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Subscription Failed',
                    text: data.message || 'Something went wrong while processing your request.'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Connection Error',
                text: 'Please check your internet connection and try again.'
            });
        }
    }
</script>
@endsection