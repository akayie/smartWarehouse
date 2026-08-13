@extends('layouts.admin')

@section('title', 'Distribution Items Catalog')

@section('button')
    <a href="{{ route('backend.distribution_items.create') }}" class="btn btn-primary shadow-sm">
        <i class="fa-solid fa-plus-circle me-1"></i> Add Distribution Item
    </a>
@endsection

@section('content')
<div id="adm-distribution-items" class="sub-page">
    <div class="card shadow-sm border-0">
        <!-- CARD HEADER & SEARCH BAR -->
        <div class="card-header bg-white py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h3 class="h5 mb-0 fw-bold text-secondary">
                <i class="fa-solid fa-boxes-stacked me-2 text-primary"></i>Distributed Items Detail List
            </h3>

            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('backend.distribution_items.index') }}" method="GET" class="d-flex align-items-center">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Search item or dispatch ref..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body p-3">
            <!-- FLASH MESSAGES -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- TABLE CONTENT -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border">
                    <thead class="table-light">
                        <tr class="text-uppercase fs-7 text-muted">
                            <th style="width: 5%;">#</th>
                            <th style="width: 25%;">Dispatch Ref</th>
                            <th style="width: 35%;">Item Name</th>
                            <th style="width: 15%;">Quantity</th>
                            <th style="width: 20%;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distributionItems as $index => $dItem)
                            <tr>
                                <td class="text-muted fs-7">
                                    {{ $distributionItems->firstItem() + $index }}
                                </td>
                                <td>
                                    <a href="{{ route('backend.distributions.show', $dItem->distribution_id) }}" class="fw-bold text-decoration-none text-primary">
                                        <i class="fa-solid fa-truck-ramp-box me-1"></i>#DSP-{{ $dItem->distribution_id }}
                                    </a>
                                    @if(optional($dItem->distribution)->warehouse)
                                        <br>
                                        <small class="text-muted fs-7">
                                            <i class="fa-solid fa-warehouse me-1"></i>{{ $dItem->distribution->warehouse->name }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">
                                        {{ $dItem->item->name ?? 'N/A' }}
                                    </div>
                                    @if(optional($dItem->item)->category)
                                        <small class="badge bg-light text-secondary border">
                                            {{ $dItem->item->category->name }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark px-2 py-1 fs-6">
                                        <i class="fa-solid fa-cubes me-1"></i>{{ number_format($dItem->quantity) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('backend.distribution_items.show', $dItem->id) }}" class="btn btn-outline-primary" title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('backend.distribution_items.edit', $dItem->id) }}" class="btn btn-outline-secondary" title="Edit Record">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('backend.distribution_items.destroy', $dItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this record? Stock will be restored.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" style="border-top-left-radius: 0; border-bottom-left-radius: 0;" title="Delete Record">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <div class="py-3">
                                        <i class="fa-solid fa-box-open fa-3x text-light mb-2"></i>
                                        <p class="mb-0">No distribution item records found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="d-flex justify-content-between align-items-center mt-3 px-1">
                <div class="text-muted small">
                    Showing {{ $distributionItems->firstItem() ?? 0 }} to {{ $distributionItems->lastItem() ?? 0 }} of {{ $distributionItems->total() }} entries
                </div>
                <div>
                    {{ $distributionItems->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
