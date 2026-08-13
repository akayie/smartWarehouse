@extends('layouts.admin')

@section('title', 'Edit Item - ' . $item->name)

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <!-- Card Header -->
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Edit SKU Item: {{ $item->name }}
                    </h5>
                    <a href="{{ route('backend.items.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Catalog
                    </a>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    <form action="{{ route('backend.items.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Category -->
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Item Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" placeholder="Enter item name" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea name="description" id="description" rows="3" placeholder="Enter optional item description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div class="col-md-4">
                                <label for="unit" class="form-label fw-semibold">Unit <span class="text-danger">*</span></label>
                                <input type="text" name="unit" id="unit" value="{{ old('unit', $item->unit) }}" placeholder="e.g. Bag, Box, Bottle" class="form-control @error('unit') is-invalid @enderror" required>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Minimum Stock -->
                            <div class="col-md-4">
                                <label for="minimum_stock" class="form-label fw-semibold">Minimum Stock Alert</label>
                                <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', $item->minimum_stock) }}" min="0" class="form-control @error('minimum_stock') is-invalid @enderror">
                                @error('minimum_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Expiry Date -->
                            <div class="col-md-4">
                                <label for="expiry_date" class="form-label fw-semibold">Expiry Date</label>
                                <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $item->expiry_date ? $item->expiry_date->format('Y-m-d') : '') }}" class="form-control @error('expiry_date') is-invalid @enderror">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Barcode -->
                            <div class="col-md-8">
                                <label for="barcode" class="form-label fw-semibold">Barcode / SKU</label>
                                <div class="input-group">
                                    <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $item->barcode) }}" placeholder="Scan or enter barcode" class="form-control @error('barcode') is-invalid @enderror">
                                    <button type="button" id="startBarcodeScanner" class="btn btn-outline-primary">
                                        <i class="fas fa-camera me-1"></i> Scan
                                    </button>
                                    <button type="button" id="stopBarcodeScanner" class="btn btn-outline-danger" style="display:none;">
                                        <i class="fas fa-times me-1"></i> Stop
                                    </button>
                                </div>
                                @error('barcode')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div id="barcode-reader" class="mt-2 rounded overflow-hidden" style="width: 100%; max-width: 320px;"></div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-4">
                                <label for="status" class="form-label fw-semibold">Status</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="Active" {{ old('status', $item->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status', $item->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 text-end mt-4 pt-3 border-top">
                                <a href="{{ route('backend.items.index') }}" class="btn btn-light me-2">Cancel</a>
                                <button type="submit" class="btn btn-warning px-4 text-white fw-bold">
                                    <i class="fas fa-save me-1"></i> Update Item
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Barcode Scanner Script --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let barcodeScanner = null;

    const startButton = document.getElementById('startBarcodeScanner');
    const stopButton = document.getElementById('stopBarcodeScanner');
    const barcodeInput = document.getElementById('barcode');

    startButton.addEventListener('click', function () {
        if (barcodeScanner) return;

        barcodeScanner = new Html5Qrcode('barcode-reader');
        const config = { fps: 10, qrbox: { width: 250, height: 120 } };

        barcodeScanner.start(
            { facingMode: "environment" },
            config,
            function(decodedText) {
                barcodeInput.value = decodedText;
                stopScanner();
            },
            function(errorMessage) {}
        ).then(() => {
            startButton.style.display = 'none';
            stopButton.style.display = 'inline-block';
        }).catch(err => {
            alert('Camera access denied or HTTPS required.');
            barcodeScanner = null;
        });
    });

    stopButton.addEventListener('click', stopScanner);

    function stopScanner() {
        if (barcodeScanner) {
            barcodeScanner.stop().then(() => {
                barcodeScanner.clear();
                barcodeScanner = null;
                startButton.style.display = 'inline-block';
                stopButton.style.display = 'none';
            });
        }
    }
</script>
@endsection
