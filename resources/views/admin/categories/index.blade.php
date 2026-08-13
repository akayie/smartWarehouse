@extends('layouts.admin')

@section('title', 'ပစ္စည်းအမျိုးအစားများ')

@section('button')
<a href="{{ route('backend.categories.create') }}" class="btn btn-primary btn-sm">
    <i class="fa-solid fa-plus me-1"></i> အမျိုးအစားအသစ်ထည့်ရန်
</a>
@endsection

@section('content')
<div class="row">

    <!-- CATEGORY LIST TABLE -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fa-solid fa-tags me-2"></i>
                    ကယ်ဆယ်ရေးပစ္စည်း အမျိုးအစားများ
                </h5>

                <span class="badge bg-primary rounded-pill">
                    {{ $categories->total() ?? count($categories) }} မျိုး
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;" class="text-center">စဉ်</th>
                                <th style="width: 100px;">ပုံ</th>
                                <th>အမျိုးအစားအမည်</th>
                                <th class="text-center" style="width: 160px;">လုပ်ဆောင်ချက်</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($categories as $key => $category)

                            <tr>

                                <!-- Number -->
                                <td class="text-center text-muted">
                                    {{ $categories->firstItem() ? $categories->firstItem() + $key : $key + 1 }}
                                </td>

                                <!-- Image -->
                                <td>
                                    @if($category->image)

                                        <img src="{{ asset($category->image) }}"
                                             alt="{{ $category->name }}"
                                             class="rounded border shadow-sm"
                                             width="50"
                                             height="50"
                                             style="object-fit: cover;">

                                    @else

                                        <div class="bg-light rounded text-center d-flex align-items-center justify-content-center text-muted border"
                                             style="width: 50px; height: 50px; font-size: 0.8rem;">
                                            <i class="fa-solid fa-image"></i>
                                        </div>

                                    @endif
                                </td>

                                <!-- Category Name -->
                                <td>

                                    <strong class="text-dark">
                                        {{ $category->name }}
                                    </strong>

                                    @if(isset($category->items_count))

                                        <br>

                                        <small class="text-muted">
                                            <i class="fa-solid fa-boxes-stacked me-1"></i>
                                            {{ $category->items_count }} မျိုးသော ပစ္စည်းများ
                                        </small>

                                    @endif

                                </td>

                                <!-- Actions -->
                                <td class="text-center">

                                    <div class="btn-group btn-group-sm" role="group">

                                        <!-- Edit -->
                                        <a href="{{ route('backend.categories.edit', $category->id) }}"
                                           class="btn btn-outline-primary"
                                           title="အမျိုးအစားပြင်ဆင်ရန်">

                                            <i class="fa-solid fa-pen-to-square"></i>
                                            ပြင်ဆင်ရန်

                                        </a>

                                        <!-- Delete -->
                                        <button type="button"
                                                class="btn btn-outline-danger delete-btn"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                title="အမျိုးအစားဖျက်ရန်">

                                            <i class="fa-solid fa-trash-can"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">

                                    <i class="fa-solid fa-folder-open fa-2x mb-2 d-block"></i>

                                    <div>
                                        ပစ္စည်းအမျိုးအစား မှတ်တမ်းမရှိသေးပါ။
                                    </div>

                                    <small>
                                        အမျိုးအစားအသစ်ထည့်ရန်
                                        <strong>“အမျိုးအစားအသစ်ထည့်ရန်”</strong>
                                        ကိုနှိပ်ပါ။
                                    </small>

                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>
            </div>

            <!-- PAGINATION -->
            @if(method_exists($categories, 'links') && $categories->hasPages())

                <div class="card-footer bg-white d-flex justify-content-end py-3">

                    {{ $categories->links('pagination::bootstrap-5') }}

                </div>

            @endif

        </div>
    </div>


    <!-- QUICK ADD SIDEBAR FORM -->
    <div class="col-lg-4 mb-4">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white py-3">

                <h5 class="m-0 font-weight-bold text-dark">

                    <i class="fa-solid fa-square-plus me-2 text-success"></i>

                    အမြန်ထည့်ရန်

                </h5>

            </div>

            <div class="card-body">

                <form action="{{ route('backend.categories.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf


                    <!-- Category Name -->
                    <div class="mb-3">

                        <label for="name"
                               class="form-label font-weight-bold">

                            အမျိုးအစားအမည်
                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="ဥပမာ - အစားအသောက်၊ အဝတ်အထည်"
                               required>

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <!-- Category Image -->
                    <div class="mb-3">

                        <label for="image"
                               class="form-label font-weight-bold">

                            အမျိုးအစားပုံ

                        </label>

                        <input type="file"
                               class="form-control @error('image') is-invalid @enderror"
                               id="image"
                               name="image"
                               accept="image/*">

                        <small class="text-muted">
                            အမျိုးအစားကို ခွဲခြားဖော်ပြရန် ပုံထည့်နိုင်ပါသည်။
                        </small>

                        @error('image')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <!-- Save Button -->
                    <button type="submit" class="btn btn-primary w-100">

                        <i class="fa-solid fa-save me-1"></i>

                        အမျိုးအစားသိမ်းဆည်းရန်

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


<!-- DELETE CONFIRMATION MODAL -->
<div class="modal fade"
     id="deleteModal"
     tabindex="-1"
     aria-labelledby="deleteModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title" id="deleteModalLabel">

                    <i class="fa-solid fa-triangle-exclamation me-2"></i>

                    အမျိုးအစားဖျက်ရန် အတည်ပြုခြင်း

                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>


            <!-- Modal Body -->
            <div class="modal-body">

                <p class="mb-2">
                    အောက်ပါပစ္စည်းအမျိုးအစားကို ဖျက်ရန် သေချာပါသလား။
                </p>

                <strong id="deleteCategoryName"
                        class="text-danger">
                    ဤအမျိုးအစား
                </strong>

                <div class="alert alert-warning mt-3 mb-0">

                    <i class="fa-solid fa-circle-exclamation me-1"></i>

                    ဖျက်ပြီးပါက ဤလုပ်ဆောင်ချက်ကို ပြန်လည်ပြင်ဆင်၍ မရနိုင်ပါ။

                </div>

            </div>


            <!-- Modal Footer -->
            <div class="modal-footer bg-light">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    <i class="fa-solid fa-xmark me-1"></i>

                    မဖျက်တော့ပါ

                </button>

                <form id="deleteForm"
                      action=""
                      method="POST"
                      class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger">

                        <i class="fa-solid fa-trash-can me-1"></i>

                        ဖျက်ရန်အတည်ပြုသည်

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection


@section('script')

<script>

$(document).ready(function() {

    // Delete Button Click Handler
    $('.delete-btn').on('click', function() {

        let id = $(this).data('id');
        let name = $(this).data('name');

        // Laravel Resource Route
        let deleteUrl =
            "{{ route('backend.categories.destroy', ':id') }}"
            .replace(':id', id);

        $('#deleteForm').attr('action', deleteUrl);

        $('#deleteCategoryName').text(
            name ? `"${name}"` : 'ဤပစ္စည်းအမျိုးအစား'
        );

        // Bootstrap 5 Modal
        let deleteModal =
            new bootstrap.Modal(
                document.getElementById('deleteModal')
            );

        deleteModal.show();

    });

});

</script>

@endsection
