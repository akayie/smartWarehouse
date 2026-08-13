@extends('layouts.admin')

@section('title', 'Donations')

@section('button')
<a href="{{ route('backend.donations.create') }}" class="btn btn-primary">
    + New Donation
</a>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Donation List & Processing</h5>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Donor</th>
                        <th>Warehouse</th>
                        <th>Donated Items</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 250px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                        <tr>
                            <td>{{ $loop->iteration + ($donations->currentPage() - 1) * $donations->perPage() }}</td>
                            <td>
                                <strong class="text-primary">{{ $donation->donor->name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $donation->donor->phone ?? '' }}</small>
                            </td>
                            <td>{{ $donation->warehouse->name ?? '-' }}</td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($donation->donationItems as $item)
                                        <li>
                                            <i class="fas fa-box text-secondary me-1"></i>
                                            {{ $item->item->name ?? 'N/A' }} -
                                            <span class="badge bg-secondary">{{ $item->quantity }} {{ $item->item->unit ?? '' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                @if($donation->status === 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($donation->status === 'Received')
                                    <span class="badge bg-success">Received (+Stock)</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($donation->status === 'Pending')
                                    {{-- Receive Button - ဒါကို နှိပ်လိုက်ရင် Inventory ထဲ Stock (+) သွားပေါင်းပါမည် --}}
                                    <form action="{{ route('backend.donations.receive', $donation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirm receiving donation? Inventory stock will be increased.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success fw-bold">
                                            <i class="fas fa-check me-1"></i> Receive
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('backend.donations.show', $donation->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                <a href="{{ route('backend.donations.edit', $donation->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No donations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $donations->links() }}
        </div>
    </div>
</div>
@endsection
