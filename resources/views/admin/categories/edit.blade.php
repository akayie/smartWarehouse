@extends('layouts.admin')

@section('title', 'ပစ္စည်းအမျိုးအစား ပြင်ဆင်ရန်')

@section('button')
<a href="{{ route('backend.categories.index') }}"
   class="btn btn-outline-secondary btn-sm">
    <i class="fa-solid fa-arrow-left me-1"></i>
    အမျိုးအစားစာရင်းသို့ ပြန်သွားရန်
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">

        <div class="card shadow-sm border-0">

            <!-- HEADER -->
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">

                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fa-solid fa-pen-to-square me-2"></i>
                    ပစ္စည်းအမျိုးအစား ပြင်ဆင်ရန်
                </h5>

                <span class="badge bg-light text-dark border">
                    ID: #{{ $category->id }}
                </span>

            </div>


            <div class="card-body p-4">

                <form action="{{ route('backend.categories.update', $category->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="editCategoryForm">

                    @csrf
                    @method('PUT')

                    <!-- OLD IMAGE PATH -->
                    <input type="hidden"
                           name="old_image"
                           value="{{ $category->image }}">


                    <!-- CATEGORY NAME -->
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
                                   value="{{ old('name', $category->name) }}"
                                   placeholder="ဥပမာ - အစားအစာ၊ သောက်ရေ၊ စောင်များ"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    <!-- IMAGE SECTION -->
                    <div class="mb-4">

                        <label class="form-label font-weight-bold text-dark">
                            ပစ္စည်းအမျိုးအစားပုံ
                        </label>

                        <div class="row align-items-center g-3 bg-light p-3 rounded border">


                            <!-- CURRENT IMAGE -->
                            <div class="col-sm-4 text-center border-end-sm">

                                <span class="d-block text-muted small mb-2 font-weight-bold">
                                    လက်ရှိပုံ
                                </span>

                                @if($category->image)

                                    <img src="{{ asset($category->image) }}"
                                         alt="{{ $category->name }}"
                                         class="img-thumbnail shadow-sm rounded"
                                         style="width: 120px;
                                                height: 120px;
                                                object-fit: cover;">

                                @else

                                    <div class="bg-white rounded border
                                                d-flex flex-column
                                                align-items-center
                                                justify-content-center
                                                mx-auto text-muted"
                                         style="width: 120px;
                                                height: 120px;">

                                        <i class="fa-solid fa-image fa-2x mb-1"></i>

                                        <small>
                                            ပုံမရှိပါ
                                        </small>

                                    </div>

                                @endif

                            </div>


                            <!-- NEW IMAGE PREVIEW -->
                            <div class="col-sm-4 text-center">

                                <span class="d-block text-muted small mb-2 font-weight-bold">
                                    ပုံအသစ် အကြိုကြည့်ရှုရန်
                                </span>

                                <div id="previewContainer">

                                    <div class="bg-white rounded border
                                                d-flex flex-column
                                                align-items-center
                                                justify-content-center
                                                mx-auto text-muted"
                                         style="width: 120px;
                                                height: 120px;">

                                        <i class="fa-solid fa-eye-slash fa-2x mb-1"></i>

                                        <small>
                                            ပုံအသစ်ရွေးချယ်ပါ
                                        </small>

                                    </div>

                                </div>

                            </div>


                            <!-- UPLOAD NEW IMAGE -->
                            <div class="col-sm-4">

                                <label for="categoryImage"
                                       class="form-label small text-muted font-weight-bold">

                                    ပုံအသစ် အစားထိုးတင်ရန်

                                </label>

                                <input type="file"
                                       id="categoryImage"
                                       name="image"
                                       accept="image/*"
                                       class="form-control form-control-sm @error('image') is-invalid @enderror"
                                       onchange="previewImage(event)">

                                <small class="text-muted d-block mt-1"
                                       style="font-size: 0.75rem;">

                                    အသုံးပြုနိုင်သောဖိုင်အမျိုးအစားများ -
                                    JPG, PNG, WEBP
                                    <br>
                                    အများဆုံးဖိုင်အရွယ်အစား - 2MB

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


                    <!-- ACTION BUTTONS -->
                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('backend.categories.index') }}"
                           class="btn btn-light border px-4">

                            <i class="fa-solid fa-xmark me-1"></i>
                            မလုပ်တော့ပါ

                        </a>

                        <button type="submit"
                                class="btn btn-primary px-4">

                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            အမျိုးအစား ပြင်ဆင်သိမ်းရန်

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

function previewImage(event) {

    const input = event.target;
    const container = document.getElementById('previewContainer');

    if (input.files && input.files[0]) {

        const file = input.files[0];

        const reader = new FileReader();

        reader.onload = function(e) {

            container.innerHTML = `
                <img src="${e.target.result}"
                     alt="ပုံအသစ် အကြိုကြည့်ရှုရန်"
                     class="img-thumbnail shadow-sm rounded border-primary"
                     style="
                        width: 120px;
                        height: 120px;
                        object-fit: cover;
                     ">
            `;

        };

        reader.readAsDataURL(file);

    } else {

        container.innerHTML = `
            <div class="bg-white rounded border
                        d-flex flex-column
                        align-items-center
                        justify-content-center
                        mx-auto text-muted"
                 style="
                    width: 120px;
                    height: 120px;
                 ">

                <i class="fa-solid fa-eye-slash fa-2x mb-1"></i>

                <small>
                    ပုံအသစ်ရွေးချယ်ပါ
                </small>

            </div>
        `;

    }

}

</script>
@endsection
