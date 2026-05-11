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
                    <th>Title</th>
                    <th>Destination</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $pkg)
                <tr>
                    <td>{{ $pkg->title }}</td>
                    <td>{{ $pkg->destination }}</td>
                    <td>{{ $pkg->duration_days }} Days</td>
                    <td>TK {{ number_format($pkg->price, 2) }}</td>
                    <td>
                        <span class="badge badge-confirmed">Active</span>
                    </td>
                    <td>
                        <button class="btn-icon text-blue"><i class="fas fa-edit"></i></button>
                        <button class="btn-icon text-red"><i class="fas fa-trash"></i></button>
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

<!-- Modal logic similar to Hotels -->
@endsection
