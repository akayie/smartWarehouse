@extends('layouts.admin')

@section('title', 'ဖြန့်ဝေမှုအသေးစိတ် #' . $distribution->id)

@section('button')

<a href="{{ route('backend.distributions.index') }}"
   class="btn btn-secondary">

    <i class="fa-solid fa-arrow-left me-1"></i>

    နောက်သို့

</a>

@endsection


@section('content')

<div class="container-fluid py-3">


    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold text-dark mb-1">

                <i class="fa-solid fa-truck-ramp-box me-2"></i>

                ဖြန့်ဝေမှုမှတ်တမ်း #{{ $distribution->id }}

            </h3>

            <p class="text-muted mb-0">

                ကယ်ဆယ်ရေးပစ္စည်းများ ဖြန့်ဝေပေးထားမှု အသေးစိတ်မှတ်တမ်း

            </p>

        </div>


        <div>

            <button
                onclick="window.print()"
                class="btn btn-outline-secondary me-2"
            >

                <i class="fa-solid fa-print me-1"></i>

                မှတ်တမ်းထုတ်ရန်

            </button>


            <a
                href="{{ route('backend.distributions.index') }}"
                class="btn btn-primary"
            >

                <i class="fa-solid fa-arrow-left me-1"></i>

                မှတ်တမ်းစာရင်းသို့

            </a>

        </div>

    </div>



    {{-- =====================================================
        DISTRIBUTION INFORMATION
    ====================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0 text-primary">

                <i class="fa-solid fa-circle-info me-2"></i>

                ဖြန့်ဝေမှုအချက်အလက်

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">


                {{-- Distribution ID --}}

                <div class="col-md-3">

                    <span class="text-muted d-block mb-1">
                        ဖြန့်ဝေမှုအမှတ်
                    </span>

                    <strong class="fs-6">
                        #{{ $distribution->id }}
                    </strong>

                </div>


                {{-- Date --}}

                <div class="col-md-3">

                    <span class="text-muted d-block mb-1">
                        ဖြန့်ဝေသည့်ရက်
                    </span>

                    <strong class="fs-6">

                        {{ $distribution->distribution_date
                            ? $distribution->distribution_date->format('d-m-Y')
                            : '-'
                        }}

                    </strong>

                </div>


                {{-- Warehouse --}}

                <div class="col-md-3">

                    <span class="text-muted d-block mb-1">
                        မူရင်းသိုလှောင်ရုံ
                    </span>

                    <strong class="fs-6 text-primary">

                        <i class="fa-solid fa-warehouse me-1"></i>

                        {{ $distribution->warehouse->name ?? 'မသတ်မှတ်ရသေးပါ' }}

                    </strong>

                </div>


                {{-- Handled By --}}

                <div class="col-md-3">

                    <span class="text-muted d-block mb-1">
                        ဆောင်ရွက်သူ
                    </span>

                    <strong class="fs-6">

                        <i class="fa-solid fa-user me-1 text-muted"></i>

                        {{ $distribution->handledBy->name ?? 'System' }}

                    </strong>

                </div>


                {{-- Relief Request --}}

                <div class="col-md-6">

                    <span class="text-muted d-block mb-1">
                        ကယ်ဆယ်ရေးတောင်းခံမှု
                    </span>


                    @if($distribution->request)

                        <strong class="fs-6">

                            <span class="badge bg-primary">

                                Request #{{ $distribution->request->id }}

                            </span>


                            <span class="ms-2">

                                {{ $distribution->request->location ?? '-' }}

                            </span>

                        </strong>

                    @else

                        <span class="badge bg-secondary">

                            တိုက်ရိုက်ဖြန့်ဝေမှု

                        </span>

                    @endif

                </div>


                {{-- Status --}}

                <div class="col-md-3">

                    <span class="text-muted d-block mb-1">
                        Status
                    </span>


                    @php

                        $statusClass = match(
                            strtolower($distribution->status ?? '')
                        ) {

                            'completed' => 'success',

                            'distributed' => 'primary',

                            'approved' => 'info',

                            'pending' => 'warning',

                            'cancelled' => 'danger',

                            default => 'secondary',

                        };

                    @endphp


                    <span class="badge bg-{{ $statusClass }} fs-6">

                        {{ ucfirst($distribution->status ?? '-') }}

                    </span>

                </div>


                {{-- Total Item Types --}}

                <div class="col-md-3">

                    <span class="text-muted d-block mb-1">

                        ဖြန့်ဝေသည့်ပစ္စည်းအမျိုးအစား

                    </span>


                    <strong class="fs-6">

                        {{ $distribution->distributionItems->count() }}

                        မျိုး

                    </strong>

                </div>


                {{-- Total Quantity --}}

                <div class="col-md-6">

                    <span class="text-muted d-block mb-1">

                        စုစုပေါင်းဖြန့်ဝေသည့်ပမာဏ

                    </span>


                    <strong class="fs-5 text-danger">

                        {{ number_format(
                            $distribution
                                ->distributionItems
                                ->sum('quantity')
                        ) }}

                    </strong>

                </div>


                {{-- =================================================
                    DONATION FUNDING USED
                ================================================== --}}

                <div class="col-md-6">

                    <div class="border rounded p-3 bg-light">

                        <span class="text-muted d-block mb-1">

                            <i class="fa-solid fa-hand-holding-dollar me-1"></i>

                            Donation Funding Used

                        </span>


                        <strong class="fs-4 text-success">

                            {{ number_format(
                                (float) ($distribution->funding_amount ?? 0),
                                2
                            ) }}

                        </strong>


                        <small class="text-muted d-block mt-1">

                            ဤ Distribution အတွက် အသုံးပြုထားသော Donation Funding

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =====================================================
        DISTRIBUTED ITEMS
    ====================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0 text-primary">

                <i class="fa-solid fa-boxes-stacked me-2"></i>

                ဖြန့်ဝေထားသော ပစ္စည်းအသေးစိတ်

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th
                                class="ps-3"
                                style="width:60px;"
                            >
                                စဉ်
                            </th>

                            <th>
                                Barcode
                            </th>

                            <th>
                                ပစ္စည်းအမည်
                            </th>

                            <th class="text-end">
                                ဖြန့်ဝေသည့်ပမာဏ
                            </th>

                            <th>
                                ယူနစ်
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $distribution->distributionItems
                            as $index => $detail
                        )

                            <tr>

                                <td class="ps-3">

                                    {{ $index + 1 }}

                                </td>


                                <td>

                                    @if($detail->item?->barcode)

                                        <code>

                                            {{ $detail->item->barcode }}

                                        </code>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <strong>

                                        {{ $detail->item->name
                                            ?? 'ပစ္စည်းမတွေ့ပါ'
                                        }}

                                    </strong>

                                </td>


                                <td class="text-end">

                                    <span class="badge bg-danger fs-6">

                                        -
                                        {{ number_format(
                                            $detail->quantity
                                        ) }}

                                    </span>

                                </td>


                                <td>

                                    {{ $detail->item->unit
                                        ?? 'ယူနစ်'
                                    }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-5 text-muted"
                                >

                                    <i
                                        class="fa-solid fa-box-open fa-2x mb-2 d-block"
                                    ></i>

                                    <div class="fw-semibold">

                                        ဖြန့်ဝေထားသော ပစ္စည်းမှတ်တမ်း မရှိသေးပါ။

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    {{-- Total Quantity --}}

                    @if(
                        $distribution
                            ->distributionItems
                            ->count() > 0
                    )

                        <tfoot class="table-light">

                            <tr>

                                <th
                                    colspan="3"
                                    class="text-end"
                                >

                                    စုစုပေါင်း

                                </th>


                                <th class="text-end">

                                    <span class="badge bg-danger fs-6">

                                        -
                                        {{ number_format(
                                            $distribution
                                                ->distributionItems
                                                ->sum('quantity')
                                        ) }}

                                    </span>

                                </th>


                                <th></th>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>



    {{-- =====================================================
        FUNDING SUMMARY
    ====================================================== --}}

    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header bg-white">

            <h5 class="fw-bold mb-0 text-success">

                <i class="fa-solid fa-money-bill-transfer me-2"></i>

                Donation Funding အသုံးပြုမှု

            </h5>

        </div>


        <div class="card-body">

            <div class="row">


                <div class="col-md-6">

                    <div class="p-3 border rounded bg-light">

                        <small class="text-muted d-block">

                            ဤ Distribution အတွက် အသုံးပြုငွေ

                        </small>

                        <strong class="fs-3 text-success">

                            {{ number_format(
                                (float) (
                                    $distribution->funding_amount ?? 0
                                ),
                                2
                            ) }}

                        </strong>

                    </div>

                </div>


                <div class="col-md-6">

                    <div class="p-3 border rounded bg-light">

                        <small class="text-muted d-block">

                            Distribution Reference

                        </small>

                        <strong class="fs-5">

                            DIST-{{ $distribution->id }}

                        </strong>

                        <small class="text-muted d-block mt-1">

                            Donation Funding ကို ဤ Distribution မှာ အသုံးပြုထားသည်။

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =====================================================
        NOTE
    ====================================================== --}}

    @if($distribution->note)

        <div class="card shadow-sm border-0 mt-4">

            <div class="card-header bg-white">

                <h5 class="fw-bold mb-0">

                    <i class="fa-solid fa-note-sticky me-2"></i>

                    မှတ်ချက်

                </h5>

            </div>


            <div class="card-body">

                {{ $distribution->note }}

            </div>

        </div>

    @endif



    {{-- =====================================================
        FOOTER ACTIONS
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-center mt-4">

        <a
            href="{{ route('backend.distributions.index') }}"
            class="btn btn-secondary"
        >

            <i class="fa-solid fa-arrow-left me-1"></i>

            ဖြန့်ဝေမှုစာရင်းသို့ ပြန်သွားရန်

        </a>


        <button
            onclick="window.print()"
            class="btn btn-outline-primary"
        >

            <i class="fa-solid fa-print me-1"></i>

            ဖြန့်ဝေမှုမှတ်တမ်း ထုတ်ရန်

        </button>

    </div>

</div>



{{-- =====================================================
    PRINT CSS
====================================================== --}}

<style>

@media print {

    body {
        background: white !important;
    }

    .btn,
    nav,
    aside,
    .sidebar,
    header {
        display: none !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    .container-fluid {
        width: 100% !important;
        padding: 0 !important;
    }

}

</style>

@endsection
{{-- FUNDING USED --}}

<div class="col-md-3">

    <span class="text-muted d-block mb-1">

        ဤ Distribution အတွက် အသုံးပြုငွေ

    </span>

    <strong class="fs-5 text-success">

        {{ number_format(
            $distribution->funding_amount ?? 0,
            2
        ) }}

        {{ config('app.currency', 'MMK') }}

    </strong>

    <small class="text-muted d-block mt-1">

        Donation Funding မှ နုတ်ယူထားသည်

    </small>

</div>
