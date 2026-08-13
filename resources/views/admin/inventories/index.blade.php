@extends('layouts.admin')

@section('title', 'Stock Inventory Batches')

@section('content')
<div class="container-fluid py-3">

    {{-- Filter & Search Form --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.inventories.index') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by item name or barcode...">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- All Expiry Status --</option>
                        <option value="expiring_soon" {{ request('status') == 'expiring_soon' ? 'selected' : '' }}>Expiring Soon (Within 30 Days)</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('backend.scan') }}" class="btn btn-success">
                        <i class="fas fa-qrcode me-1"></i> Scan Stock In
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Inventory Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fas fa-boxes me-2"></i>Stock Inventory (Batch-wise Expiry)
            </h5>
            <a href="{{ route('backend.items.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Create New Item
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Barcode</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Warehouse</th>
                            <th>Qty</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventories as $index => $inventory)
                            @php
                                $item = $inventory->item;
                                $expiryDate = $inventory->expiry_date ? \Carbon\Carbon::parse($inventory->expiry_date) : null;
                                $isExpired = $expiryDate ? $expiryDate->isPast() : false;
                                $isExpiringSoon = $expiryDate ? (!$isExpired && $expiryDate->diffInDays(now()) <= 30) : false;
                            @endphp
                            <tr>
                                <td class="ps-3">{{ $inventories->firstItem() + $index }}</td>
                                <td><code>{{ $item->barcode ?? 'N/A' }}</code></td>
                                <td class="fw-bold">{{ $item->name ?? 'Unknown Item' }}</td>
                                <td>{{ $item->category->name ?? 'N/A' }}</td>
                                <td>{{ $inventory->warehouse->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info text-dark fs-6">{{ $inventory->quantity }} {{ $item->unit ?? '' }}</span>
                                </td>
                                <td>
                                    @if($expiryDate)
                                        <span class="badge {{ $isExpired ? 'bg-danger' : ($isExpiringSoon ? 'bg-warning text-dark' : 'bg-light text-dark border') }}">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $expiryDate->format('Y-m-d') }}
                                        </span>
                                    @else
                                        <span class="text-muted">No Expiry</span>
                                    @endif
                                </td>
                                <td>
                                    @if($isExpired)
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif($isExpiringSoon)
                                        <span class="badge bg-warning text-dark">Expiring Soon</span>
                                    @else
                                        <span class="badge bg-success">Good</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Stock Batch တစ်ခုမျှ မရှိသေးပါ။ Stock-In အရင် ပြုလုပ်ပေးပါ။
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($inventories->hasPages())
            <div class="card-footer bg-white">
                {{ $inventories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
