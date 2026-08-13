@extends('layouts.admin')

@section('title', 'Donation Details #' . $donation->id)

@section('button')
<a href="{{ route('backend.donations.index') }}" class="btn btn-secondary">
    Back to List
</a>
@endsection

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-bold">Donation Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Donor:</strong> {{ $donation->donor->name ?? 'N/A' }}</p>
                    <p><strong>Warehouse:</strong> {{ $donation->warehouse->name ?? '-' }}</p>
                    <p><strong>Status:</strong>
                        @if($donation->status === 'Pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($donation->status === 'Received')
                            <span class="badge bg-success">Received</span>
                        @endif
                    </p>
                    <p><strong>Note:</strong> {{ $donation->note ?? '-' }}</p>

                    <hr>

                    @if($donation->status === 'Pending')
                        <form action="{{ route('backend.donations.receive', $donation->id) }}" method="POST" onsubmit="return confirm('Confirm receiving donation? Inventory stock will be added.')">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                Receive Donation (Stock IN)
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Donated Items List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th class="text-end">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donation->donationItems as $dItem)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dItem->item->name ?? 'N/A' }}</td>
                                    <td class="text-end fw-bold">{{ number_format($dItem->quantity) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
