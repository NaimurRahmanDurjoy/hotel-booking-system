<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; background: #1E3A5F; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 15px; display: block; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: #fff; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <h4 class="text-white mb-4"><i class="fas fa-hotel me-2"></i>Luxury Hotel</h4>
                <nav>
                    <a href="/dashboard"><i class="fas fa-home me-2"></i>Dashboard</a>
                    <a href="/bookings" class="active"><i class="fas fa-calendar me-2"></i>My Bookings</a>
                    <a href="/rooms"><i class="fas fa-bed me-2"></i>Rooms</a>
                    <a href="/chat"><i class="fas fa-comments me-2"></i>Messages</a>
                    <a href="/premium"><i class="fas fa-crown me-2"></i>Premium</a>
                    <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                </nav>
            </div>
            <div class="col-md-9 col-lg-10 p-4">
                <h2 class="mb-4">My Bookings</h2>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Room</th>
                                        <th>Type</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
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
        if (!authToken) window.location.href = '/';
        
        document.addEventListener('DOMContentLoaded', loadBookings);
        
        async function loadBookings() {
            try {
                const response = await fetch('/api/bookings/my', {
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                });
                const bookings = await response.json();
                const tbody = document.getElementById('bookingsTable');
                
                if (bookings.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No bookings yet</td></tr>';
                    return;
                }
                
                tbody.innerHTML = bookings.map(booking => {
                    const statusClass = booking.status === 'confirmed' ? 'success' : (booking.status === 'pending' ? 'warning' : 'danger');
                    return `<tr>
                        <td>#${booking.id}</td>
                        <td>Room ${booking.room?.room_number || 'N/A'}</td>
                        <td>${booking.room?.room_type || 'N/A'}</td>
                        <td>${booking.check_in_date}</td>
                        <td>${booking.check_out_date}</td>
                        <td>$${booking.total_price}</td>
                        <td><span class="badge bg-${statusClass}">${booking.status}</span></td>
                        <td>
                            <button onclick="viewBooking(${booking.id})" class="btn btn-sm btn-info text-white">View</button>
                        </td>
                    </tr>`;
                }).join('');
            } catch (error) { console.error('Error:', error); }
        }
        
        function viewBooking(id) { alert('View booking #' + id); }
        function logout() {
            fetch('/api/logout', { method: 'POST', headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' } })
            .then(() => { localStorage.removeItem('auth_token'); window.location.href = '/'; });
        }
    </script>
</body>
</html>