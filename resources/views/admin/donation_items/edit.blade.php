@extends('layouts.admin')

@section('title', 'Edit Donation Item')

@section('button')
<a href="{{ route('backend.donation_items.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back
</a>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-dark">Edit Donation Item #{{ $donationItem->id }}</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('backend.donation_items.update', $donationItem->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Donation Selection -->
            <div class="mb-3">
                <label for="donation_id" class="form-label font-weight-bold">
                    Donation <span class="text-danger">*</span>
                </label>
                <select name="donation_id"
                        id="donation_id"
                        class="form-select @error('donation_id') is-invalid @enderror">
                    @foreach($donations as $donation)
                        <option value="{{ $donation->id }}"
                            {{ old('donation_id', $donationItem->donation_id) == $donation->id ? 'selected' : '' }}>
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
                    @foreach($items as $item)
                        <option value="{{ $item->id }}"
                            {{ old('item_id', $donationItem->item_id) == $item->id ? 'selected' : '' }}>
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
                       min="1"
                       value="{{ old('quantity', $donationItem->quantity) }}"
                       class="form-control @error('quantity') is-invalid @enderror">
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Controls -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i> Update Donation Item
                </button>
                <a href="{{ route('backend.donation_items.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
