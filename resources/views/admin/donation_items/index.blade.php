@extends('layouts.admin')

@section('title', 'အလှူပစ္စည်းများ')

@section('button')
<a href="{{ route('backend.donation_items.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> အလှူပစ္စည်းထည့်ရန်
</a>
@endsection

@section('content')

<div class="card shadow-sm border-0">

    {{-- Header --}}
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

        <h5 class="mb-0 fw-bold text-dark">
            <i class="fas fa-box-open me-2 text-primary"></i>
            အလှူပစ္စည်းစာရင်း
        </h5>

    </div>

    <div class="card-body">

        {{-- Success Message --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <i class="fas fa-check-circle me-1"></i>
                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                </button>

            </div>

        @endif

        {{-- Error Message --}}
        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <i class="fas fa-exclamation-circle me-1"></i>
                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                </button>

            </div>

        @endif

        {{-- Search Filter Form --}}
        <form
            method="GET"
            action="{{ route('backend.donation_items.index') }}"
            class="row g-3 mb-4">

            <div class="col-md-5">

                <label class="form-label fw-semibold">
                    ရှာဖွေရန်
                </label>

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="အလှူရှင်၊ ပစ္စည်းအမည် သို့မဟုတ် အလှူမှတ်တမ်းအမှတ်ဖြင့် ရှာဖွေပါ..."
                    value="{{ request('search') }}">

            </div>

            <div class="col-md-2 d-flex align-items-end">

                <button
                    type="submit"
                    class="btn btn-secondary w-100">

                    <i class="fas fa-search me-1"></i>
                    ရှာဖွေရန်

                </button>

            </div>

            @if(request('search'))

                <div class="col-md-2 d-flex align-items-end">

                    <a
                        href="{{ route('backend.donation_items.index') }}"
                        class="btn btn-outline-danger w-100">

                        <i class="fas fa-times me-1"></i>
                        ရှင်းလင်းရန်

                    </a>

                </div>

            @endif

        </form>

        {{-- Donation Items Table --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th style="width: 50px;">
                            #
                        </th>

                        <th>
                            အလှူမှတ်တမ်း
                        </th>

                        <th>
                            အလှူရှင်
                        </th>

                        <th>
                            ပစ္စည်းအမည်
                        </th>

                        <th>
                            ပမာဏ
                        </th>

                        <th
                            style="width: 220px;"
                            class="text-center">

                            လုပ်ဆောင်ချက်

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($donationItems as $donationItem)

                        <tr>

                            {{-- Number --}}
                            <td>

                                {{ $loop->iteration
                                    + ($donationItems->currentPage() - 1)
                                    * $donationItems->perPage()
                                }}

                            </td>

                            {{-- Donation ID --}}
                            <td>

                                <span class="badge bg-light text-dark border">

                                    အလှူ #{{ $donationItem->donation_id }}

                                </span>

                            </td>

                            {{-- Donor --}}
                            <td>

                                <span class="fw-semibold">

                                    {{ $donationItem
                                        ->donation
                                        ->donor
                                        ->name
                                        ?? 'မသိရှိပါ'
                                    }}

                                </span>

                            </td>

                            {{-- Item --}}
                            <td>

                                <span class="fw-semibold text-dark">

                                    {{ $donationItem
                                        ->item
                                        ->name
                                        ?? 'ပစ္စည်းအမည် မရှိပါ'
                                    }}

                                </span>

                            </td>

                            {{-- Quantity --}}
                            <td>

                                <span class="badge bg-info text-dark">

                                    {{ number_format($donationItem->quantity) }}

                                    {{ $donationItem->item?->unit ?? '' }}

                                </span>

                            </td>

                            {{-- Actions --}}
                            <td class="text-center">

                                <div
                                    class="btn-group btn-group-sm"
                                    role="group">

                                    {{-- View --}}
                                    <a
                                        href="{{ route(
                                            'backend.donation_items.show',
                                            $donationItem->id
                                        ) }}"
                                        class="btn btn-outline-info"
                                        title="အသေးစိတ်ကြည့်ရန်">

                                        <i class="fas fa-eye me-1"></i>
                                        ကြည့်ရန်

                                    </a>

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route(
                                            'backend.donation_items.edit',
                                            $donationItem->id
                                        ) }}"
                                        class="btn btn-outline-warning"
                                        title="ပြင်ဆင်ရန်">

                                        <i class="fas fa-edit me-1"></i>
                                        ပြင်ရန်

                                    </a>

                                    {{-- Delete --}}
                                    <form
                                        action="{{ route(
                                            'backend.donation_items.destroy',
                                            $donationItem->id
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            'ဤအလှူပစ္စည်းမှတ်တမ်းကို ဖျက်ရန် သေချာပါသလား?'
                                        )">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger"
                                            title="ဖျက်ရန်">

                                            <i class="fas fa-trash me-1"></i>
                                            ဖျက်ရန်

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5 text-muted">

                                <i
                                    class="fas fa-box-open fa-3x mb-3 d-block text-secondary">
                                </i>

                                <h6 class="fw-bold">
                                    အလှူပစ္စည်းမှတ်တမ်း မတွေ့ရှိပါ။
                                </h6>

                                <p class="mb-0 small">
                                    အလှူပစ္စည်းမှတ်တမ်းများ မရှိသေးပါ။
                                    "အလှူပစ္စည်းထည့်ရန်" ကိုနှိပ်၍
                                    မှတ်တမ်းအသစ်ထည့်သွင်းနိုင်ပါသည်။
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($donationItems->hasPages())

            <div class="mt-4 d-flex justify-content-end">

                {{ $donationItems->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
