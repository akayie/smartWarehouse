@extends('layouts.admin')

@section('title', 'ဘေးအန္တရာယ်ဖြစ်ရပ်များ')

@section('button')
<a href="{{ route('backend.disasters.create') }}" class="btn btn-sm btn-danger shadow-sm">
    <i class="fa-solid fa-plus me-1"></i> ဖြစ်ရပ်အသစ်ထည့်ရန်
</a>
@endsection

@section('content')
<div id="adm-events" class="container-fluid px-0">

    {{-- အောင်မြင်ကြောင်း အသိပေးစာ --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3 shadow-sm border-0" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endif

    {{-- အဓိက ဘေးအန္တရာယ်ဖြစ်ရပ်စာရင်း --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

            <h5 class="m-0 font-weight-bold text-dark">
                <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>
                ဘေးအန္တရာယ်ဖြစ်ရပ်များနှင့် အရေးပေါ်အခြေအနေများ
            </h5>

            <a href="{{ route('backend.disasters.create') }}"
               class="btn btn-sm btn-danger">

                <i class="fa-solid fa-plus me-1"></i>
                ဖြစ်ရပ်အသစ်ထည့်ရန်

            </a>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="data-table table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 50px;" class="text-center">
                                #
                            </th>

                            <th>
                                ဖြစ်ရပ်အမည်
                            </th>

                            <th>
                                ဘေးအန္တရာယ်အမျိုးအစား
                            </th>

                            <th>
                                ဖြစ်ပွားရာဒေသ
                            </th>

                            <th>
                                စတင်သည့်ရက်
                            </th>

                            <th>
                                ပြီးဆုံးသည့်ရက်
                            </th>

                            <th class="text-center">
                                ကယ်ဆယ်ရေးတောင်းဆိုမှုများ
                            </th>

                            <th>
                                အခြေအနေ
                            </th>

                            <th class="text-center" style="width: 170px;">
                                လုပ်ဆောင်ချက်
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($disasters as $key => $disaster)

                            <tr>

                                {{-- Number --}}
                                <td class="text-center text-muted fw-bold">

                                    {{ method_exists($disasters, 'firstItem') && $disasters->firstItem()
                                        ? $disasters->firstItem() + $key
                                        : $loop->iteration }}

                                </td>

                                {{-- Event Name --}}
                                <td>

                                    <strong class="text-dark">
                                        {{ $disaster->name }}
                                    </strong>

                                </td>

                                {{-- Type --}}
                                <td>

                                    <span class="badge bg-light text-dark border">

                                        {{ $disaster->type ?? 'မသတ်မှတ်ရသေးပါ' }}

                                    </span>

                                </td>

                                {{-- Location --}}
                                <td>

                                    <i class="fa-solid fa-location-dot text-danger me-1"></i>

                                    {{ $disaster->location }}

                                </td>

                                {{-- Start Date --}}
                                <td>

                                    <small class="text-secondary fw-semibold">

                                        {{ $disaster->start_date
                                            ? $disaster->start_date->format('d-m-Y')
                                            : '-' }}

                                    </small>

                                </td>

                                {{-- End Date --}}
                                <td>

                                    <small class="text-secondary fw-semibold">

                                        {{ $disaster->end_date
                                            ? $disaster->end_date->format('d-m-Y')
                                            : '-' }}

                                    </small>

                                </td>

                                {{-- Relief Requests --}}
                                <td class="text-center">

                                    <span class="badge bg-info text-dark rounded-pill px-3 py-2">

                                        <i class="fa-solid fa-hand-holding-hand me-1"></i>

                                        {{ $disaster->relief_requests_count
                                            ?? ($disaster->reliefRequests
                                            ? $disaster->reliefRequests->count()
                                            : 0) }}

                                    </span>

                                </td>

                                {{-- Status --}}
                                <td>

                                    @php
                                        $status = strtolower($disaster->status ?? '');
                                    @endphp

                                    @if($status === 'active')

                                        <span class="badge bg-success">

                                            <i class="fa-solid fa-circle-dot me-1"></i>

                                            လက်ရှိဖြစ်ပွားနေ

                                        </span>

                                    @elseif($status === 'completed')

                                        <span class="badge bg-primary">

                                            <i class="fa-solid fa-circle-check me-1"></i>

                                            ပြီးဆုံးပြီ

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            <i class="fa-solid fa-circle-xmark me-1"></i>

                                            {{ $disaster->status ?? 'ပယ်ဖျက်ထားသည်' }}

                                        </span>

                                    @endif

                                </td>

                                {{-- Actions --}}
                                <td class="text-center">

                                    <div class="btn-group btn-group-sm"
                                         role="group">

                                        {{-- View --}}
                                        <a href="{{ route('backend.disasters.show', $disaster->id) }}"
                                           class="btn btn-outline-info"
                                           title="အသေးစိတ်ကြည့်ရန်">

                                            <i class="fa-solid fa-eye"></i>
                                            ကြည့်ရန်

                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('backend.disasters.edit', $disaster->id) }}"
                                           class="btn btn-outline-warning text-dark"
                                           title="ဘေးအန္တရာယ်ဖြစ်ရပ် ပြင်ဆင်ရန်">

                                            <i class="fa-solid fa-pen"></i>
                                            ပြင်ဆင်ရန်

                                        </a>

                                        {{-- Delete --}}
                                        <button type="button"
                                                class="btn btn-outline-danger delete-btn"
                                                data-id="{{ $disaster->id }}"
                                                data-name="{{ $disaster->name }}"
                                                title="ဖြစ်ရပ်ဖျက်ရန်">

                                            <i class="fa-solid fa-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5 text-muted">

                                    <i class="fa-solid fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>

                                    <p class="mb-0 fw-semibold">
                                        ဘေးအန္တရာယ်ဖြစ်ရပ် မှတ်တမ်းမတွေ့ရှိပါ။
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Pagination --}}
        @if(method_exists($disasters, 'hasPages') && $disasters->hasPages())

            <div class="card-footer bg-white d-flex justify-content-end py-3">

                {{ $disasters->links('pagination::bootstrap-5') }}

            </div>

        @endif

    </div>

