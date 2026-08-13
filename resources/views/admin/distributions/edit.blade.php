@extends('layouts.admin')

@section('title')
    ဖြန့်ဝေမည့်ပစ္စည်း ထည့်သွင်းရန်
@endsection

@section('button')
    <a href="{{ route('backend.distribution_items.index') }}"
       class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i>
        နောက်သို့
    </a>
@endsection

@section('content')

<div id="adm-distribution-create" class="sub-page">

    <div class="card shadow-sm border-0">

        {{-- Card Header --}}
        <div class="card-header bg-white py-3">

            <h5 class="mb-0 text-primary fw-bold">
                <i class="fa-solid fa-box-open me-2"></i>
                ဖြန့်ဝေမည့်ပစ္စည်း ထည့်သွင်းခြင်း
            </h5>

        </div>


        {{-- Card Body --}}
        <div class="card-body">

            {{-- Validation Error Summary --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    <strong>
                        အချက်အလက်များကို ပြန်လည်စစ်ဆေးပေးပါ။
                    </strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form action="{{ route('backend.distribution_items.store') }}"
                  method="POST">

                @csrf

                <div class="row">

                    {{-- Distribution Selection --}}
                    <div class="col-md-6 form-group mb-3">

                        <label for="distribution_id"
                               class="form-label fw-bold">

                            ဖြန့်ဝေမှုမှတ်တမ်း
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="distribution_id"
                            id="distribution_id"
                            class="form-select @error('distribution_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- ဖြန့်ဝေမှုကို ရွေးချယ်ပါ --
                            </option>

                            @foreach($distributions as $distribution)

                                <option
                                    value="{{ $distribution->id }}"
                                    {{ old('distribution_id') == $distribution->id ? 'selected' : '' }}
                                >

                                    #DSP-{{ $distribution->id }}

                                    |

                                    {{ $distribution->warehouse->name ?? 'သိုလှောင်ရုံ မသတ်မှတ်ရသေးပါ' }}

                                    →

                                    {{ $distribution->request->location ?? 'နေရာ မသတ်မှတ်ရသေးပါ' }}

                                </option>

                            @endforeach

                        </select>

                        @error('distribution_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <div class="form-text text-muted">
                            ဤပစ္စည်းကို ထည့်သွင်းမည့် ဖြန့်ဝေမှုမှတ်တမ်းကို ရွေးချယ်ပါ။
                        </div>

                    </div>


                    {{-- Item Selection --}}
                    <div class="col-md-6 form-group mb-3">

                        <label for="item_id"
                               class="form-label fw-bold">

                            ကယ်ဆယ်ရေးပစ္စည်း
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="item_id"
                            id="item_id"
                            class="form-select @error('item_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                -- ပစ္စည်းကို ရွေးချယ်ပါ --
                            </option>

                            @foreach($items as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ old('item_id') == $item->id ? 'selected' : '' }}
                                >

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

                        <div class="form-text text-muted">
                            ဖြန့်ဝေမည့် ကယ်ဆယ်ရေးပစ္စည်းကို ရွေးချယ်ပါ။
                        </div>

                    </div>


                    {{-- Quantity --}}
                    <div class="col-md-6 form-group mb-4">

                        <label for="quantity"
                               class="form-label fw-bold">

                            ဖြန့်ဝေမည့်ပမာဏ
                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                name="quantity"
                                id="quantity"
                                min="1"
                                value="{{ old('quantity') }}"
                                class="form-control @error('quantity') is-invalid @enderror"
                                placeholder="ပမာဏထည့်သွင်းပါ"
                                required
                            >

                            <span class="input-group-text">
                                ယူနစ်
                            </span>

                        </div>

                        @error('quantity')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                        <div class="form-text text-muted">
                            သိုလှောင်ရုံမှ ထုတ်ပေးမည့် ပစ္စည်းအရေအတွက်ကို ထည့်သွင်းပါ။
                        </div>

                    </div>

                </div>


                <hr>


                {{-- Form Actions --}}
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('backend.distribution_items.index') }}"
                       class="btn btn-light me-2">

                        <i class="fa-solid fa-xmark me-1"></i>
                        ပယ်ဖျက်ရန်

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fa-solid fa-save me-1"></i>
                        ပစ္စည်းထည့်သွင်းရန်

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
