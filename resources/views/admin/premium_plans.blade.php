@extends('layouts.admin')

@section('title', 'Premium Plan Management')
@section('header_title', 'Premium Membership Plans')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Manage Plans</h3>
        <button class="btn-premium" onclick="showAddPlanModal()"><i class="fas fa-plus"></i> Add New Plan</button>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Plan Name</th>
                    <th>Tier Key</th>
                    <th>Req. Stays</th>
                    <th>Discount</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="plansTableBody">
                <!-- Loaded via JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Plan Modal -->
<div id="planModal" class="modal-overlay">
    <div class="modal-content-card">
        <div class="modal-header-flex">
            <h3 id="modalTitle">Add New Plan</h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <form id="planForm">
            <input type="hidden" id="planId">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Plan Name</label>
                    <input type="text" id="planName" class="form-control" required placeholder="Silver Member">
                </div>
                <div class="col-md-6 form-group">
                    <label>Tier Key (Unique)</label>
                    <input type="text" id="planTierKey" class="form-control" required placeholder="silver">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Required Stays</label>
                    <input type="number" id="planMinBookings" class="form-control" required min="0">
                </div>
                <div class="col-md-4 form-group">
                    <label>Discount (%)</label>
                    <input type="number" id="planDiscount" class="form-control" required min="0" max="100">
                </div>
                <div class="col-md-4 form-group">
                    <label>Price (TK)</label>
                    <input type="number" id="planPrice" class="form-control" required min="0" step="0.01">
                </div>
            </div>
            <div class="form-group">
                <label>Benefits (Comma separated)</label>
                <textarea id="planBenefits" class="form-control" rows="3" placeholder="5% off, Priority support"></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select id="planStatus" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save">Save Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const planModal = document.getElementById('planModal');
    const planForm = document.getElementById('planForm');
    let isEditing = false;

    async function loadPlans() {
        const response = await fetch('/api/premium-plans');
        const plans = await response.json();
        const tbody = document.getElementById('plansTableBody');
        tbody.innerHTML = plans.map(plan => `
            <tr>
                <td class="fw-bold text-primary">${plan.name}</td>
                <td><code class="bg-light p-1 rounded">${plan.tier_key}</code></td>
                <td><span class="badge bg-secondary">${plan.min_bookings} stays</span></td>
                <td>${plan.discount_percentage}%</td>
                <td>TK ${parseFloat(plan.price).toLocaleString()}</td>
                <td>
                    <span class="badge ${plan.is_active ? 'badge-confirmed' : 'badge-danger'}">
                        ${plan.is_active ? 'Active' : 'Inactive'}
                    </span>
                </td>
                <td>
                    <button onclick='editPlan(${JSON.stringify(plan)})' style="border: none; background: none; color: var(--primary); cursor: pointer; margin-right: 10px;"><i class="fas fa-edit"></i></button>
                </td>
            </tr>
        `).join('');
    }

    function showAddPlanModal() {
        isEditing = false;
        document.getElementById('modalTitle').textContent = 'Add New Plan';
        document.getElementById('planId').value = '';
        planForm.reset();
        planModal.style.display = 'flex';
    }

    function editPlan(plan) {
        isEditing = true;
        document.getElementById('modalTitle').textContent = 'Edit Plan';
        document.getElementById('planId').value = plan.id;
        document.getElementById('planName').value = plan.name;
        document.getElementById('planTierKey').value = plan.tier_key;
        document.getElementById('planMinBookings').value = plan.min_bookings;
        document.getElementById('planDiscount').value = plan.discount_percentage;
        document.getElementById('planPrice').value = plan.price;
        document.getElementById('planBenefits').value = (plan.benefits || []).join(', ');
        document.getElementById('planStatus').value = plan.is_active ? "1" : "0";
        planModal.style.display = 'flex';
    }

    function closeModal() {
        planModal.style.display = 'none';
    }

    planForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('planId').value;
        const method = isEditing ? 'PUT' : 'POST';
        const url = isEditing ? `/api/admin/premium-plans/${id}` : '/api/admin/premium-plans';

        const data = {
            name: document.getElementById('planName').value,
            tier_key: document.getElementById('planTierKey').value,
            min_bookings: document.getElementById('planMinBookings').value,
            discount_percentage: document.getElementById('planDiscount').value,
            price: document.getElementById('planPrice').value,
            benefits: document.getElementById('planBenefits').value.split(',').map(b => b.trim()).filter(b => b !== ""),
            is_active: document.getElementById('planStatus').value === "1"
        };

        const response = await fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            Swal.fire({ icon: 'success', title: 'Success', text: 'Plan saved successfully' });
            closeModal();
            loadPlans();
        } else {
            const err = await response.json();
            Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Failed to save plan' });
        }
    });

    document.addEventListener('DOMContentLoaded', loadPlans);
</script>
@endsection
