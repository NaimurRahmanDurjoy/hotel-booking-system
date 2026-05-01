<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; background: #1E3A5F; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 15px; display: block; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: #fff; }
        .stat-card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <h4 class="text-white mb-4"><i class="fas fa-hotel me-2"></i>Luxury Hotel</h4>
                <nav>
                    <a href="/dashboard" class="active"><i class="fas fa-home me-2"></i>Dashboard</a>
                    <a href="/bookings"><i class="fas fa-calendar me-2"></i>My Bookings</a>
                    <a href="/rooms"><i class="fas fa-bed me-2"></i>Rooms</a>
                    <a href="/chat"><i class="fas fa-comments me-2"></i>Messages</a>
                    <a href="/premium"><i class="fas fa-crown me-2"></i>Premium</a>
                    <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <h2 class="mb-4">Welcome, <span id="userName">User</span>!</h2>
                
                <!-- Stats Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card bg-primary text-white">
                            <div class="card-body">
                                <h6>Total Bookings</h6>
                                <h2 id="totalBookings">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-success text-white">
                            <div class="card-body">
                                <h6>Confirmed</h6>
                                <h2 id="confirmedBookings">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-warning text-white">
                            <div class="card-body">
                                <h6>Pending</h6>
                                <h2 id="pendingBookings">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card bg-info text-white">
                            <div class="card-body">
                                <h6>Premium Status</h6>
                                <h6 id="premiumStatus">Free</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Recent Bookings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Room</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="bookingsTable"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const authToken = localStorage.getItem('auth_token');
        
        if (!authToken) {
            window.location.href = '/';
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchUser();
            loadBookings();
            checkPremium();
        });

        async function fetchUser() {
            try {
                const response = await fetch('/api/user', {
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                });
                if (response.ok) {
                    const user = await response.json();
                    document.getElementById('userName').textContent = user.name;
                }
            } catch (error) { console.error('Error:', error); }
        }

        async function loadBookings() {
            try {
                const response = await fetch('/api/bookings/my', {
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                });
                const bookings = await response.json();
                
                let total = 0, confirmed = 0, pending = 0;
                const tbody = document.getElementById('bookingsTable');
                
                if (bookings.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No bookings yet</td></tr>';
                } else {
                    tbody.innerHTML = bookings.slice(0, 5).map(booking => {
                        total++;
                        if (booking.status === 'confirmed') confirmed++;
                        if (booking.status === 'pending') pending++;
                        const statusClass = booking.status === 'confirmed' ? 'success' : (booking.status === 'pending' ? 'warning' : 'danger');
                        return `<tr>
                            <td>Room ${booking.room?.room_number || 'N/A'}</td>
                            <td>${booking.check_in_date}</td>
                            <td>${booking.check_out_date}</td>
                            <td>$${booking.total_price}</td>
                            <td><span class="badge bg-${statusClass}">${booking.status}</span></td>
                        </tr>`;
                    }).join('');
                }
                
                document.getElementById('totalBookings').textContent = total;
                document.getElementById('confirmedBookings').textContent = confirmed;
                document.getElementById('pendingBookings').textContent = pending;
            } catch (error) { console.error('Error:', error); }
        }

        async function checkPremium() {
            try {
                const response = await fetch('/api/premium/status', {
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (data.premium) {
                    document.getElementById('premiumStatus').textContent = data.discount + '% Discount';
                }
            } catch (error) { console.error('Error:', error); }
        }

        function logout() {
            fetch('/api/logout', {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
            }).then(() => {
                localStorage.removeItem('auth_token');
                window.location.href = '/';
            });
        }
    </script>
</body>
</html>