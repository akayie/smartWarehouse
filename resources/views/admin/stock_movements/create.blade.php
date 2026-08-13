@extends('layouts.admin')

@section('title', 'စတော့ အဝင်/အထွက် စာရင်းသွင်းရန်')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h1 class="h3 text-gray-800">စတော့ အဝင်/အထွက် စာရင်းသွင်းရန်</h1>
    </div>

    <div class="card shadow mb-4 border-0">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">စတော့ အဝင်/အထွက် အသစ်ထည့်သွင်းမည်</h6>
        </div>
        <div class="card-body">

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('backend.stock-movements.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    {{-- Item Selection --}}
                    <div class="col-md-6">
                        <label for="item_id" class="form-label fw-bold">ပစ္စည်းအမည် ရွေးချယ်ပါ <span class="text-danger">*</span></label>
                        <select name="item_id" id="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                            <option value="">-- ပစ္စည်းအမည် ရွေးချယ်ပါ --</option>
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
                        <label for="type" class="form-label fw-bold">အမျိုးအစား <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" onchange="toggleTargetWarehouse(this.value)" required>
                            <option value="IN" {{ old('type', 'IN') == 'IN' ? 'selected' : '' }}>စတော့ အဝင် (+)</option>
                            <option value="OUT" {{ old('type') == 'OUT' ? 'selected' : '' }}>စတော့ အထွက် (-)</option>
                            <option value="TRANSFER" {{ old('type') == 'TRANSFER' ? 'selected' : '' }}>စတော့ လွှဲပြောင်း (->)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Source Warehouse --}}
                    <div class="col-md-6">
                        <label for="warehouse_id" class="form-label fw-bold">မူလ / လက်ရှိ ကုန်လှောင်ရုံ <span class="text-danger">*</span></label>
                        <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                            <option value="">-- ကုန်လှောင်ရုံ ရွေးချယ်ပါ --</option>
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
                        <label for="target_warehouse_id" class="form-label fw-bold">လက်ခံမည့် ကုန်လှောင်ရုံ <span class="text-danger">*</span></label>
                        <select name="target_warehouse_id" id="target_warehouse_id" class="form-select @error('target_warehouse_id') is-invalid @enderror">
                            <option value="">-- လက်ခံမည့် ကုန်လှောင်ရုံ ရွေးချယ်ပါ --</option>
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
                        <label for="quantity" class="form-label fw-bold">အရေအတွက် <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Reference / Note --}}
                    <div class="col-md-6">
                        <label for="reference" class="form-label fw-bold">အကိုးအကား / မှတ်ချက်</label>
                        <input type="text" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference') }}" placeholder="ဥပမာ - DON-001 သို့မဟုတ် PO-1002">
                        @error('reference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Created By Field --}}
                    <div class="col-md-6">
                        <label for="created_by" class="form-label fw-bold">စာရင်းသွင်းသူ <span class="text-danger">*</span></label>
                        <select name="created_by" id="created_by" class="form-select @error('created_by') is-invalid @enderror" required>
                            <option value="">-- စာရင်းသွင်းသူ ရွေးချယ်ပါ --</option>
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
                            <i class="fa-solid fa-floppy-disk me-1"></i> စာရင်းသိမ်းဆည်းမည်
                        </button>
                        <a href="{{ route('backend.stock-movements.index') }}" class="btn btn-light border px-4 ms-2">
                            မလုပ်ဆောင်တော့ပါ
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
