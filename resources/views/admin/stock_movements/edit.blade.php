@extends('layouts.admin')

@section('title')
    စတော့ အဝင်/အထွက် ပြင်ဆင်ရန်
@endsection

@section('button')
    <a href="{{ route('backend.stock-movements.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> နောက်သို့
    </a>
@endsection

@section('content')

<div class="card shadow border-0 mb-4">

    <div class="card-header bg-white py-3">
        <h4 class="mb-0 fw-bold text-dark">စတော့ အဝင်/အထွက် ပြင်ဆင်ရန်</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('backend.stock-movements.update', $stockMovement->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Item Selection --}}
            <div class="form-group mb-3">
                <label for="item_id" class="form-label fw-bold">ပစ္စည်းအမည် <span class="text-danger">*</span></label>
                <select name="item_id" id="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}"
                            {{ old('item_id', $stockMovement->item_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->name }} {{ $item->code ? '('.$item->code.')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('item_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Warehouse Selection --}}
            <div class="form-group mb-3">
                <label for="warehouse_id" class="form-label fw-bold">ကုန်လှောင်ရုံ / စခန်း <span class="text-danger">*</span></label>
                <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}"
                            {{ old('warehouse_id', $stockMovement->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
                @error('warehouse_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Movement Type --}}
            <div class="form-group mb-3">
                <label for="type" class="form-label fw-bold">အမျိုးအစား <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="IN" {{ old('type', $stockMovement->type) == 'IN' ? 'selected' : '' }}>
                        စတော့ အဝင် (Stock IN)
                    </option>
                    <option value="OUT" {{ old('type', $stockMovement->type) == 'OUT' ? 'selected' : '' }}>
                        စတော့ အထွက် (Stock OUT)
                    </option>
                    <option value="TRANSFER" {{ old('type', $stockMovement->type) == 'TRANSFER' ? 'selected' : '' }}>
                        လွှဲပြောင်း (Transfer)
                    </option>
                </select>
                @error('type')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Quantity --}}
            <div class="form-group mb-3">
                <label for="quantity" class="form-label fw-bold">အရေအတွက် <span class="text-danger">*</span></label>
                <input type="number"
                       name="quantity"
                       id="quantity"
                       min="1"
                       value="{{ old('quantity', $stockMovement->quantity) }}"
                       class="form-control @error('quantity') is-invalid @enderror"
                       required>
                @error('quantity')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Reference --}}
            <div class="form-group mb-4">
                <label for="reference" class="form-label fw-bold">အကိုးအကား / မှတ်ချက်</label>
                <input type="text"
                       name="reference"
                       id="reference"
                       value="{{ old('reference', $stockMovement->reference) }}"
                       class="form-control @error('reference') is-invalid @enderror"
                       placeholder="ဥပမာ - DON-001 သို့မဟုတ် PO-1002">
                @error('reference')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Form Actions --}}
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> ပြင်ဆင်ချက် သိမ်းဆည်းမည်
                </button>
                <a href="{{ route('backend.stock-movements.index') }}" class="btn btn-light border px-4 ms-2">
                    မလုပ်ဆောင်တော့ပါ
                </a>
            </div>

        </form>

    </div>

</div>

@endsection
