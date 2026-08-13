@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-exchange-alt me-2"></i>Stock Movement Audit Report</h2>
        <button onclick="window.print()" class="btn btn-secondary d-print-none">
            <i class="fas fa-print me-1"></i> Print / Export PDF
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="card mb-4 d-print-none">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.reports.stock-movement') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock IN</option>
                        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock OUT</option>
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('backend.reports.stock-movement') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Date</th>
                        <th>Item</th>
                        <th>Warehouse</th>
                        <th>Type</th>
                        <th class="text-end">Quantity</th>
                        <th>Handled By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $m)
                        <tr>
                            <td>{{ $m->created_at->format('d-M-Y h:i A') }}</td>
                            <td>{{ $m->item->name ?? 'N/A' }}</td>
                            <td>{{ $m->warehouse->name ?? 'N/A' }}</td>
                            <td>
                                @if($m->type == 'in')
                                    <span class="badge bg-success">IN</span>
                                @elseif($m->type == 'out')
                                    <span class="badge bg-danger">OUT</span>
                                @else
                                    <span class="badge bg-warning text-dark">ADJUSTMENT</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold">{{ number_format($m->quantity) }}</td>
                            <td>{{ $m->user->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No stock movement history found.</td>
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
