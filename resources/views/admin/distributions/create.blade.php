@extends('layouts.admin')

@section('title', 'Create New Distribution')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">Create Goods Distribution</h3>
        <a href="{{ route('backend.distributions.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('backend.distributions.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            {{-- Header Details --}}
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title fw-bold mb-0 text-primary">1. Distribution Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Approved Relief Request</label>
                                <select name="request_id" id="request_id" class="form-select @error('request_id') is-invalid @enderror">
                                    <option value="">-- Direct Distribution (No Request) --</option>
                                    @foreach($requests as $req)
                                        <option value="{{ $req->id }}" {{ old('request_id') == $req->id ? 'selected' : '' }}>
                                            Req #{{ $req->id }} - {{ $req->disaster->title ?? 'N/A' }} ({{ $req->location }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Source Warehouse <span class="text-danger">*</span></label>
                                <select name="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                                    <option value="">-- Select Source Warehouse --</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                            {{ $wh->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Handled By Staff <span class="text-danger">*</span></label>
                                <select name="handled_by" class="form-select @error('handled_by') is-invalid @enderror" required>
                                    <option value="">-- Select Handler --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('handled_by', auth()->id()) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Distribution Date <span class="text-danger">*</span></label>
                                <input type="date" name="distribution_date" class="form-control" value="{{ old('distribution_date', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="Completed" selected>Completed (Deduct Stock Now)</option>
                                    <option value="Processing">Processing</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Note</label>
                                <input type="text" name="note" class="form-control" value="{{ old('note') }}" placeholder="Remarks or Vehicle No.">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Line Items Table --}}
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-bold mb-0 text-primary">2. Distribution Items</h5>
                        <button type="button" class="btn btn-sm btn-success" id="add-item-row">
                            <i class="fa-solid fa-plus me-1"></i> Add Item Row
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0" id="items-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">Item Name <span class="text-danger">*</span></th>
                                        <th style="width: 25%">Quantity <span class="text-danger">*</span></th>
                                        <th style="width: 25%">Batch Expiry Date</th>
                                        <th style="width: 10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][item_id]" class="form-select item-select" required>
                                                <option value="">-- Select Item --</option>
                                                @foreach(\App\Models\Item::orderBy('name')->get() as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control" min="1" value="1" required>
                                        </td>
                                        <td>
                                            <input type="date" name="items[0][expiry_date]" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="fa-solid fa-check-circle me-1"></i> Confirm Distribution & Deduct Stock
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    let rowIndex = 1;
    document.getElementById('add-item-row').addEventListener('click', function () {
        let newRow = `
            <tr class="item-row">
                <td>
                    <select name="items[${rowIndex}][item_id]" class="form-select item-select" required>
                        <option value="">-- Select Item --</option>
                        @foreach(\App\Models\Item::orderBy('name')->get() as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${rowIndex}][quantity]" class="form-control" min="1" value="1" required>
                </td>
                <td>
                    <input type="date" name="items[${rowIndex}][expiry_date]" class="form-control">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        document.querySelector('#items-table tbody').insertAdjacentHTML('beforeend', newRow);
        rowIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.closest('.remove-row')) {
            let rowCount = document.querySelectorAll('#items-table tbody tr').length;
            if (rowCount > 1) {
                e.target.closest('tr').remove();
            } else {
                alert('At least one item is required for distribution!');
            }
        }
    });
</script>
@endsection
