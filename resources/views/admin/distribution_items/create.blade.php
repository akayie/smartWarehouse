@extends('layouts.admin')

@section('title', 'ဖြန့်ဝေမည့် ပစ္စည်းထည့်ရန်')

@section('button')
    <a href="{{ route('backend.distribution_items.index') }}"
       class="btn btn-outline-secondary">

        <i class="fa-solid fa-arrow-left me-1"></i>
        စာရင်းသို့ ပြန်သွားရန်

    </a>
@endsection

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        <!-- Header -->
        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="fa-solid fa-boxes-stacked me-2"></i>

                ဖြန့်ဝေမည့် ပစ္စည်းထည့်ရန်

            </h5>

        </div>

        <div class="card-body">

            {{-- General Error --}}
            @if($errors->has('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="fa-solid fa-circle-exclamation me-2"></i>

                    {{ $errors->first('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            {{-- Form --}}
            <form
                action="{{ route('backend.distribution_items.store') }}"
                method="POST">

                @csrf


                <!-- Distribution Selection -->
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
                                {{ old('distribution_id') == $dist->id ? 'selected' : '' }}>

                                #DSP-{{ $dist->id }}

                                |
                                ဂိုဒေါင်:
                                {{ $dist->warehouse->name ?? 'မသိရှိပါ' }}

                                |

                                တောင်းခံမှု:
                                @if($dist->relief_request_id)
                                    #REQ-{{ $dist->relief_request_id }}
                                @else
                                    တိုက်ရိုက်ဖြန့်ဝေမှု
                                @endif

                            </option>

                        @endforeach

                    </select>

                    @error('distribution_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                    <small class="text-muted">

                        ပစ္စည်းဖြန့်ဝေမှု မှတ်တမ်းကို ရွေးချယ်ပါ။

                    </small>

                </div>


                <!-- Item Selection -->
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
                                {{ old('item_id') == $item->id ? 'selected' : '' }}>

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

                        ဖြန့်ဝေမည့် ကယ်ဆယ်ရေးပစ္စည်းကို ရွေးချယ်ပါ။

                    </small>

                </div>


                <!-- Quantity -->
                <div class="mb-4">

                    <label
                        for="quantity"
                        class="form-label fw-bold">

                        ဖြန့်ဝေမည့် အရေအတွက်

                        <span class="text-danger">*</span>

                    </label>

                    <input
                        type="number"
                        name="quantity"
                        id="quantity"
                        class="form-control @error('quantity') is-invalid @enderror"
                        value="{{ old('quantity', 1) }}"
                        min="1"
                        required>

                    @error('quantity')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                    <small class="text-muted">

                        ဖြန့်ဝေမည့် ပစ္စည်းအရေအတွက်ကို ထည့်သွင်းပါ။

                    </small>

                </div>


                <hr>


                <!-- Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('backend.distribution_items.index') }}"
                        class="btn btn-light">

                        <i class="fa-solid fa-xmark me-1"></i>

                        ပယ်ဖျက်ရန်

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fa-solid fa-save me-1"></i>

                        ပစ္စည်းမှတ်တမ်း သိမ်းဆည်းရန်

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
