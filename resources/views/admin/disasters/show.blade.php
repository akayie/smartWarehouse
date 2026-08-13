@extends('layouts.admin')

@section('title', 'ဘေးအန္တရာယ်ဖြစ်ရပ် အသေးစိတ်')

@section('button')
<a href="{{ route('backend.disasters.index') }}"
   class="btn btn-outline-secondary btn-sm">

    <i class="fa-solid fa-arrow-left me-1"></i>
    ဘေးအန္တရာယ်ဖြစ်ရပ်များသို့ ပြန်သွားရန်

</a>
@endsection

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-9 col-md-11">

        {{-- ============================= --}}
        {{-- Header Card --}}
        {{-- ============================= --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="d-flex align-items-center">

                    <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-circle me-3">

                        <i class="fa-solid fa-triangle-exclamation fa-2x"></i>

                    </div>

                    <div>

                        <h4 class="m-0 font-weight-bold text-dark">

                            {{ $disaster->name }}

                        </h4>

                        <span class="text-muted small">

                            <i class="fa-solid fa-location-dot text-danger me-1"></i>

                            {{ $disaster->location }}

                        </span>

                    </div>

                </div>


                {{-- Status + Edit --}}

                <div class="d-flex align-items-center gap-2">

                    @php
                        $status = strtolower($disaster->status ?? '');
                    @endphp


                    @if($status === 'active')

                        <span class="badge bg-success fs-6 px-3 py-2">

                            <i class="fa-solid fa-circle-dot me-1"></i>

                            လက်ရှိဖြစ်ပွားနေ

                        </span>

                    @elseif($status === 'completed')

                        <span class="badge bg-primary fs-6 px-3 py-2">

                            <i class="fa-solid fa-circle-check me-1"></i>

                            ပြီးဆုံးပြီ

                        </span>

                    @else

                        <span class="badge bg-danger fs-6 px-3 py-2">

                            <i class="fa-solid fa-circle-xmark me-1"></i>

                            {{ $disaster->status ?? 'ပယ်ဖျက်ထားသည်' }}

                        </span>

                    @endif


                    <a href="{{ route('backend.disasters.edit', $disaster->id) }}"
                       class="btn btn-outline-warning text-dark btn-sm px-3">

                        <i class="fa-solid fa-pen me-1"></i>

                        ပြင်ဆင်ရန်

                    </a>

                </div>

            </div>

        </div>


        {{-- ============================= --}}
        {{-- Event Overview --}}
        {{-- ============================= --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="m-0 font-weight-bold text-dark">

                    <i class="fa-solid fa-circle-info me-2 text-primary"></i>

                    ဖြစ်ရပ်အချက်အလက် အကျဉ်းချုပ်

                </h6>

            </div>


            <div class="card-body p-4">

                <div class="row g-4">


                    {{-- Disaster ID --}}

                    <div class="col-md-4 col-sm-6">

                        <div class="p-3 bg-light rounded border">

                            <small class="text-muted d-block text-uppercase font-weight-bold mb-1">

                                ဖြစ်ရပ် ID

                            </small>

                            <span class="h6 mb-0 text-dark fw-bold">

                                #{{ $disaster->id }}

                            </span>

                        </div>

                    </div>


                    {{-- Disaster Type --}}

                    <div class="col-md-4 col-sm-6">

                        <div class="p-3 bg-light rounded border">

                            <small class="text-muted d-block text-uppercase font-weight-bold mb-1">

                                ဘေးအန္တရာယ်အမျိုးအစား

                            </small>

                            <span class="badge bg-light text-dark border fs-6">

                                {{ $disaster->type ?? 'မသတ်မှတ်ရသေးပါ' }}

                            </span>

                        </div>

                    </div>


                    {{-- Location --}}

                    <div class="col-md-4 col-sm-6">

                        <div class="p-3 bg-light rounded border">

                            <small class="text-muted d-block text-uppercase font-weight-bold mb-1">

                                ဖြစ်ပွားရာဒေသ

                            </small>

                            <span class="h6 mb-0 text-dark fw-bold">

                                <i class="fa-solid fa-map-pin text-danger me-1"></i>

                                {{ $disaster->location }}

                            </span>

                        </div>

                    </div>


                    {{-- Start Date --}}

                    <div class="col-md-4 col-sm-6">

                        <div class="p-3 bg-light rounded border">

                            <small class="text-muted d-block text-uppercase font-weight-bold mb-1">

                                စတင်သည့်ရက်

                            </small>

                            <span class="h6 mb-0 text-dark fw-bold">

                                <i class="fa-solid fa-calendar-day text-primary me-1"></i>

                                {{ $disaster->start_date
                                    ? (is_string($disaster->start_date)
                                        ? $disaster->start_date
                                        : $disaster->start_date->format('d M, Y'))
                                    : '-' }}

                            </span>

                        </div>

                    </div>


                    {{-- End Date --}}

                    <div class="col-md-4 col-sm-6">

                        <div class="p-3 bg-light rounded border">

                            <small class="text-muted d-block text-uppercase font-weight-bold mb-1">

                                ပြီးဆုံးသည့်ရက်

                            </small>

                            <span class="h6 mb-0 text-dark fw-bold">

                                <i class="fa-solid fa-calendar-check text-success me-1"></i>

                                {{ $disaster->end_date
                                    ? (is_string($disaster->end_date)
                                        ? $disaster->end_date
                                        : $disaster->end_date->format('d M, Y'))
                                    : 'ဆက်လက်ဖြစ်ပွားနေသည်' }}

                            </span>

                        </div>

                    </div>


                    {{-- Created At --}}

                    <div class="col-md-4 col-sm-6">

                        <div class="p-3 bg-light rounded border">

                            <small class="text-muted d-block text-uppercase font-weight-bold mb-1">

                                မှတ်တမ်းတင်သည့်အချိန်

                            </small>

                            <span class="small text-secondary fw-bold">

                                {{ $disaster->created_at
                                    ? $disaster->created_at->format('d M, Y (h:i A)')
                                    : '-' }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================= --}}
        {{-- Footer Actions --}}
        {{-- ============================= --}}

        <div class="d-flex justify-content-between align-items-center">

            <a href="{{ route('backend.disasters.index') }}"
               class="btn btn-light border px-4">

                <i class="fa-solid fa-arrow-left me-1"></i>

                စာရင်းသို့ ပြန်သွားရန်

            </a>


            <div class="d-flex gap-2">

                <a href="{{ route('backend.disasters.edit', $disaster->id) }}"
                   class="btn btn-warning px-4 text-dark font-weight-bold">

                    <i class="fa-solid fa-pen me-1"></i>

                    ဖြစ်ရပ်ပြင်ဆင်ရန်

                </a>


                <button type="button"
                        class="btn btn-outline-danger px-3 delete-btn"
                        data-id="{{ $disaster->id }}"
                        data-name="{{ $disaster->name }}">

                    <i class="fa-solid fa-trash me-1"></i>

                    ဖျက်ရန်

                </button>

            </div>

        </div>

    </div>

</div>


{{-- ========================================== --}}
{{-- DELETE CONFIRMATION MODAL --}}
{{-- ========================================== --}}

<div class="modal fade"
     id="deleteModal"
     tabindex="-1"
     aria-labelledby="deleteModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">


            {{-- Modal Header --}}

            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title"
                    id="deleteModalLabel">

                    <i class="fa-solid fa-triangle-exclamation me-2"></i>

                    ဖျက်ရန် အတည်ပြုခြင်း

                </h5>


                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>


            {{-- Modal Body --}}

            <div class="modal-body p-4">

                <p class="mb-2">

                    ဤဘေးအန္တရာယ်ဖြစ်ရပ်

                    <strong id="deleteDisasterName"
                            class="text-danger">
                        ဤဖြစ်ရပ်
                    </strong>

                    ကို ဖျက်ရန် သေချာပါသလား။

                </p>


                <div class="alert alert-warning mb-0">

                    <i class="fa-solid fa-triangle-exclamation me-2"></i>

                    ဖျက်ပြီးပါက ဤမှတ်တမ်းကို
                    ပြန်လည်ရယူ၍ မရနိုင်ပါ။

                </div>

            </div>


            {{-- Modal Footer --}}

            <div class="modal-footer bg-light border-0">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    မလုပ်တော့ပါ

                </button>


                <form id="deleteForm"
                      action=""
                      method="POST"
                      class="d-inline">

                    @csrf
                    @method('DELETE')


                    <button type="submit"
                            class="btn btn-danger">

                        <i class="fa-solid fa-trash me-1"></i>

                        ဖြစ်ရပ်ဖျက်ရန်

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

    $('.delete-btn').on('click', function() {

        let id = $(this).data('id');

        let name = $(this).data('name');


        let deleteUrl =
            "{{ route('backend.disasters.destroy', ':id') }}"
            .replace(':id', id);


        $('#deleteForm').attr(
            'action',
            deleteUrl
        );


        $('#deleteDisasterName').text(
            name ? `"${name}"` : 'ဤဖြစ်ရပ်'
        );


        let deleteModal =
            new bootstrap.Modal(
                document.getElementById('deleteModal')
            );


        deleteModal.show();

    });

});

</script>

@endsection
