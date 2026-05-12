@extends('layouts.admin')

@section('title', 'Manage Travel Packages')
@section('header_title', 'Travel Packages')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Tour Packages</h3>
        <button onclick="openTravelModal()" class="btn-premium"><i class="fas fa-plus"></i> Create Package</button>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Destination</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $pkg)
                <tr>
                    <td>
                        <img src="{{ $pkg->images[0] ?? 'https://via.placeholder.com/100' }}" alt="{{ $pkg->title }}" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $pkg->title }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ Str::limit($pkg->description, 50) }}</div>
                    </td>
                    <td>{{ $pkg->destination }}</td>
                    <td>{{ $pkg->duration_days }} Days</td>
                    <td>TK {{ number_format($pkg->price, 2) }}</td>
                    <td>
                        <button onclick="editPackage({{ $pkg->id }})" class="btn-icon text-blue"><i class="fas fa-edit"></i></button>
                        <button onclick="deletePackage({{ $pkg->id }})" class="btn-icon text-red"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px;">No travel packages found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 20px;">
            {{ $packages->links() }}
        </div>
    </div>
</div>

<!-- Travel Package Modal -->
<div id="travelModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background: #fff; margin: 5% auto; padding: 30px; width: 60%; border-radius: 15px; max-height: 90vh; overflow-y: auto;">
        <h2 id="modalTitle">Create New Package</h2>
        <form id="travelForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="package_id" name="package_id">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="margin-bottom: 15px;">
                    <label>Package Title</label>
                    <input type="text" name="title" id="title" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Destination</label>
                    <input type="text" name="destination" id="destination" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Price (TK)</label>
                    <input type="number" name="price" id="price" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Duration (Days)</label>
                    <input type="number" name="duration_days" id="duration_days" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;" required>
                </div>
            </div>
            <div style="margin-bottom: 15px;">
                <label>Description</label>
                <textarea name="description" id="description" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px; height: 100px;" required></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label>Package Image</label>
                <input type="file" name="image" class="form-control" style="width: 100%; padding: 10px; margin-top: 5px;">
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeTravelModal()" class="btn-premium" style="background: #666;">Cancel</button>
                <button type="submit" class="btn-premium">Save Package</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function openTravelModal() {
        document.getElementById('travelModal').style.display = 'block';
        document.getElementById('modalTitle').innerText = 'Create New Package';
        document.getElementById('travelForm').reset();
        document.getElementById('package_id').value = '';
    }

    function closeTravelModal() {
        document.getElementById('travelModal').style.display = 'none';
    }

    document.getElementById('travelForm').onsubmit = async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('package_id').value;
        const url = id ? `/manager/travel-packages/${id}` : '/manager/travel-packages';
        
        if (id) {
            formData.append('_method', 'PUT');
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (response.ok) {
                Swal.fire('Success', result.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', result.message || 'Something went wrong', 'error');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Failed to save package', 'error');
        }
    }

    async function editPackage(id) {
        try {
            const response = await fetch(`/api/travel-packages/${id}`);
            const pkg = await response.json();
            
            document.getElementById('package_id').value = pkg.id;
            document.getElementById('title').value = pkg.title;
            document.getElementById('destination').value = pkg.destination;
            document.getElementById('price').value = pkg.price;
            document.getElementById('duration_days').value = pkg.duration_days;
            document.getElementById('description').value = pkg.description;
            
            document.getElementById('modalTitle').innerText = 'Edit Package';
            document.getElementById('travelModal').style.display = 'block';
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Failed to fetch package data', 'error');
        }
    }

    async function deletePackage(id) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This travel package will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/manager/travel-packages/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    Swal.fire('Deleted!', 'Package has been deleted.', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', 'Failed to delete package', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'System error occurred', 'error');
            }
        }
    }
</script>
@endsection
@endsection
