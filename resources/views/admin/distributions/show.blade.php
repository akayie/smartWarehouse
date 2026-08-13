@extends('layouts.admin')

@section('title', 'Distribution Details #' . $distribution->id)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Distribution Voucher #{{ $distribution->id }}</h3>
        <div>
            <button onclick="window.print()" class="btn btn-outline-secondary me-2">
                <i class="fa-solid fa-print me-1"></i> Print Voucher
            </button>
            <a href="{{ route('backend.distributions.index') }}" class="btn btn-primary">
                Back to List
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <span class="text-muted d-block">Distribution Date</span>
                    <strong class="fs-6">{{ $distribution->distribution_date->format('Y-m-d') }}</strong>
                </div>
                <div class="col-md-3">
                    <span class="text-muted d-block">Source Warehouse</span>
                    <strong class="fs-6 text-primary">{{ $distribution->warehouse->name ?? 'N/A' }}</strong>
                </div>
                <div class="col-md-3">
                    <span class="text-muted d-block">Target Relief Request</span>
                    <strong class="fs-6">{{ $distribution->request ? 'Req #'.$distribution->request->id.' ('.$distribution->request->location.')' : 'Direct Distribution' }}</strong>
                </div>
                <div class="col-md-3">
                    <span class="text-muted d-block">Handled By Staff</span>
                    <strong class="fs-6">{{ $distribution->handledBy->name ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Distributed Items Line Details</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Barcode</th>
                            <th>Item Name</th>
                            <th>Quantity Distributed</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distribution->distributionItems as $index => $detail)
                            <tr>
                                <td class="ps-3">{{ $index + 1 }}</td>
                                <td><code>{{ $detail->item->barcode ?? '-' }}</code></td>
                                <td class="fw-bold">{{ $detail->item->name ?? 'Unknown Item' }}</td>
                                <td>
                                    <span class="badge bg-danger fs-6">- {{ $detail->quantity }}</span>
                                </td>
                                <td>{{ $detail->item->unit ?? 'Pcs' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">No line items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
