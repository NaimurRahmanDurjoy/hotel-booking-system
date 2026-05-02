<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; background: #1E3A5F; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 15px; display: block; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: #fff; }
        .room-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .room-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); }
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
                    <a href="/rooms" class="active"><i class="fas fa-bed me-2"></i>Rooms</a>
                    <a href="/chat"><i class="fas fa-comments me-2"></i>Messages</a>
                    <a href="/premium"><i class="fas fa-crown me-2"></i>Premium</a>
                    <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                </nav>
            </div>
            <div class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">Available Rooms</h2>
                        <p class="text-muted">Choose the perfect room for your stay.</p>
                    </div>
                    <button class="btn btn-primary" onclick="location.href='/dashboard'">Back to Dashboard</button>
                </div>
                <div class="row g-4" id="roomsGrid"></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const authToken = localStorage.getItem('auth_token');
        if (!authToken) window.location.href = '/';

        document.addEventListener('DOMContentLoaded', loadRooms);

        async function loadRooms() {
            try {
                const response = await fetch('/api/rooms', {
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                });
                const rooms = await response.json();
                const grid = document.getElementById('roomsGrid');

                if (!rooms.length) {
                    grid.innerHTML = '<div class="col-12 text-center text-muted">No rooms available</div>';
                    return;
                }

                grid.innerHTML = rooms.map(room => `
                    <div class="col-md-6 col-xl-4">
                        <div class="card room-card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title fw-bold">Room ${room.room_number}</h5>
                                        <span class="badge bg-primary">${room.room_type}</span>
                                    </div>
                                    <div class="text-end">
                                        <p class="h4 text-primary mb-0">$${room.price_per_night}</p>
                                        <small class="text-muted">/night</small>
                                    </div>
                                </div>
                                <p class="card-text text-muted">Capacity: ${room.capacity} guests</p>
                                <p class="card-text text-muted">Status: <strong>${room.status}</strong></p>
                                <p class="card-text text-muted">${room.amenities || 'Includes Wi-Fi, breakfast and more.'}</p>
                                <button onclick="bookRoom(${room.id})" class="btn btn-primary w-100">Book Now</button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error loading rooms:', error);
            }
        }

        function bookRoom(roomId) {
            if (!authToken) {
                window.location.href = '/';
                return;
            }
            localStorage.setItem('selectedRoomId', roomId);
            window.location.href = '/dashboard';
        }

        function logout() {
            fetch('/api/logout', { method: 'POST', headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' } })
                .then(() => { localStorage.removeItem('auth_token'); window.location.href = '/'; });
        }
    </script>
</body>
</html>