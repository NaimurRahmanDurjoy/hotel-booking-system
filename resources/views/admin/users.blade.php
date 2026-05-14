@extends('layouts.admin')

@section('title', 'User Management')
@section('header_title', 'User Management')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>All Registered Users</h3>
        <button class="btn-premium" onclick="showAddUserModal()"><i class="fas fa-user-plus"></i> Add New User</button>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name & Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Premium Status</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr id="user-row-{{ $user->id }}">
                    <td>
                        <div style="font-weight: 600;">{{ $user->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $user->email }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $user->role === 'admin' ? 'badge-confirmed' : ($user->role === 'manager' ? 'badge-pending' : '') }}" style="text-transform: capitalize;">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td>{{ $user->phone ?? 'N/A' }}</td>
                    <td>
                        @if($user->is_premium)
                            <span class="badge badge-premium">
                                <i class="fas fa-crown"></i> {{ ucfirst($user->premium_tier) }}
                            </span>
                        @else
                            <span style="color: var(--text-muted);">Standard</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <button onclick="editUser({{ $user->id }})" style="border: none; background: none; color: var(--primary); cursor: pointer; margin-right: 10px;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        @if(!$user->isAdmin())
                        <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" style="border: none; background: none; color: #E74C3C; cursor: pointer;" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        <div class="pagination-info">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div id="userModal" class="modal-overlay">
    <div class="modal-content-card">
        <div class="modal-header-flex">
            <h3 id="modalTitle" style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-user-circle"></i> Add New User
            </h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <form id="userForm">
            <input type="hidden" id="userId">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label><i class="fas fa-id-card me-1"></i> Full Name</label>
                    <input type="text" id="userName" class="form-control" required placeholder="John Doe">
                </div>
                <div class="col-md-6 form-group">
                    <label><i class="fas fa-envelope me-1"></i> Email Address</label>
                    <input type="email" id="userEmail" class="form-control" required placeholder="john@example.com">
                </div>
            </div>
            <div class="form-group" id="passwordGroup">
                <label><i class="fas fa-lock me-1"></i> Password</label>
                <input type="password" id="userPassword" class="form-control" placeholder="••••••••">
                <small class="text-muted" id="passwordHint" style="display: block; margin-top: 5px; font-style: italic;">
                    <i class="fas fa-info-circle"></i> Leave blank to keep current password if editing
                </small>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label><i class="fas fa-user-tag me-1"></i> Role</label>
                    <select id="userRole" class="form-control" required>
                        <option value="customer">Customer</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label><i class="fas fa-phone me-1"></i> Phone Number</label>
                    <input type="text" id="userPhone" class="form-control" placeholder="+880 1XXX XXXXXX">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt me-1"></i> Address</label>
                <input type="text" id="userAddress" class="form-control" placeholder="House 123, Road 4, City">
            </div>
            <div class="modal-footer" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #F1F5F9;">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save">
                    <i class="fas fa-check-circle me-1"></i> Save User Information
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const userModal = document.getElementById('userModal');
    const userForm = document.getElementById('userForm');
    let isEditing = false;

    function showAddUserModal() {
        isEditing = false;
        document.getElementById('modalTitle').textContent = 'Add New User';
        document.getElementById('userId').value = '';
        userForm.reset();
        document.getElementById('passwordGroup').style.display = 'block';
        document.getElementById('passwordHint').style.display = 'none';
        document.getElementById('userPassword').required = true;
        userModal.style.display = 'flex';
    }

    async function editUser(id) {
        isEditing = true;
        document.getElementById('modalTitle').textContent = 'Edit User';
        document.getElementById('userId').value = id;
        document.getElementById('passwordHint').style.display = 'block';
        document.getElementById('userPassword').required = false;
        
        try {
            const response = await fetch(`/api/admin/users/${id}`, {
                headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${localStorage.getItem('auth_token')}` }
            });
            const user = await response.json();
            
            document.getElementById('userName').value = user.name;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userRole').value = user.role;
            document.getElementById('userPhone').value = user.phone || '';
            document.getElementById('userAddress').value = user.address || '';
            
            userModal.style.display = 'flex';
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Fetch Error', text: 'Failed to retrieve user information.' });
        }
    }

    function closeModal() {
        userModal.style.display = 'none';
    }

    userForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('userId').value;
        const url = isEditing ? `/api/admin/users/${id}` : '/api/admin/users';
        const method = isEditing ? 'PUT' : 'POST';
        
        const formData = {
            name: document.getElementById('userName').value,
            email: document.getElementById('userEmail').value,
            role: document.getElementById('userRole').value,
            phone: document.getElementById('userPhone').value,
            address: document.getElementById('userAddress').value,
        };
        
        const password = document.getElementById('userPassword').value;
        if (password) formData.password = password;

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            });
            
            const result = await response.json();
            if (response.ok) {
                Swal.fire({ icon: 'success', title: 'Success', text: result.message }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Action Failed', text: result.message || 'Operation failed' });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'System Error', text: 'An unexpected error occurred.' });
        }
    });

    async function deleteUser(id, name) {
        const confirmDelete = await Swal.fire({
            title: `Remove User?`,
            text: `Are you sure you want to delete account for "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete!'
        });

        if (!confirmDelete.isConfirmed) return;
        
        try {
            const response = await fetch(`/api/admin/users/${id}`, {
                method: 'DELETE',
                headers: { 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (response.ok) {
                Swal.fire({ icon: 'success', title: 'Deleted!', text: 'User account has been removed.' });
                document.getElementById(`user-row-${id}`).remove();
            } else {
                const result = await response.json();
                Swal.fire({ icon: 'error', title: 'Delete Failed', text: result.message || 'Delete failed' });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'System Error', text: 'An unexpected error occurred.' });
        }
    }
</script>
@endsection
