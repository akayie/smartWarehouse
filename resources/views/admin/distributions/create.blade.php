@extends('layouts.admin')

@section('title', 'ပစ္စည်းဖြန့်ဝေမှုအသစ် ပြုလုပ်ရန်')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark mb-0">
            ကယ်ဆယ်ရေးပစ္စည်း ဖြန့်ဝေမှုအသစ် ပြုလုပ်ရန်
        </h3>

        <a href="{{ route('backend.distributions.index') }}"
           class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>
            စာရင်းသို့ ပြန်သွားရန်
        </a>
    </div>


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <i class="fa-solid fa-circle-exclamation me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    <form action="{{ route('backend.distributions.store') }}"
          method="POST">

        @csrf

        <div class="row g-4">


            {{-- =========================================================
                 Distribution Details
            ========================================================== --}}

            <div class="col-lg-12">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white py-3">

                        <h5 class="card-title fw-bold mb-0 text-primary">

                            ၁။ ဖြန့်ဝေမှု အချက်အလက်များ

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Relief Request --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">

                                    အတည်ပြုထားသော ကယ်ဆယ်ရေးတောင်းဆိုမှု

                                </label>


                                <select name="request_id"
                                        id="request_id"
                                        class="form-select @error('request_id') is-invalid @enderror">

                                    <option value="">
                                        -- တောင်းဆိုမှုမရှိဘဲ တိုက်ရိုက်ဖြန့်ဝေခြင်း --
                                    </option>


                                    @foreach($requests as $req)

                                        <option value="{{ $req->id }}"
                                            {{ old('request_id') == $req->id ? 'selected' : '' }}>

                                            တောင်းဆိုမှု #{{ $req->id }}
                                            -
                                            {{ $req->disaster->title ?? 'N/A' }}

                                            ({{ $req->location }})

                                        </option>

                                    @endforeach

                                </select>


                                @error('request_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- Warehouse --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">

                                    ပစ္စည်းထုတ်ပေးမည့် ဂိုဒေါင်

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="warehouse_id"
                                        class="form-select @error('warehouse_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- ဂိုဒေါင်ရွေးချယ်ပါ --
                                    </option>


                                    @foreach($warehouses as $wh)

                                        <option value="{{ $wh->id }}"
                                            {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>

                                            {{ $wh->name }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('warehouse_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- Handled By --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">

                                    တာဝန်ယူဆောင်ရွက်သူ

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="handled_by"
                                        class="form-select @error('handled_by') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- တာဝန်ယူသူ ရွေးချယ်ပါ --
                                    </option>


                                    @foreach($users as $user)

                                        <option value="{{ $user->id }}"
                                            {{ old('handled_by', auth()->id()) == $user->id ? 'selected' : '' }}>

                                            {{ $user->name }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('handled_by')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- Distribution Date --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">

                                    ဖြန့်ဝေသည့် ရက်စွဲ

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="date"
                                       name="distribution_date"
                                       class="form-control @error('distribution_date') is-invalid @enderror"
                                       value="{{ old('distribution_date', date('Y-m-d')) }}"
                                       required>


                                @error('distribution_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- Status --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">

                                    အခြေအနေ

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required>

                                    <option value="Completed" selected>
                                        ပြီးစီးပြီး (လက်ကျန်ပစ္စည်းမှ နုတ်မည်)
                                    </option>

                                    <option value="Processing">
                                        ဆောင်ရွက်နေဆဲ
                                    </option>

                                </select>


                                @error('status')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- Note --}}
                            <div class="col-md-4">

                                <label class="form-label fw-bold">

                                    မှတ်ချက်

                                </label>


                                <input type="text"
                                       name="note"
                                       class="form-control"
                                       value="{{ old('note') }}"
                                       placeholder="မှတ်ချက် သို့မဟုတ် ယာဉ်နံပါတ် ထည့်သွင်းပါ">

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =========================================================
                 Distribution Items
            ========================================================== --}}

            <div class="col-lg-12">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

                        <h5 class="card-title fw-bold mb-0 text-primary">

                            ၂။ ဖြန့်ဝေမည့် ကယ်ဆယ်ရေးပစ္စည်းများ

                        </h5>


                        <button type="button"
                                class="btn btn-sm btn-success"
                                id="add-item-row">

                            <i class="fa-solid fa-plus me-1"></i>

                            ပစ္စည်းတစ်ကြောင်း ထပ်ထည့်ရန်

                        </button>

                    </div>



                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-bordered align-middle mb-0"
                                   id="items-table">

                                <thead class="table-light">

                                    <tr>

                                        <th style="width: 40%">

                                            ပစ္စည်းအမည်

                                            <span class="text-danger">*</span>

                                        </th>


                                        <th style="width: 25%">

                                            အရေအတွက်

                                            <span class="text-danger">*</span>

                                        </th>


                                        <th style="width: 25%">

                                            Batch သက်တမ်းကုန်ဆုံးရက်

                                        </th>


                                        <th style="width: 10%"
                                            class="text-center">

                                            လုပ်ဆောင်ချက်

                                        </th>

                                    </tr>

                                </thead>



                                <tbody>

                                    <tr class="item-row">

                                        {{-- Item --}}
                                        <td>

                                            <select name="items[0][item_id]"
                                                    class="form-select item-select"
                                                    required>

                                                <option value="">
                                                    -- ပစ္စည်းရွေးချယ်ပါ --
                                                </option>


                                                @foreach(\App\Models\Item::orderBy('name')->get() as $item)

                                                    <option value="{{ $item->id }}">

                                                        {{ $item->name }}

                                                        @if($item->unit)
                                                            ({{ $item->unit }})
                                                        @endif

                                                    </option>

                                                @endforeach

                                            </select>

                                        </td>



                                        {{-- Quantity --}}
                                        <td>

                                            <input type="number"
                                                   name="items[0][quantity]"
                                                   class="form-control"
                                                   min="1"
                                                   value="1"
                                                   required
                                                   placeholder="အရေအတွက် ထည့်ပါ">

                                        </td>



                                        {{-- Expiry --}}
                                        <td>

                                            <input type="date"
                                                   name="items[0][expiry_date]"
                                                   class="form-control">

                                        </td>



                                        {{-- Remove --}}
                                        <td class="text-center">

                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm remove-row">

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



            {{-- Submit --}}
            <div class="col-12 text-end">

                <a href="{{ route('backend.distributions.index') }}"
                   class="btn btn-light me-2">

                    ပယ်ဖျက်ရန်

                </a>


                <button type="submit"
                        class="btn btn-primary btn-lg px-5">

                    <i class="fa-solid fa-check-circle me-1"></i>

                    ဖြန့်ဝေမှု အတည်ပြုရန်

                </button>

            </div>

        </div>

    </form>

</div>



<script>

    let rowIndex = 1;


    // Add Item Row
    document.getElementById('add-item-row').addEventListener('click', function () {

        let newRow = `

            <tr class="item-row">

                <td>

                    <select name="items[${rowIndex}][item_id]"
                            class="form-select item-select"
                            required>

                        <option value="">
                            -- ပစ္စည်းရွေးချယ်ပါ --
                        </option>

                        @foreach(\App\Models\Item::orderBy('name')->get() as $item)

                            <option value="{{ $item->id }}">

                                {{ $item->name }}

                                @if($item->unit)
                                    ({{ $item->unit }})
                                @endif

                            </option>

                        @endforeach

                    </select>

                </td>


                <td>

                    <input type="number"
                           name="items[${rowIndex}][quantity]"
                           class="form-control"
                           min="1"
                           value="1"
                           required
                           placeholder="အရေအတွက် ထည့်ပါ">

                </td>


                <td>

                    <input type="date"
                           name="items[${rowIndex}][expiry_date]"
                           class="form-control">

                </td>


                <td class="text-center">

                    <button type="button"
                            class="btn btn-outline-danger btn-sm remove-row">

                        <i class="fa-solid fa-trash"></i>

                    </button>

                </td>

            </tr>

        `;


        document
            .querySelector('#items-table tbody')
            .insertAdjacentHTML('beforeend', newRow);


        rowIndex++;

    });



    // Remove Item Row
    document.addEventListener('click', function (e) {

        if (e.target && e.target.closest('.remove-row')) {

            let rowCount =
                document.querySelectorAll('#items-table tbody tr').length;


            if (rowCount > 1) {

                e.target.closest('tr').remove();

            } else {

                alert(
                    'ဖြန့်ဝေမည့် ပစ္စည်းအနည်းဆုံး တစ်မျိုး ထည့်သွင်းရပါမည်။'
                );

            }

        }

    });

</script>

@endsection
