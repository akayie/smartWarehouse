@extends('layouts.admin')

@section('title', 'Disaster Campaigns')

@section('button')
<a href="{{ route('backend.disasters.create') }}" class="btn btn-sm btn-danger shadow-sm">
    <i class="fa-solid fa-plus me-1"></i> Create Event
</a>
@endsection

@section('content')
<div id="adm-events" class="container-fluid px-0">

    {{-- Success Alert Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3 shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Data Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-dark">
                <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>Disaster Campaigns & Emergencies
            </h5>
            <a href="{{ route('backend.disasters.create') }}" class="btn btn-sm btn-danger">
                <i class="fa-solid fa-plus me-1"></i> Create Event
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">#</th>
                            <th>Event Name</th>
                            <th>Type</th>
                            <th>Location Zone</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th class="text-center">Relief Requests</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 170px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disasters as $key => $disaster)
                            <tr>
                                <td class="text-center text-muted fw-bold">
                                    {{ method_exists($disasters, 'firstItem') && $disasters->firstItem() ? $disasters->firstItem() + $key : $loop->iteration }}
                                </td>
                                <td>
                                    <strong class="text-dark">{{ $disaster->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $disaster->type ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $disaster->location }}
                                </td>
                                <td>
                                    <small class="text-secondary fw-semibold">
                                        {{ $disaster->start_date ? $disaster->start_date->format('d-m-Y') : '-' }}
                                    </small>
                                </td>
                                <td>
                                    <small class="text-secondary fw-semibold">
                                        {{ $disaster->end_date ? $disaster->end_date->format('d-m-Y') : '-' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark rounded-pill px-3 py-2">
                                        <i class="fa-solid fa-hand-holding-hand me-1"></i>
                                        {{ $disaster->relief_requests_count ?? ($disaster->reliefRequests ? $disaster->reliefRequests->count() : 0) }}
                                    </span>
                                </td>
                                <td>
                                    @php $status = strtolower($disaster->status ?? ''); @endphp
                                    @if($status === 'active')
                                        <span class="badge bg-success"><i class="fa-solid fa-circle-dot me-1"></i>Active</span>
                                    @elseif($status === 'completed')
                                        <span class="badge bg-primary"><i class="fa-solid fa-circle-check me-1"></i>Completed</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i>{{ $disaster->status ?? 'Cancelled' }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('backend.disasters.show', $disaster->id) }}"
                                           class="btn btn-outline-info"
                                           title="View Details">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('backend.disasters.edit', $disaster->id) }}"
                                           class="btn btn-outline-warning text-dark"
                                           title="Edit Disaster">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>
                                        <button type="button"
                                                class="btn btn-outline-danger delete-btn"
                                                data-id="{{ $disaster->id }}"
                                                data-name="{{ $disaster->name }}"
                                                title="Delete Disaster">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0 fw-semibold">No disaster campaigns found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($disasters, 'hasPages') && $disasters->hasPages())
            <div class="card-footer bg-white d-flex justify-content-end py-3">
                {{ $disasters->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                Are you sure you want to delete <strong id="deleteDisasterName" class="text-danger">this disaster event</strong>? This action cannot be undone.
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash me-1"></i> Delete Event
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const deleteForm = document.getElementById('deleteForm');
    const deleteNameSpan = document.getElementById('deleteDisasterName');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            let id = this.getAttribute('data-id');
            let name = this.getAttribute('data-name');
            let deleteUrl = "{{ route('backend.disasters.destroy', ':id') }}".replace(':id', id);

            deleteForm.setAttribute('action', deleteUrl);
            deleteNameSpan.textContent = name ? `"${name}"` : 'this disaster event';

            let modalElement = document.getElementById('deleteModal');
            let deleteModal = new bootstrap.Modal(modalElement);
            deleteModal.show();
        });
    });
});
</script>
@endsection
