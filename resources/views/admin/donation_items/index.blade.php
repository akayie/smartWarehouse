@extends('layouts.admin')

@section('title', 'Donation Items')

@section('button')
<a href="{{ route('backend.donation_items.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> Add Donation Item
</a>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">Donation Items List</h5>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Search Filter Form --}}
        <form method="GET" action="{{ route('backend.donation_items.index') }}" class="row g-3 mb-4">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by Donor, Item name or Donation ID..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Search</button>
            </div>
            @if(request('search'))
                <div class="col-md-2">
                    <a href="{{ route('backend.donation_items.index') }}" class="btn btn-outline-danger w-100">Clear</a>
                </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Donation</th>
                        <th>Donor</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th style="width: 200px;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donationItems as $donationItem)
                        <tr>
                            <td>{{ $loop->iteration + ($donationItems->currentPage() - 1) * $donationItems->perPage() }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    #{{ $donationItem->donation_id }}
                                </span>
                            </td>
                            <td>{{ $donationItem->donation->donor->name ?? 'N/A' }}</td>
                            <td>
                                <span class="fw-semibold">{{ $donationItem->item->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $donationItem->quantity }} {{ $donationItem->item?->unit }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('backend.donation_items.show', $donationItem->id) }}"
                                       class="btn btn-outline-info" title="View Details">
                                        View
                                    </a>
                                    <a href="{{ route('backend.donation_items.edit', $donationItem->id) }}"
                                       class="btn btn-outline-warning" title="Edit Item">
                                        Edit
                                    </a>
                                    <form action="{{ route('backend.donation_items.destroy', $donationItem->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this donation item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete Item">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <em>No donation items found.</em>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($donationItems->hasPages())
            <div class="mt-4 d-flex justify-content-end">
                {{ $donationItems->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
