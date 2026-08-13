@extends('layouts.admin')

@section('title', 'Stock Movement History')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-dark">Stock Movement History</h3>
        <div class="d-flex gap-2">
            {{-- QR/Barcode Scanning --}}
            <a href="{{ route('backend.scan') }}" class="btn btn-primary">
                <i class="fa-solid fa-qrcode me-1"></i> QR / Barcode Scan
            </a>

            {{-- Manual Movement Creation --}}
            <a href="{{ route('backend.stock-movements.create') }}" class="btn btn-success">
                <i class="fa-solid fa-plus me-1"></i> Create Stock Movement
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Form --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.stock-movements.index') }}" class="row g-3">
                <div class="col-md-2">
                    <select name="item_id" class="form-select">
                        <option value="">-- All Items --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="warehouse_id" class="form-select">
                        <option value="">-- All Warehouses --</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">-- All Types --</option>
                        <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>IN</option>
                        <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>OUT</option>
                        <option value="TRANSFER" {{ request('type') == 'TRANSFER' ? 'selected' : '' }}>TRANSFER</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" placeholder="From Date">
                </div>

                <div class="col-md-2">
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" placeholder="To Date">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('backend.stock-movements.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Movements Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Date & Time</th>
                            <th>Item</th>
                            <th>Warehouse</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Reference</th>
                            <th>Created By</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockMovements as $movement)
                            <tr>
                                <td class="ps-3">{{ $loop->iteration + ($stockMovements->currentPage() - 1) * $stockMovements->perPage() }}</td>
                                <td>{{ $movement->created_at->format('Y-m-d h:i A') }}</td>
                                <td class="fw-bold">{{ $movement->item->name ?? '-' }}</td>
                                <td>{{ $movement->warehouse->name ?? '-' }}</td>
                                <td>
                                    @if($movement->type === 'IN')
                                        <span class="badge bg-success">IN</span>
                                    @elseif($movement->type === 'OUT')
                                        <span class="badge bg-danger">OUT</span>
                                    @else
                                        <span class="badge bg-info text-dark">TRANSFER</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $movement->type === 'IN' ? 'text-success' : 'text-danger' }}">
                                        {{ $movement->type === 'IN' ? '+' : '-' }}{{ $movement->quantity }}
                                    </strong>
                                </td>
                                <td>{{ $movement->reference ?? '-' }}</td>
                                <td>{{ $movement->creator->name ?? 'System' }}</td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('backend.stock-movements.show', $movement->id) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <form action="{{ route('backend.stock-movements.destroy', $movement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this movement? This action will revert the stock balance.');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete & Revert">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>
                                    No stock movements recorded.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($stockMovements->hasPages())
                <div class="p-3 border-top">
                    {{ $stockMovements->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
