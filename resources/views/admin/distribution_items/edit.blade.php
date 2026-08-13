@extends('layouts.admin')

@section('title', 'ဖြန့်ဝေမှု ပစ္စည်းမှတ်တမ်း ပြင်ဆင်ရန်')

@section('button')
    <a
        href="{{ route('backend.distribution_items.index') }}"
        class="btn btn-outline-secondary btn-sm">

        <i class="fa-solid fa-arrow-left me-1"></i>
        စာရင်းသို့ ပြန်သွားရန်

    </a>
@endsection

@section('content')

<div id="adm-distribution-item-edit" class="sub-page">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow-sm border-0">

                <!-- Header -->
                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold text-secondary">

                        <i class="fa-solid fa-pen-to-square me-2 text-warning"></i>

                        ဖြန့်ဝေမှု ပစ္စည်းမှတ်တမ်း ပြင်ဆင်ရန်

                    </h5>

                </div>


                <div class="card-body p-4">

                    <!-- Error Message -->

                    @if($errors->has('error'))

                        <div
                            class="alert alert-danger alert-dismissible fade show"
                            role="alert">

                            <i class="fa-solid fa-triangle-exclamation me-2"></i>

                            {{ $errors->first('error') }}

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close">
                            </button>

                        </div>

                    @endif


                    <!-- Form -->

                    <form
                        action="{{ route('backend.distribution_items.update', $distributionItem->id) }}"
                        method="POST">

                        @csrf

                        @method('PUT')


                        <!-- Distribution Reference -->

                        <div class="mb-4">

                            <label
                                for="distribution_id"
                                class="form-label fw-bold">

                                ဖြန့်ဝေမှု ရည်ညွှန်းအမှတ်

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="distribution_id"
                                id="distribution_id"
                                class="form-select @error('distribution_id') is-invalid @enderror"
                                required>

                                <option value="">
                                    -- ဖြန့်ဝေမှုကို ရွေးချယ်ပါ --
                                </option>

                                @foreach($distributions as $dist)

                                    <option
                                        value="{{ $dist->id }}"
                                        {{ old('distribution_id', $distributionItem->distribution_id) == $dist->id ? 'selected' : '' }}>

                                        #DSP-{{ $dist->id }}

                                        |
                                        ဂိုဒေါင်:
                                        {{ $dist->warehouse->name ?? 'မသိရှိပါ' }}

                                    </option>

                                @endforeach

                            </select>

                            @error('distribution_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">

                                သက်ဆိုင်ရာ ပစ္စည်းဖြန့်ဝေမှု မှတ်တမ်းကို ရွေးချယ်ပါ။

                            </small>

                        </div>


                        <!-- Item -->

                        <div class="mb-4">

                            <label
                                for="item_id"
                                class="form-label fw-bold">

                                ပစ္စည်းအမည်

                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="item_id"
                                id="item_id"
                                class="form-select @error('item_id') is-invalid @enderror"
                                required>

                                <option value="">
                                    -- ပစ္စည်းကို ရွေးချယ်ပါ --
                                </option>

                                @foreach($items as $item)

                                    <option
                                        value="{{ $item->id }}"
                                        {{ old('item_id', $distributionItem->item_id) == $item->id ? 'selected' : '' }}>

                                        {{ $item->name }}

                                        @if($item->unit)
                                            ({{ $item->unit }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('item_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">

                                ဖြန့်ဝေထားသည့် ကယ်ဆယ်ရေးပစ္စည်းကို ရွေးချယ်ပါ။

                            </small>

                        </div>


                        <!-- Quantity -->

                        <div class="mb-4">

                            <label
                                for="quantity"
                                class="form-label fw-bold">

                                ဖြန့်ဝေသည့် အရေအတွက်

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="number"
                                name="quantity"
                                id="quantity"
                                class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity', $distributionItem->quantity) }}"
                                min="1"
                                required>

                            @error('quantity')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">

                                အရေအတွက်ကို ပြင်ဆင်ပါက သက်ဆိုင်ရာ ဂိုဒေါင်၏
                                ပစ္စည်းလက်ကျန်ကို အလိုအလျောက် ပြန်လည်တွက်ချက်ပေးမည်ဖြစ်သည်။

                            </small>

                        </div>


                        <hr>


                        <!-- Buttons -->

                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a
                                href="{{ route('backend.distribution_items.index') }}"
                                class="btn btn-light">

                                <i class="fa-solid fa-xmark me-1"></i>

                                မပြင်တော့ပါ

                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="fa-solid fa-rotate me-1"></i>

                                ပြင်ဆင်ချက်များ သိမ်းဆည်းရန်

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
