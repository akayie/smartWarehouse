@extends('layouts.admin')

@section('title', 'Supply Categories')

@section('button')
<a href="{{ route('backend.categories.create') }}" class="btn btn-primary btn-sm">
    <i class="fa-solid fa-plus me-1"></i> Add Category
</a>
@endsection

@section('content')
<div class="row">
    <!-- CATEGORY LIST TABLE -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fa-solid fa-tags me-2"></i>Relief Supply Categories
                </h5>
                <span class="badge bg-primary rounded-pill">{{ $categories->total() ?? count($categories) }} Categories Total</span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;" class="text-center">#</th>
                                <th style="width: 100px;">Image</th>
                                <th>Category Name</th>
                                <th class="text-center" style="width: 160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $key => $category)
                            <tr>
                                <td class="text-center text-muted">
                                    {{ $categories->firstItem() ? $categories->firstItem() + $key : $key + 1 }}
                                </td>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset($category->image) }}"
                                             alt="{{ $category->name }}"
                                             class="rounded border shadow-sm"
                                             width="50"
                                             height="50"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded text-center d-flex align-items-center justify-content-center text-muted border"
                                             style="width: 50px; height: 50px; font-size: 0.8rem;">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-dark">{{ $category->name }}</strong>
                                    @if(isset($category->items_count))
                                        <br><small class="text-muted"><i class="fa-solid fa-boxes-stacked me-1"></i>{{ $category->items_count }} Linked Items</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('backend.categories.edit', $category->id) }}"
                                           class="btn btn-outline-primary"
                                           title="Edit Category">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        <button type="button"
                                                class="btn btn-outline-danger delete-btn"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                title="Delete Category">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-folder-open fa-2x mb-2 d-block"></i>
                                    No categories found. Click <strong>"Add Category"</strong> to create one.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(method_exists($categories, 'links') && $categories->hasPages())
                <div class="card-footer bg-white d-flex justify-content-end py-3">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- QUICK ADD SIDEBAR FORM -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="m-0 font-weight-bold text-dark">
                    <i class="fa-solid fa-square-plus me-2 text-success"></i>Quick Add
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('backend.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label font-weight-bold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="e.g., Medical Supplies" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label font-weight-bold">Category Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-save me-1"></i> Save Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- BOOTSTRAP DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="deleteCategoryName" class="text-danger">this category</strong>? This action cannot be undone.
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash-can me-1"></i> Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Delete Button Click Handler
    $('.delete-btn').on('click', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');

        // Dynamically set form action URL based on Laravel resource routing
        let deleteUrl = "{{ route('backend.categories.destroy', ':id') }}".replace(':id', id);

        $('#deleteForm').attr('action', deleteUrl);
        $('#deleteCategoryName').text(name ? `"${name}"` : 'this category');

        // Trigger Bootstrap 5 Modal
        let deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    });
});
</script>
@endsection
