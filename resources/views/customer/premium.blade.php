<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium - Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; background: #1E3A5F; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 15px; display: block; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: #fff; }
        .plan-card { border: none; border-radius: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <h4 class="text-white mb-4"><i class="fas fa-hotel me-2"></i>Luxury Hotel</h4>
                <nav>
                    <a href="/dashboard"><i class="fas fa-home me-2"></i>Dashboard</a>
                    <a href="/bookings"><i class="fas fa-calendar me-2"></i>My Bookings</a>
                    <a href="/rooms"><i class="fas fa-bed me-2"></i>Rooms</a>
                    <a href="/chat"><i class="fas fa-comments me-2"></i>Messages</a>
                    <a href="/premium" class="active"><i class="fas fa-crown me-2"></i>Premium</a>
                    <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                </nav>
            </div>
            <div class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">Premium Membership</h2>
                        <p class="text-muted">Unlock member discounts and priority service.</p>
                    </div>
                    <button class="btn btn-primary" onclick="location.href='/dashboard'">Back to Dashboard</button>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card plan-card p-4">
                            <div class="mb-4">
                                <span class="badge bg-secondary">Silver</span>
                            </div>
                            <h3 class="fw-bold">5% Discount</h3>
                            <p class="text-muted">Affordable premium access for guests who want more value.</p>
                            <ul class="list-unstyled text-muted mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>5% off all bookings</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Priority customer support</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Exclusive deals</li>
                            </ul>
                            <button class="btn btn-outline-primary w-100" onclick="subscribePremium('silver')">Subscribe</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card plan-card p-4 border-primary">
                            <div class="mb-4">
                                <span class="badge bg-warning text-dark">Gold</span>
                            </div>
                            <h3 class="fw-bold">10% Discount</h3>
                            <p class="text-muted">Best value membership with extra service benefits.</p>
                            <ul class="list-unstyled text-muted mb-4">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>10% off all bookings</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Priority service and upgrades</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Special offers and access</li>
                            </ul>
                            <button class="btn btn-primary w-100" onclick="subscribePremium('gold')">Subscribe</button>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5>Your Current Status</h5>
                        <p id="currentStatus" class="text-muted">Loading...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const authToken = localStorage.getItem('auth_token');
        if (!authToken) window.location.href = '/';

        document.addEventListener('DOMContentLoaded', loadStatus);

        async function loadStatus() {
            try {
                const response = await fetch('/api/premium/status', {
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                });
                const data = await response.json();
                const status = data.premium ? `${data.discount}% discount active` : 'No premium subscription yet.';
                document.getElementById('currentStatus').textContent = status;
            } catch (error) {
                console.error('Error loading premium status:', error);
                document.getElementById('currentStatus').textContent = 'Unable to load status.';
            }
        }

        async function subscribePremium(tier) {
            if (!confirm(`Subscribe to ${tier} premium for 1 month?`)) return;
            try {
                const response = await fetch('/api/premium/subscribe', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' },
                    body: JSON.stringify({ tier: tier, duration_months: 1 })
                });
                const data = await response.json();
                if (response.ok) {
                    alert(data.message);
                    loadStatus();
                } else {
                    alert(data.message || 'Subscription failed');
                }
            } catch (error) {
                console.error('Error subscribing:', error);
                alert('Subscription failed. Please try again.');
            }
        }

        function logout() {
            fetch('/api/logout', { method: 'POST', headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' } })
                .then(() => { localStorage.removeItem('auth_token'); window.location.href = '/'; });
        }
    </script>
</body>
</html>