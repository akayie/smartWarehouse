@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- 1. DASHBOARD -->
<div id="adm-dashboard" class="sub-page active">
    <!-- Stat Cards Section -->
    <div class="grid-4">
        <!-- Total Inventory Card -->
        <div class="card stat-card">
            <div>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Total Inventory</p>
                <div class="val">{{ number_format($totalInventory) }} Units</div>
                <span class="badge badge-success" style="margin-top: 5px;">Updated Live</span>
            </div>
            <div class="icon-box" style="background: #e0f2fe; color: #0284c7;">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- Pending Requests Card -->
        <div class="card stat-card">
            <div>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Pending Requests</p>
                <div class="val" style="color: var(--warning-orange);">{{ $pendingRequests }} Requests</div>
                <span class="badge badge-warning" style="margin-top: 5px;">Needs Action</span>
            </div>
            <div class="icon-box" style="background: #ffedd5; color: #ea580c;">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <!-- Active Dispatches Card -->
        {{-- <div class="card stat-card">
            <div>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Active Dispatches</p>
                <div class="val" style="color: var(--primary-blue);">{{ $activeDispatches }} Transit</div>
                <span class="badge badge-info" style="margin-top: 5px;">En Route</span>
            </div>
            <div class="icon-box" style="background: #dbeafe; color: #2563eb;">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
        </div> --}}

        <!-- Low Stock Alerts Card -->
        <div class="card stat-card">
            <div>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Low Stock Alerts</p>
                <div class="val" style="color: var(--danger-red);">{{ $lowStockCount }} Items</div>
                <span class="badge badge-danger" style="margin-top: 5px;">Critical</span>
            </div>
            <div class="icon-box" style="background: #fee2e2; color: #dc2626;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
    </div>

    <!-- Recent Activities Section -->
    <div class="grid-2 mt-4">
        <div class="card">
            <h3>Recent Relief Activities</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Activity</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $activity)
                        <tr>
                            <td>{{ $activity->created_at->diffForHumans() }}</td>
                            <td>{{ $activity->title }}</td>
                            <td>{{ $activity->location }}</td>
                            <td>
                                @if($activity->status == 'In Transit')
                                    <span class="badge badge-info">In Transit</span>
                                @elseif($activity->status == 'Verified' || $activity->status == 'Completed')
                                    <span class="badge badge-success">{{ $activity->status }}</span>
                                @elseif($activity->status == 'Pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-secondary">{{ $activity->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No recent activities logged.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
