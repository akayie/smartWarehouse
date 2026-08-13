@extends('layouts.admin')

@section('title', 'ပစ္စည်းအမျိုးအစားအသစ်ထည့်ရန်')

@section('button')
<a href="{{ route('backend.categories.index') }}" class="btn btn-outline-secondary btn-sm">
    <i class="fa-solid fa-arrow-left me-1"></i> အမျိုးအစားများသို့ ပြန်သွားရန်
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">

        <div class="card shadow-sm border-0">

            <!-- Card Header -->
            <div class="card-header bg-white py-3 d-flex align-items-center">

                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fa-solid fa-square-plus me-2"></i>
                    ပစ္စည်းအမျိုးအစားအသစ် ထည့်သွင်းရန်
                </h5>

            </div>

            <div class="card-body p-4">

                <form action="{{ route('backend.categories.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="createCategoryForm">

                    @csrf

                    <!-- Category Name -->
                    <div class="mb-4">

                        <label for="categoryName"
                               class="form-label font-weight-bold text-dark">

                            ပစ္စည်းအမျိုးအစားအမည်
                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light text-muted">
                                <i class="fa-solid fa-tag"></i>
                            </span>

                            <input type="text"
                                   id="categoryName"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="ဥပမာ - အစားအသောက်၊ သောက်သုံးရေ၊ အဝတ်အထည်"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <small class="text-muted d-block mt-1">
                            ကယ်ဆယ်ရေးပစ္စည်းများကို အမျိုးအစားအလိုက် စနစ်တကျခွဲခြားနိုင်ရန်
                            အမျိုးအစားအမည်ကို ထည့်သွင်းပါ။
                        </small>

                    </div>


                    <!-- Category Image -->
                    <div class="mb-4">

                        <label class="form-label font-weight-bold text-dark">
                            ပစ္စည်းအမျိုးအစားပုံ
                        </label>

                        <div class="row align-items-center g-3 bg-light p-3 rounded border">

                            <!-- Image Preview -->
                            <div class="col-sm-4 text-center">

                                <span class="d-block text-muted small mb-2 font-weight-bold">
                                    ပုံကြိုတင်ကြည့်ရှုရန်
                                </span>

                                <div id="previewContainer">

                                    <div class="bg-white rounded border d-flex flex-column align-items-center justify-content-center mx-auto text-muted"
                                         style="width: 120px; height: 120px;">

                                        <i class="fa-solid fa-image fa-2x mb-1 text-secondary"></i>

                                        <small style="font-size: 0.75rem;">
                                            ပုံရွေးချယ်ထားခြင်း မရှိသေးပါ
                                        </small>

                                    </div>

                                </div>

                            </div>


                            <!-- Upload Field -->
                            <div class="col-sm-8">

                                <label for="categoryImage"
                                       class="form-label small text-muted font-weight-bold">

                                    ပုံဖိုင်ရွေးချယ်ရန်

                                </label>

                                <input type="file"
                                       id="categoryImage"
                                       name="image"
                                       accept="image/*"
                                       class="form-control form-control-sm @error('image') is-invalid @enderror"
                                       onchange="previewImage(event)">

                                <small class="text-muted d-block mt-1"
                                       style="font-size: 0.75rem;">

                                    အသုံးပြုနိုင်သော ဖိုင်အမျိုးအစားများ -
                                    JPG, PNG, WEBP
                                    (အများဆုံး 2MB)

                                </small>

                                @error('image')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>


                    <hr class="my-4 text-muted">


                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('backend.categories.index') }}"
                           class="btn btn-light border px-4">

                            <i class="fa-solid fa-xmark me-1"></i>
                            မလုပ်တော့ပါ

                        </a>

                        <button type="submit"
                                class="btn btn-primary px-4">

                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            အမျိုးအစားသိမ်းဆည်းရန်

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>
@endsection


@section('script')
<script>

    // ပုံကြိုတင်ကြည့်ရှုခြင်း Function
    function previewImage(event) {

        const input = event.target;
        const container = document.getElementById('previewContainer');

        if (input.files && input.files[0]) {

            const file = input.files[0];

            const reader = new FileReader();

            reader.onload = function(e) {

                container.innerHTML = `
                    <img src="${e.target.result}"
                         alt="ပစ္စည်းအမျိုးအစားပုံကြိုတင်ကြည့်ရှုခြင်း"
                         class="img-thumbnail shadow-sm rounded border-primary"
                         style="width: 120px; height: 120px; object-fit: cover;">
                `;

            };

            reader.readAsDataURL(file);
        }
    }

</script>
@endsection
