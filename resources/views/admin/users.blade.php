@extends('layouts.admin')

@section('title', 'User Management')
@section('header_title', 'User Management')

@section('content')
<div class="data-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>All Registered Users</h3>
        <button class="btn-premium"><i class="fas fa-user-plus"></i> Add Manager</button>
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
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $user->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $user->email }}</div>
                    </td>
                    <td>
                        <span style="text-transform: capitalize; font-weight: 600;">{{ $user->role }}</span>
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
                        <button style="border: none; background: none; color: var(--primary); cursor: pointer; margin-right: 10px;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        @if(!$user->isAdmin())
                        <button style="border: none; background: none; color: #E74C3C; cursor: pointer;" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>
@endsection
