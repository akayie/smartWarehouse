@extends('layouts.admin')

@section('title', 'Create Stock Movement')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h1 class="h3 text-gray-800">Create Stock Movement</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New Stock Movement</h6>
        </div>
        <div class="card-body">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('backend.stock-movements.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    {{-- Item Selection --}}
                    <div class="col-md-6">
                        <label for="item_id" class="form-label fw-bold">Select Item <span class="text-danger">*</span></label>
                        <select name="item_id" id="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                            <option value="">-- Select Item --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} {{ $item->code ? '('.$item->code.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Movement Type --}}
                    <div class="col-md-6">
                        <label for="type" class="form-label fw-bold">Movement Type <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" onchange="toggleTargetWarehouse(this.value)" required>
                            <option value="IN" {{ old('type') == 'IN' ? 'selected' : '' }}>Stock IN (+)</option>
                            <option value="OUT" {{ old('type') == 'OUT' ? 'selected' : '' }}>Stock OUT (-)</option>
                            <option value="TRANSFER" {{ old('type') == 'TRANSFER' ? 'selected' : '' }}>Stock TRANSFER (->)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Source Warehouse --}}
                    <div class="col-md-6">
                        <label for="warehouse_id" class="form-label fw-bold">Source / Current Warehouse <span class="text-danger">*</span></label>
                        <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                            <option value="">-- Select Warehouse --</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Target Warehouse (Visible only when type is TRANSFER) --}}
                    <div class="col-md-6" id="target_warehouse_wrapper" style="display: none;">
                        <label for="target_warehouse_id" class="form-label fw-bold">Target Warehouse <span class="text-danger">*</span></label>
                        <select name="target_warehouse_id" id="target_warehouse_id" class="form-select @error('target_warehouse_id') is-invalid @enderror">
                            <option value="">-- Select Target Warehouse --</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ old('target_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('target_warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Quantity --}}
                    <div class="col-md-6">
                        <label for="quantity" class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Reference / Note --}}
                    <div class="col-md-6">
                        <label for="reference" class="form-label fw-bold">Reference / Note</label>
                        <input type="text" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference') }}" placeholder="e.g. DON-001 or PO-1002">
                        @error('reference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Created By Field --}}
                    <div class="col-md-6">
                        <label for="created_by" class="form-label fw-bold">Created By <span class="text-danger">*</span></label>
                        <select name="created_by" id="created_by" class="form-select @error('created_by') is-invalid @enderror" required>
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('created_by', auth()->id()) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('created_by')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Form Actions --}}
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Save Movement
                        </button>
                        <a href="{{ route('backend.stock-movements.index') }}" class="btn btn-light border px-4 ms-2">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleTargetWarehouse(type) {
        const wrapper = document.getElementById('target_warehouse_wrapper');
        const targetSelect = document.getElementById('target_warehouse_id');

        if (type === 'TRANSFER') {
            wrapper.style.display = 'block';
            targetSelect.setAttribute('required', 'required');
        } else {
            wrapper.style.display = 'none';
            targetSelect.removeAttribute('required');
        }
    }

    // Run on page load (for old input memory)
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        if (typeSelect) {
            toggleTargetWarehouse(typeSelect.value);
        }
    });
</script>
@endsection
