@extends('layouts.admin')

@section('title', 'Add Distribution Item')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa-solid fa-boxes-stacked me-2"></i> Add Distribution Item</h5>
        </div>
        <div class="card-body">

            @if($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <form action="{{ route('backend.distribution_items.store') }}" method="POST">
                @csrf

                <!-- Distribution Selection Dropdown -->
                <div class="mb-3">
                    <label for="distribution_id" class="form-label fw-bold">Select Distribution Reference <span class="text-danger">*</span></label>
                    <select name="distribution_id" id="distribution_id" class="form-select @error('distribution_id') is-invalid @enderror" required>
                        <option value="">-- Select Distribution --</option>
                        @foreach($distributions as $dist)
                            <option value="{{ $dist->id }}" {{ old('distribution_id') == $dist->id ? 'selected' : '' }}>
                                #DSP-{{ $dist->id }} (Warehouse: {{ $dist->warehouse->name ?? 'N/A' }} | Request: #REQ-{{ $dist->relief_request_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('distribution_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Item Selection Dropdown -->
                <div class="mb-3">
                    <label for="item_id" class="form-label fw-bold">Select Item <span class="text-danger">*</span></label>
                    <select name="item_id" id="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                        <option value="">-- Choose Item --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Quantity Input -->
                <div class="mb-3">
                    <label for="quantity" class="form-label fw-bold">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('backend.distribution_items.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-1"></i> Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
