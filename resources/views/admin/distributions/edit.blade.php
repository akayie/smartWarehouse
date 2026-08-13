@extends('layouts.admin')

@section('title')
    Add Distribution Item
@endsection

@section('button')
    <a href="{{ route('backend.distribution_items.index') }}" class="btn btn-outline-secondary">
        Back
    </a>
@endsection

@section('content')
    <div id="adm-distribution-create" class="sub-page">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary font-weight-bold">Add Distribution Line Item</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('backend.distribution_items.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Distribution Selection -->
                        <div class="col-md-6 form-group mb-3">
                            <label for="distribution_id" class="font-weight-bold">
                                Target Distribution <span class="text-danger">*</span>
                            </label>
                            <select
                                name="distribution_id"
                                id="distribution_id"
                                class="form-control @error('distribution_id') is-invalid @enderror"
                            >
                                <option value="">-- Select Distribution --</option>
                                @foreach($distributions as $distribution)
                                    <option
                                        value="{{ $distribution->id }}"
                                        {{ old('distribution_id') == $distribution->id ? 'selected' : '' }}
                                    >
                                        #DSP-{{ $distribution->id }} | {{ $distribution->warehouse->name ?? 'N/A' }} → {{ $distribution->request->location ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('distribution_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Item Selection -->
                        <div class="col-md-6 form-group mb-3">
                            <label for="item_id" class="font-weight-bold">
                                Item <span class="text-danger">*</span>
                            </label>
                            <select
                                name="item_id"
                                id="item_id"
                                class="form-control @error('item_id') is-invalid @enderror"
                            >
                                <option value="">-- Select Item --</option>
                                @foreach($items as $item)
                                    <option
                                        value="{{ $item->id }}"
                                        {{ old('item_id') == $item->id ? 'selected' : '' }}
                                    >
                                        {{ $item->name }} @if($item->unit) ({{ $item->unit }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-6 form-group mb-4">
                            <label for="quantity" class="font-weight-bold">
                                Quantity <span class="text-danger">*</span>
                            </label>
                            <input
                                type="number"
                                name="quantity"
                                id="quantity"
                                min="1"
                                value="{{ old('quantity') }}"
                                class="form-control @error('quantity') is-invalid @enderror"
                                placeholder="Enter quantity"
                            >
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('backend.distribution_items.index') }}" class="btn btn-light mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
