@extends('layouts.admin')

@section('title', 'Manage Car Rentals')
@section('header_title', 'Car Fleet')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Rental Vehicles</h3>
        <button onclick="openCarModal()" class="btn-premium"><i class="fas fa-plus"></i> Add Vehicle</button>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Car Name</th>
                    <th>Brand</th>
                    <th>Type</th>
                    <th>Price/Day</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cars as $car)
                <tr>
                    <td>
                        <img src="{{ $car->image ?? 'https://via.placeholder.com/100' }}" alt="{{ $car->name }}" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $car->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $car->model_year }} | {{ ucfirst($car->transmission) }}</div>
                    </td>
                    <td>{{ $car->brand }}</td>
                    <td>{{ ucfirst($car->type) }}</td>
                    <td>TK {{ number_format($car->price_per_day, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $car->status === 'available' ? 'confirmed' : 'pending' }}">
                            {{ ucfirst($car->status) }}
                        </span>
                    </td>
                    <td>
                        <button onclick="editCar({{ $car->id }})" class="btn-icon text-blue"><i class="fas fa-edit"></i></button>
                        <button onclick="deleteCar({{ $car->id }})" class="btn-icon text-red"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px;">No vehicles found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 20px;">
            {{ $cars->links() }}
        </div>
    </div>
</div>

<!-- Car Modal -->
<div id="carModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background: #fff; margin: 5% auto; padding: 30px; width: 60%; border-radius: 15px; max-height: 90vh; overflow-y: auto;">
        <h2 id="modalTitle">Add New Vehicle</h2>
        <form id="carForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="car_id" name="car_id">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="margin-bottom: 15px;">
                    <label>Vehicle Name</label>
                    <input type="text" name="name" id="name" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Brand</label>
                    <input type="text" name="brand" id="brand" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Model Year</label>
                    <input type="text" name="model_year" id="model_year" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Daily Price (TK)</label>
                    <input type="number" name="price_per_day" id="price_per_day" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Type</label>
                    <select name="type" id="type" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                        <option value="sedan">Sedan</option>
                        <option value="suv">SUV</option>
                        <option value="microbus">Microbus</option>
                        <option value="luxury">Luxury</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Capacity (Seats)</label>
                    <input type="number" name="capacity" id="capacity" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Transmission</label>
                    <select name="transmission" id="transmission" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                        <option value="auto">Automatic</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Fuel Type</label>
                    <select name="fuel_type" id="fuel_type" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                        <option value="octane">Octane</option>
                        <option value="diesel">Diesel</option>
                        <option value="hybrid">Hybrid</option>
                        <option value="electric">Electric</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom: 15px;">
                <label>Description</label>
                <textarea name="description" id="description" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px; height: 80px;"></textarea>
            </div>
            <div style="margin-bottom: 15px;">
                <label>Status</label>
                <select name="status" id="status" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;">
                    <option value="available">Available</option>
                    <option value="booked">Booked</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label>Vehicle Image</label>
                <input type="file" name="image" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;">
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeCarModal()" class="btn-premium" style="background: #666;">Cancel</button>
                <button type="submit" class="btn-premium">Save Vehicle</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function openCarModal() {
        document.getElementById('carModal').style.display = 'block';
        document.getElementById('modalTitle').innerText = 'Add New Vehicle';
        document.getElementById('carForm').reset();
        document.getElementById('car_id').value = '';
    }

    function closeCarModal() {
        document.getElementById('carModal').style.display = 'none';
    }

    document.getElementById('carForm').onsubmit = async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('car_id').value;
        const url = id ? `/manager/cars/${id}` : '/manager/cars';
        
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
                Swal.fire('Success', 'Vehicle saved successfully', 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', 'Failed to save vehicle', 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'System error occurred', 'error');
        }
    }

    async function editCar(id) {
        try {
            const response = await fetch(`/manager/cars/${id}`);
            const car = await response.json();
            
            document.getElementById('car_id').value = car.id;
            document.getElementById('name').value = car.name;
            document.getElementById('brand').value = car.brand;
            document.getElementById('model_year').value = car.model_year;
            document.getElementById('price_per_day').value = car.price_per_day;
            document.getElementById('type').value = car.type;
            document.getElementById('capacity').value = car.capacity;
            document.getElementById('transmission').value = car.transmission;
            document.getElementById('fuel_type').value = car.fuel_type;
            document.getElementById('description').value = car.description;
            document.getElementById('status').value = car.status;
            
            document.getElementById('modalTitle').innerText = 'Edit Vehicle';
            document.getElementById('carModal').style.display = 'block';
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Failed to fetch car data', 'error');
        }
    }

    async function deleteCar(id) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This vehicle will be removed from your fleet!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            const response = await fetch(`/manager/cars/${id}`, {
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
