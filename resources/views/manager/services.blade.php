@extends('layouts.admin')

@section('title', 'Service Management')
@section('header_title', 'Service Management')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>All Services</h3>
        <button class="btn-premium" onclick="showAddServiceModal()"><i class="fas fa-plus"></i> Add New Service</button>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr id="service-row-{{ $service->id }}">
                    <td class="fw-bold text-primary">{{ $service->name }}</td>
                    <td style="max-width: 300px;"><div class="text-truncate" title="{{ $service->description }}">{{ $service->description }}</div></td>
                    <td>TK {{ number_format($service->price, 2) }}</td>
                    <td>
                        <span class="badge {{ $service->is_available ? 'badge-confirmed' : 'badge-danger' }}">
                            {{ $service->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </td>
                    <td>
                        <button onclick="editService({{ $service->id }})" style="border: none; background: none; color: var(--primary); cursor: pointer; margin-right: 10px;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteService({{ $service->id }}, '{{ $service->name }}')" style="border: none; background: none; color: #E74C3C; cursor: pointer;" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $services->links() }}
    </div>
</div>

<!-- Add/Edit Service Modal -->
<div id="serviceModal" class="modal-overlay">
    <div class="modal-content-card">
        <div class="modal-header-flex">
            <h3 id="modalTitle">Add New Service</h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <form id="serviceForm">
            <input type="hidden" id="serviceId">
            <div class="form-group">
                <label>Service Name</label>
                <input type="text" id="serviceName" class="form-control" required placeholder="e.g. Airport Transfer">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="serviceDescription" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Service Image</label>
                <div id="currentImagePreview" class="mb-2 d-none">
                    <img src="" id="previewImg" class="rounded shadow-sm" style="height: 100px; width: 150px; object-fit: cover;">
                    <p class="small text-muted mb-0">Current Image</p>
                </div>
                <input type="file" id="serviceImage" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label>Price (TK)</label>
                <input type="number" id="servicePrice" class="form-control" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select id="serviceStatus" class="form-control" required>
                    <option value="1">Available</option>
                    <option value="0">Unavailable</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Service</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const serviceModal = document.getElementById('serviceModal');
    const serviceForm = document.getElementById('serviceForm');
    let isEditing = false;

    function showAddServiceModal() {
        isEditing = false;
        document.getElementById('modalTitle').textContent = 'Add New Service';
        document.getElementById('serviceId').value = '';
        document.getElementById('currentImagePreview').classList.add('d-none');
        serviceForm.reset();
        serviceModal.style.display = 'flex';
    }

    async function editService(id) {
        isEditing = true;
        document.getElementById('modalTitle').textContent = 'Edit Service';
        document.getElementById('serviceId').value = id;
        
        try {
            const response = await fetch(`/api/services/${id}`, {
                headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${localStorage.getItem('auth_token')}` }
            });
            const service = await response.json();
            
            document.getElementById('serviceName').value = service.name;
            document.getElementById('serviceDescription').value = service.description;
            document.getElementById('servicePrice').value = service.price;
            document.getElementById('serviceStatus').value = service.is_available ? "1" : "0";

            if (service.image) {
                const imgSrc = service.image.startsWith('http') ? service.image : (window.location.origin + (service.image.startsWith('/') ? '' : '/') + service.image);
                document.getElementById('previewImg').src = imgSrc;
                document.getElementById('currentImagePreview').classList.remove('d-none');
            } else {
                document.getElementById('currentImagePreview').classList.add('d-none');
            }
            
            serviceModal.style.display = 'flex';
        } catch (error) {
            alert('Failed to fetch service data');
        }
    }

    // New Image Preview Logic
    document.getElementById('serviceImage').addEventListener('change', function(e) {
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
        serviceModal.style.display = 'none';
    }

    serviceForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('serviceId').value;
        const url = isEditing ? `/api/services/${id}` : '/api/services';
        
        const formData = new FormData();
        formData.append('name', document.getElementById('serviceName').value);
        formData.append('description', document.getElementById('serviceDescription').value);
        formData.append('price', document.getElementById('servicePrice').value);
        formData.append('is_available', document.getElementById('serviceStatus').value === "1" ? "1" : "0");
        
        const imageFile = document.getElementById('serviceImage').files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        if (isEditing) {
            formData.append('_method', 'PUT');
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
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

    async function deleteService(id, name) {
        if (!confirm(`Are you sure you want to delete service "${name}"?`)) return;
        
        try {
            const response = await fetch(`/api/services/${id}`, {
                method: 'DELETE',
                headers: { 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (response.ok) {
                alert('Service deleted successfully');
                document.getElementById(`service-row-${id}`).remove();
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
