@extends('layouts.admin')

@section('title', 'Create Item')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-box-open me-2"></i>Create New SKU Item</h5>
                    <a href="{{ route('backend.items.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Catalog
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('backend.items.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <!-- Category -->
                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Enter item name" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">Description</label>
                                <textarea name="description" id="description" rows="3" placeholder="Enter optional item description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Unit -->
                            <div class="col-md-4">
                                <label for="unit" class="form-label fw-semibold">Unit <span class="text-danger">*</span></label>
                                <input type="text" name="unit" id="unit" value="{{ old('unit') }}" placeholder="e.g. Bag, Box, Bottle" class="form-control @error('unit') is-invalid @enderror" required>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Minimum Stock -->
                            <div class="col-md-4">
                                <label for="minimum_stock" class="form-label fw-semibold">Minimum Stock Alert</label>
                                <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', 0) }}" min="0" class="form-control @error('minimum_stock') is-invalid @enderror">
                                @error('minimum_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Expiry Date -->
                            <div class="col-md-4">
                                <label for="expiry_date" class="form-label fw-semibold">
                                    Expiry Date <span class="text-muted fw-normal">(Optional)</span>
                                </label>
                                <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" class="form-control @error('expiry_date') is-invalid @enderror">
                                <div class="form-text text-muted small">Stock-In သွင်းစဉ်မှလည်း Expiry Date ထည့်သွင်းနိုင်ပါသည်။</div>
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Barcode -->
                            <div class="col-md-8">
                                <label for="barcode" class="form-label fw-semibold">Barcode / SKU</label>
                                <div class="input-group">
                                    <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}" placeholder="Scan or enter barcode" class="form-control @error('barcode') is-invalid @enderror">
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
                                <div id="barcode-reader" class="mt-2 rounded overflow-hidden" style="width: 100%; max-width: 380px;"></div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-4">
                                <label for="status" class="form-label fw-semibold">Status</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Actions -->
                            <div class="col-12 text-end mt-4">
                                <a href="{{ route('backend.items.index') }}" class="btn btn-light me-2">Cancel</a>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fas fa-save me-1"></i> Save Item
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let barcodeScanner = null;

    const startButton = document.getElementById('startBarcodeScanner');
    const stopButton = document.getElementById('stopBarcodeScanner');
    const barcodeInput = document.getElementById('barcode');

    function fetchItemDetails(barcode) {
        fetch(`{{ url('items/get-by-barcode') }}/${barcode}`)
            .then(response => response.ok ? response.json() : null)
            .then(res => {
                if (res && res.success && res.data) {
                    document.getElementById('name').value = res.data.name || '';
                    document.getElementById('category_id').value = res.data.category_id || '';
                    document.getElementById('description').value = res.data.description || '';
                    document.getElementById('unit').value = res.data.unit || '';
                    document.getElementById('minimum_stock').value = res.data.minimum_stock || 0;
                    document.getElementById('expiry_date').value = res.data.expiry_date || '';
                    document.getElementById('status').value = res.data.status || 'Active';
                }
            })
            .catch(err => console.log('Barcode lookup:', err));
    }

    barcodeInput.addEventListener('change', function () {
        if (this.value.trim() !== '') {
            fetchItemDetails(this.value.trim());
        }
    });

    startButton.addEventListener('click', function () {
        if (barcodeScanner) return;

        // Supported Formats များကို သီးသန့် Config လုပ်ခြင်း (Barcode များကို ပိုမိုမြန်ဆန်စွာ ဖတ်နိုင်ရန်)
        const formatsToSupport = [
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.EAN_8,
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.UPC_A,
            Html5QrcodeSupportedFormats.UPC_E,
            Html5QrcodeSupportedFormats.QR_CODE
        ];

        barcodeScanner = new Html5Qrcode('barcode-reader', { formatsToSupport: formatsToSupport });

        const config = {
            fps: 15,
            qrbox: { width: 300, height: 150 },
            aspectRatio: 1.777778
        };

        barcodeScanner.start(
            { facingMode: "environment" },
            config,
            function(decodedText) {
                barcodeInput.value = decodedText;
                fetchItemDetails(decodedText);
                stopScanner();
            },
            function(errorMessage) {
                // Scaning process ထဲတွင် frames တိုင်း error ပြသနေသဖြင့် console ထဲတွင် တိတ်ဆိတ်ထားသည်
            }
        ).then(() => {
            startButton.style.display = 'none';
            stopButton.style.display = 'inline-block';
        }).catch(err => {
            alert('Camera ဖွင့်၍ မရပါ။ HTTPS Domain သုံးထားခြင်း ရှိမရှိ သို့မဟုတ် Camera Permission ပေးထားခြင်း ရှိမရှိ စစ်ဆေးပါ။');
            console.error(err);
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
            }).catch(err => {
                console.error("Failed to stop scanner:", err);
                barcodeScanner = null;
                startButton.style.display = 'inline-block';
                stopButton.style.display = 'none';
            });
        }
    }
</script>
@endsection
