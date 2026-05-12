@extends('layouts.admin')

@section('title', 'Manage Hotels')
@section('header_title', 'My Hotels')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Hotel Properties</h3>
        <button onclick="openHotelModal()" class="btn-premium"><i class="fas fa-plus"></i> Add Hotel</button>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Rooms</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hotels as $hotel)
                <tr>
                    <td>
                        <img src="{{ $hotel->image ?? 'https://via.placeholder.com/100' }}" alt="{{ $hotel->name }}" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $hotel->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $hotel->description }}</div>
                    </td>
                    <td>{{ $hotel->city }}, {{ $hotel->address }}</td>
                    <td>{{ $hotel->rooms_count }} Rooms</td>
                    <td>
                        <button onclick="editHotel({{ $hotel->id }})" class="btn-icon text-blue"><i class="fas fa-edit"></i></button>
                        <button onclick="deleteHotel({{ $hotel->id }})" class="btn-icon text-red"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">No hotels found. Add your first hotel to get started.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Simple Hotel Modal (Logic handled by controller) -->
<div id="hotelModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background: #fff; margin: 10% auto; padding: 30px; width: 50%; border-radius: 15px;">
        <h2 id="modalTitle">Add New Hotel</h2>
        <form id="hotelForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="hotel_id" name="hotel_id">
            <div style="margin-bottom: 15px;">
                <label>Hotel Name</label>
                <input type="text" name="name" id="name" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
            </div>
            <div style="margin-bottom: 15px;">
                <label>City</label>
                <input type="text" name="city" id="city" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
            </div>
            <div style="margin-bottom: 15px;">
                <label>Address</label>
                <input type="text" name="address" id="address" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
            </div>
            <div style="margin-bottom: 15px;">
                <label>Description</label>
                <textarea name="description" id="description" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;"></textarea>
            </div>
            <div style="margin-bottom: 15px;">
                <label>Hotel Image</label>
                <input type="file" name="image" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;">
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeHotelModal()" class="btn-premium" style="background: #666;">Cancel</button>
                <button type="submit" class="btn-premium">Save Hotel</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function openHotelModal() {
        document.getElementById('hotelModal').style.display = 'block';
        document.getElementById('modalTitle').innerText = 'Add New Hotel';
        document.getElementById('hotelForm').reset();
        document.getElementById('hotel_id').value = '';
    }

    function closeHotelModal() {
        document.getElementById('hotelModal').style.display = 'none';
    }

    document.getElementById('hotelForm').onsubmit = async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('hotel_id').value;
        const url = id ? `/manager/hotels/${id}` : '/manager/hotels';
        
        if (id) formData.append('_method', 'PUT');

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                location.reload();
            } else {
                alert('Error saving hotel');
            }
        } catch (error) {
            console.error(error);
        }
    }

    async function editHotel(id) {
        try {
            const response = await fetch(`/api/hotels/${id}`);
            const hotel = await response.json();
            
            document.getElementById('hotel_id').value = hotel.id;
            document.getElementById('name').value = hotel.name;
            document.getElementById('city').value = hotel.city;
            document.getElementById('address').value = hotel.address;
            document.getElementById('description').value = hotel.description;
            
            document.getElementById('modalTitle').innerText = 'Edit Hotel';
            document.getElementById('hotelModal').style.display = 'block';
        } catch (error) {
            console.error(error);
            alert('Error fetching hotel data');
        }
    }

    async function deleteHotel(id) {
        if (confirm('Are you sure you want to delete this hotel? All rooms and bookings will be lost!')) {
            const response = await fetch(`/manager/hotels/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) location.reload();
        }
    }
</script>
@endsection
@endsection
