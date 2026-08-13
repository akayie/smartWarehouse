@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-boxes me-2"></i>Inventory Report</h2>
        <button onclick="window.print()" class="btn btn-secondary d-print-none">
            <i class="fas fa-print me-1"></i> Print / Export PDF
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="card mb-4 d-print-none">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.reports.inventory') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Warehouse</label>
                    <select name="warehouse_id" class="form-select">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('backend.reports.inventory') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Report Table --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Warehouse</th>
                        <th class="text-end">Current Stock Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $index => $inv)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $inv->item->name ?? 'N/A' }}</td>
                            <td>{{ $inv->item->category->name ?? 'N/A' }}</td>
                            <td>{{ $inv->warehouse->name ?? 'N/A' }}</td>
                            <td class="text-end fw-bold">{{ number_format($inv->quantity) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No inventory records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    .d-print-none, sidebar, navbar, footer { display: none !important; }
    .card { border: none !important; }
}
</style>
@endsection
