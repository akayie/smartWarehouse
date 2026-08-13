@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Distribution Report</h2>
        <button onclick="window.print()" class="btn btn-secondary d-print-none">
            <i class="fas fa-print"></i> Print / Export PDF
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="card mb-4 d-print-none">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.reports.distribution') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('backend.reports.distribution') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Report Table --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Distribution Code</th>
                        <th>Warehouse</th>
                        <th>Distributed Items</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distributions as $dist)
                        <tr>
                            <td>{{ $dist->created_at->format('d-M-Y') }}</td>
                            <td>{{ $dist->code ?? 'DIS-'.str_pad($dist->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $dist->warehouse->name ?? 'N/A' }}</td>
                            <td>
                                <ul class="mb-0">
                                    @foreach($dist->distributionItems as $dItem)
                                        <li>{{ $dItem->item->name ?? 'Item' }} (Qty: {{ $dItem->quantity }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ ucfirst($dist->status ?? 'completed') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No distribution records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    .d-print-none, sidebar, navbar, footer {
        display: none !important;
    }
    .card {
        border: none !important;
    }
}
</style>
@endsection
