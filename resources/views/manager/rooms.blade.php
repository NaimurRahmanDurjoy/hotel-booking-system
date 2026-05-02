@extends('layouts.admin')

@section('title', 'Room Management')
@section('header_title', 'Room Management')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>All Rooms</h3>
        <button class="btn-premium" onclick="showAddRoomModal()"><i class="fas fa-plus"></i> Add New Room</button>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Capacity</th>
                    <th>Price/Night</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                <tr id="room-row-{{ $room->id }}">
                    <td class="fw-bold text-primary">#{{ $room->room_number }}</td>
                    <td style="text-transform: capitalize;">{{ $room->room_type }}</td>
                    <td>{{ $room->capacity }} Guests</td>
                    <td>TK {{ $room->price_per_night }}</td>
                    <td>
                        <span class="badge {{ $room->status === 'available' ? 'badge-confirmed' : ($room->status === 'occupied' ? 'badge-pending' : 'badge-danger') }}" style="text-transform: capitalize;">
                            {{ $room->status }}
                        </span>
                    </td>
                    <td>
                        <button onclick="editRoom({{ $room->id }})" style="border: none; background: none; color: var(--primary); cursor: pointer; margin-right: 10px;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteRoom({{ $room->id }}, '{{ $room->room_number }}')" style="border: none; background: none; color: #E74C3C; cursor: pointer;" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $rooms->links() }}
    </div>
</div>

<!-- Add/Edit Room Modal -->
<div id="roomModal" class="modal-overlay">
    <div class="modal-content-card">
        <div class="modal-header-flex">
            <h3 id="modalTitle">Add New Room</h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <form id="roomForm">
            <input type="hidden" id="roomId">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Room Number</label>
                    <input type="text" id="roomNumber" class="form-control" required placeholder="e.g. 101">
                </div>
                <div class="col-md-6 form-group">
                    <label>Room Type</label>
                    <select id="roomType" class="form-control" required>
                        <option value="standard">Standard</option>
                        <option value="deluxe">Deluxe</option>
                        <option value="suite">Suite</option>
                        <option value="presidential">Presidential</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Room Image</label>
                <div id="currentImagePreview" class="mb-2 d-none">
                    <img src="" id="previewImg" class="rounded shadow-sm" style="height: 100px; width: 150px; object-fit: cover;">
                    <p class="small text-muted mb-0">Current Image</p>
                </div>
                <input type="file" id="roomImage" class="form-control" accept="image/*">
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Price Per Night (TK)</label>
                    <input type="number" id="pricePerNight" class="form-control" step="0.01" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Capacity (Guests)</label>
                    <input type="number" id="capacity" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>Amenities (Comma separated)</label>
                <input type="text" id="amenities" class="form-control" placeholder="WiFi, Breakfast, TV, Air Conditioning">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select id="roomStatus" class="form-control" required>
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Room</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const roomModal = document.getElementById('roomModal');
    const roomForm = document.getElementById('roomForm');
    let isEditing = false;

    function showAddRoomModal() {
        isEditing = false;
        document.getElementById('modalTitle').textContent = 'Add New Room';
        document.getElementById('userId')?.remove(); // Cleanup if exists
        document.getElementById('roomId').value = '';
        document.getElementById('currentImagePreview').classList.add('d-none');
        roomForm.reset();
        roomModal.style.display = 'flex';
    }

    async function editRoom(id) {
        isEditing = true;
        document.getElementById('modalTitle').textContent = 'Edit Room';
        document.getElementById('roomId').value = id;
        
        try {
            const response = await fetch(`/api/rooms/${id}`, {
                headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${localStorage.getItem('auth_token')}` }
            });
            const room = await response.json();
            
            document.getElementById('roomNumber').value = room.room_number;
            document.getElementById('roomType').value = room.room_type;
            document.getElementById('description').value = room.description;
            document.getElementById('pricePerNight').value = room.price_per_night;
            document.getElementById('capacity').value = room.capacity;
            document.getElementById('roomStatus').value = room.status;
            document.getElementById('amenities').value = Array.isArray(room.amenities) ? room.amenities.join(', ') : (room.amenities || '');

            if (room.image) {
                const imgSrc = room.image.startsWith('http') ? room.image : (window.location.origin + (room.image.startsWith('/') ? '' : '/') + room.image);
                document.getElementById('previewImg').src = imgSrc;
                document.getElementById('currentImagePreview').classList.remove('d-none');
            } else {
                document.getElementById('currentImagePreview').classList.add('d-none');
            }
            
            roomModal.style.display = 'flex';
        } catch (error) {
            alert('Failed to fetch room data');
        }
    }

    // New Image Preview Logic
    document.getElementById('roomImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('previewImg').src = event.target.result;
                document.getElementById('currentImagePreview').classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    function closeModal() {
        roomModal.style.display = 'none';
    }

    roomForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('roomId').value;
        const url = isEditing ? `/api/rooms/${id}` : '/api/rooms';
        
        const formData = new FormData();
        formData.append('room_number', document.getElementById('roomNumber').value);
        formData.append('room_type', document.getElementById('roomType').value);
        formData.append('description', document.getElementById('description').value);
        formData.append('price_per_night', document.getElementById('pricePerNight').value);
        formData.append('capacity', document.getElementById('capacity').value);
        formData.append('status', document.getElementById('roomStatus').value);
        
        const amenitiesInput = document.getElementById('amenities').value;
        const amenitiesArray = amenitiesInput.split(',').map(s => s.trim()).filter(s => s !== '');
        amenitiesArray.forEach((amenity, index) => {
            formData.append(`amenities[${index}]`, amenity);
        });
        
        const imageFile = document.getElementById('roomImage').files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        if (isEditing) {
            formData.append('_method', 'PUT');
        }

        try {
            const response = await fetch(url, {
                method: 'POST', // Use POST with _method PUT for file uploads
                headers: { 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            
            const result = await response.json();
            if (response.ok) {
                alert(result.message);
                location.reload();
            } else {
                alert(result.message || 'Action failed');
            }
        } catch (error) {
            alert('An error occurred');
        }
    });

    async function deleteRoom(id, roomNumber) {
        if (!confirm(`Are you sure you want to delete Room ${roomNumber}?`)) return;
        
        try {
            const response = await fetch(`/api/rooms/${id}`, {
                method: 'DELETE',
                headers: { 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (response.ok) {
                alert('Room deleted successfully');
                document.getElementById(`room-row-${id}`).remove();
            } else {
                const result = await response.json();
                alert(result.message || 'Delete failed');
            }
        } catch (error) {
            alert('An error occurred');
        }
    }
</script>
@endsection
