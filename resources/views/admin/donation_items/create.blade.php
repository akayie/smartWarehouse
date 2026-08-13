@extends('layouts.admin')

@section('title', 'Add Donation Item')

@section('button')
<a href="{{ route('backend.donation_items.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back
</a>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-dark">Add New Donation Item</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('backend.donation_items.store') }}" method="POST">
            @csrf

            <!-- Donation Selection -->
            <div class="mb-3">
                <label for="donation_id" class="form-label font-weight-bold">
                    Donation <span class="text-danger">*</span>
                </label>
                <select name="donation_id"
                        id="donation_id"
                        class="form-select @error('donation_id') is-invalid @enderror">
                    <option value="">-- Select Donation --</option>
                    @foreach($donations as $donation)
                        <option value="{{ $donation->id }}" {{ old('donation_id') == $donation->id ? 'selected' : '' }}>
                            #{{ $donation->id }} - {{ $donation->donor->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
                @error('donation_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Item Selection -->
            <div class="mb-3">
                <label for="item_id" class="form-label font-weight-bold">
                    Item <span class="text-danger">*</span>
                </label>
                <select name="item_id"
                        id="item_id"
                        class="form-select @error('item_id') is-invalid @enderror">
                    <option value="">-- Select Item --</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }} @if($item->unit) ({{ $item->unit }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('item_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label for="quantity" class="form-label font-weight-bold">
                    Quantity <span class="text-danger">*</span>
                </label>
                <input type="number"
                       name="quantity"
                       id="quantity"
                       value="{{ old('quantity') }}"
                       min="1"
                       placeholder="Enter quantity"
                       class="form-control @error('quantity') is-invalid @enderror">
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Controls -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Donation Item
                </button>
                <a href="{{ route('backend.donation_items.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
