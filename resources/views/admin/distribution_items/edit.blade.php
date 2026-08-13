@extends('layouts.admin')

@section('title', 'Edit Distribution Item')

@section('button')
    <a href="{{ route('backend.distribution_items.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to List
    </a>
@endsection

@section('content')
<div id="adm-distribution-item-edit" class="sub-page">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-secondary">
                        <i class="fa-solid fa-pen-to-square me-2 text-warning"></i>Edit Distribution Item Record
                    </h5>
                </div>

                <div class="card-body p-4">

                    <!-- FLASH ERROR MESSAGES -->
                    @if($errors->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ $errors->first('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('backend.distribution_items.update', $distributionItem->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Distribution Reference Dropdown -->
                        <div class="mb-3">
                            <label for="distribution_id" class="form-label fw-bold">
                                Select Distribution Reference <span class="text-danger">*</span>
                            </label>
                            <select name="distribution_id" id="distribution_id" class="form-select @error('distribution_id') is-invalid @enderror" required>
                                <option value="">-- Choose Distribution --</option>
                                @foreach($distributions as $dist)
                                    <option value="{{ $dist->id }}" {{ old('distribution_id', $distributionItem->distribution_id) == $dist->id ? 'selected' : '' }}>
                                        #DSP-{{ $dist->id }} (Warehouse: {{ $dist->warehouse->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('distribution_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Item Dropdown -->
                        <div class="mb-3">
                            <label for="item_id" class="form-label fw-bold">
                                Select Item <span class="text-danger">*</span>
                            </label>
                            <select name="item_id" id="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                                <option value="">-- Choose Item --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ old('item_id', $distributionItem->item_id) == $item->id ? 'selected' : '' }}>
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
                            <label for="quantity" class="form-label fw-bold">
                                Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $distributionItem->quantity) }}" min="1" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted fs-7">Note: Updating quantity will automatically adjust warehouse stock accordingly.</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('backend.distribution_items.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-rotate me-1"></i> Update Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
