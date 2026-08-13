@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-exclamation-triangle text-warning me-2"></i>Low Stock Alert Report</h2>
        <button onclick="window.print()" class="btn btn-secondary d-print-none">
            <i class="fas fa-print me-1"></i> Print / Export PDF
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="card mb-4 d-print-none">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.reports.low-stock') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Stock Threshold Limit (<=)</label>
                    <input type="number" name="threshold" class="form-control" value="{{ $threshold }}" min="1">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search me-1"></i> Check Low Stock</button>
                    <a href="{{ route('backend.reports.low-stock') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Report Table --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Warehouse</th>
                        <th class="text-end">Current Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStocks as $index => $stock)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $stock->item->name ?? 'N/A' }}</td>
                            <td>{{ $stock->item->category->name ?? 'N/A' }}</td>
                            <td>{{ $stock->warehouse->name ?? 'N/A' }}</td>
                            <td class="text-end fw-bold text-danger">{{ number_format($stock->quantity) }}</td>
                            <td>
                                <span class="badge bg-danger">Low Stock Warning</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-success py-3">
                                <i class="fas fa-check-circle me-1"></i> All items have sufficient stock level (Above {{ $threshold }}).
                            </td>
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
