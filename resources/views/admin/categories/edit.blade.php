@extends('layouts.admin')

@section('title', 'Edit Category')

@section('button')
<a href="{{ route('backend.categories.index') }}" class="btn btn-outline-secondary btn-sm">
    <i class="fa-solid fa-arrow-left me-1"></i> Back to Categories
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Category: <span class="text-dark">{{ $category->name }}</span>
                </h5>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('backend.categories.update', $category->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="editCategoryForm">
                    @csrf
                    @method('PUT')

                    <!-- Hidden Input for Old Image Path -->
                    <input type="hidden" name="old_image" value="{{ $category->image }}">

                    <!-- Category Name Field -->
                    <div class="mb-4">
                        <label for="categoryName" class="form-label font-weight-bold text-dark">
                            Category Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-tag"></i></span>
                            <input type="text"
                                   id="categoryName"
                                   name="name"
                                   value="{{ old('name', $category->name) }}"
                                   placeholder="e.g., Emergency Medical Supplies"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Image Section: Current vs New Preview -->
                    <div class="mb-4">
                        <label class="form-label font-weight-bold text-dark">Category Image</label>

                        <div class="row align-items-center g-3 bg-light p-3 rounded border">
                            <!-- Current Image Display -->
                            <div class="col-sm-4 text-center border-end-sm">
                                <span class="d-block text-muted small mb-2 font-weight-bold">Current Image</span>
                                @if($category->image)
                                    <img src="{{ asset($category->image) }}"
                                         alt="{{ $category->name }}"
                                         class="img-thumbnail shadow-sm rounded"
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="bg-white rounded border d-flex flex-column align-items-center justify-content-center mx-auto text-muted"
                                         style="width: 120px; height: 120px;">
                                        <i class="fa-solid fa-image fa-2x mb-1"></i>
                                        <small>No Image</small>
                                    </div>
                                @endif
                            </div>

                            <!-- New Image Live Preview -->
                            <div class="col-sm-4 text-center">
                                <span class="d-block text-muted small mb-2 font-weight-bold">New Image Preview</span>
                                <div id="previewContainer">
                                    <div class="bg-white rounded border d-flex flex-column align-items-center justify-content-center mx-auto text-muted"
                                         style="width: 120px; height: 120px;">
                                        <i class="fa-solid fa-eye-slash fa-2x mb-1"></i>
                                        <small>Waiting for input...</small>
                                    </div>
                                </div>
                            </div>

                            <!-- File Input Upload Field -->
                            <div class="col-sm-4">
                                <label for="categoryImage" class="form-label small text-muted font-weight-bold">Upload Replacement</label>
                                <input type="file"
                                       id="categoryImage"
                                       name="image"
                                       accept="image/*"
                                       class="form-control form-control-sm @error('image') is-invalid @enderror"
                                       onchange="previewImage(event)">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                    Supported formats: JPG, PNG, WEBP (Max 2MB).
                                </small>
                                @error('image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <!-- Form Action Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('backend.categories.index') }}" class="btn btn-light border px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Live Image Preview Function
    function previewImage(event) {
        const input = event.target;
        const container = document.getElementById('previewContainer');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                container.innerHTML = `
                    <img src="${e.target.result}"
                         alt="New Preview"
                         class="img-thumbnail shadow-sm rounded border-primary"
                         style="width: 120px; height: 120px; object-fit: cover;">
                `;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
