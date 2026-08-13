@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-hands-helping me-2"></i>Relief Request Report</h2>
        <button onclick="window.print()" class="btn btn-secondary d-print-none">
            <i class="fas fa-print me-1"></i> Print / Export PDF
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="card mb-4 d-print-none">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.reports.relief-request') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('backend.reports.relief-request') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Requester</th>
                        <th>Disaster Event</th>
                        <th>Requested Items</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reliefRequests as $req)
                        <tr>
                            <td>{{ $req->created_at->format('d-M-Y') }}</td>
                            <td>{{ $req->user->name ?? 'N/A' }}</td>
                            <td>{{ $req->disaster->name ?? 'General Relief' }}</td>
                            <td>
                                <ul class="mb-0 ps-3">
                                    @foreach($req->requestItems as $item)
                                        <li>{{ $item->item->name ?? 'Item' }} (Qty: {{ $item->quantity }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                @if($req->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($req->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No relief request records found.</td>
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