</div>


{{-- DELETE CONFIRMATION MODAL --}}
<div class="modal fade"
     id="deleteModal"
     tabindex="-1"
     aria-labelledby="deleteModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

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

            <div class="modal-body p-4">

                ဤဘေးအန္တရာယ်ဖြစ်ရပ်

                <strong id="deleteDisasterName"
                        class="text-danger">
                    ဤဖြစ်ရပ်
                </strong>

                ကို ဖျက်ရန် သေချာပါသလား။

                <br>

                <small class="text-muted">
                    ဖျက်ပြီးပါက ဤမှတ်တမ်းကို ပြန်လည်ရယူ၍ မရနိုင်ပါ။
                </small>

            </div>

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

document.addEventListener('DOMContentLoaded', function() {

    const deleteButtons =
        document.querySelectorAll('.delete-btn');

    const deleteForm =
        document.getElementById('deleteForm');

    const deleteNameSpan =
        document.getElementById('deleteDisasterName');


    deleteButtons.forEach(button => {

        button.addEventListener('click', function() {

            let id =
                this.getAttribute('data-id');

            let name =
                this.getAttribute('data-name');


            let deleteUrl =
                "{{ route('backend.disasters.destroy', ':id') }}"
                .replace(':id', id);


            deleteForm.setAttribute(
                'action',
                deleteUrl
            );


            deleteNameSpan.textContent =
                name ? `"${name}"` : 'ဤဖြစ်ရပ်';


            let modalElement =
                document.getElementById('deleteModal');


            let deleteModal =
                new bootstrap.Modal(modalElement);


            deleteModal.show();

        });

    });

});

</script>

@endsection
