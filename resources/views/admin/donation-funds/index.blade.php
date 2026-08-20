@extends('layouts.admin')

@section('title', 'Donation Fund Management')

@section('content')

<div class="container-fluid py-4">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fa-solid fa-hand-holding-dollar me-2 text-success"></i>

                Donation Fund Management

            </h3>

            <p class="text-muted mb-0">

                Donation Payment မှ ရရှိသော ငွေကြေးနှင့်
                Distribution တွင် အသုံးပြုထားသော Funding မှတ်တမ်း

            </p>

        </div>

    </div>


    {{-- =====================================================
        SUMMARY CARDS
    ====================================================== --}}

    <div class="row g-4 mb-4">

        {{-- TOTAL DONATION --}}

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Donation Fund
                            </small>

                            <h3 class="fw-bold text-primary mt-2">

                                {{ number_format(
                                    $totalDonationAmount,
                                    2
                                ) }}

                            </h3>

                            <small>
                                {{ config('app.currency', 'MMK') }}
                            </small>

                        </div>

                        <div>

                            <i class="fa-solid fa-coins fa-2x text-primary"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- USED FUNDING --}}

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Used Funding
                            </small>

                            <h3 class="fw-bold text-danger mt-2">

                                {{ number_format(
                                    $usedFundingAmount,
                                    2
                                ) }}

                            </h3>

                            <small>
                                {{ config('app.currency', 'MMK') }}
                            </small>

                        </div>

                        <div>

                            <i class="fa-solid fa-money-bill-transfer fa-2x text-danger"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- REMAINING --}}

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Remaining Fund
                            </small>

                            <h3 class="fw-bold text-success mt-2">

                                {{ number_format(
                                    $remainingFundingAmount,
                                    2
                                ) }}

                            </h3>

                            <small>
                                {{ config('app.currency', 'MMK') }}
                            </small>

                        </div>

                        <div>

                            <i class="fa-solid fa-wallet fa-2x text-success"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        SEARCH
    ====================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('backend.donation-funds') }}">

                <div class="row g-3">

                    <div class="col-md-10">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Donation / Payment Search..."
                        >

                    </div>

                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="fa-solid fa-search me-1"></i>

                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
        DONATION PAYMENT RECORDS
    ====================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0 text-primary">

                <i class="fa-solid fa-money-check-dollar me-2"></i>

                Donation Payment Records

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Donation</th>

                            <th>Donor</th>

                            <th>Payment Method</th>

                            <th class="text-end">
                                Payment Amount
                            </th>

                            <th>Status</th>

                            <th>Date</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($payments as $payment)

                            <tr>

                                <td>

                                    {{ $payments->firstItem() + $loop->index }}

                                </td>

                                <td>

                                    <strong>

                                        {{ $payment->donation->donation_no
                                            ?? '#' . ($payment->donation_id ?? $payment->id)
                                        }}

                                    </strong>

                                </td>

                                <td>

                                    {{ $payment->donation->donor->name
                                        ?? 'Anonymous'
                                    }}

                                </td>

                                <td>

                                    {{ $payment->payment_method ?? '-' }}

                                </td>

                                <td class="text-end">

                                    <strong class="text-success">

                                        {{ number_format(
                                            $payment->amount,
                                            2
                                        ) }}

                                    </strong>

                                    {{ config('app.currency', 'MMK') }}

                                </td>

                                <td>

                                    <span class="badge bg-success">

                                        Completed

                                    </span>

                                </td>

                                <td>

                                    {{ $payment->created_at
                                        ? $payment->created_at->format('d-m-Y')
                                        : '-'
                                    }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5 text-muted">

                                    Donation Payment မရှိပါ။

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =====================================================
        DISTRIBUTION FUNDING RECORDS
    ====================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <h5 class="fw-bold mb-0 text-danger">

                <i class="fa-solid fa-money-bill-transfer me-2"></i>

                Distribution Funding Records

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Distribution</th>

                            <th>Request</th>

                            <th>Warehouse</th>

                            <th>Handled By</th>

                            <th>Date</th>

                            <th class="text-end">
                                Used Funding
                            </th>

                            <th class="text-end">
                                Balance After
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @php
                            $runningBalance = $totalDonationAmount;
                        @endphp


                        @forelse($distributionFundings as $distribution)

                            @php

                                /*
                                |--------------------------------------------------------------------------
                                | Calculate Balance
                                |--------------------------------------------------------------------------
                                */

                                $used = (float) (
                                    $distribution->funding_amount ?? 0
                                );

                                $balanceAfter =
                                    $runningBalance - $used;

                                $runningBalance =
                                    $balanceAfter;

                            @endphp


                            <tr>

                                <td>

                                    {{ $distributionFundings->firstItem() + $loop->index }}

                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'backend.distributions.show',
                                            $distribution->id
                                        ) }}"
                                        class="fw-bold text-decoration-none"
                                    >

                                        Distribution #{{ $distribution->id }}

                                    </a>

                                </td>


                                <td>

                                    @if($distribution->reliefRequest)

                                        <span class="badge bg-primary">

                                            Request #{{ $distribution->reliefRequest->id }}

                                        </span>

                                        <br>

                                        <small class="text-muted">

                                            {{ $distribution->reliefRequest->location }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Direct Distribution
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ $distribution->warehouse->name ?? '-' }}

                                </td>


                                <td>

                                    {{ $distribution->handledBy->name ?? 'System' }}

                                </td>


                                <td>

                                    {{ $distribution->distribution_date
                                        ? $distribution->distribution_date->format('d-m-Y')
                                        : '-'
                                    }}

                                </td>


                                <td class="text-end">

                                    <strong class="text-danger">

                                        - {{ number_format(
                                            $used,
                                            2
                                        ) }}

                                    </strong>

                                    <small>
                                        {{ config('app.currency', 'MMK') }}
                                    </small>

                                </td>


                                <td class="text-end">

                                    <strong
                                        class="{{ $balanceAfter > 0
                                            ? 'text-success'
                                            : 'text-danger'
                                        }}"
                                    >

                                        {{ number_format(
                                            max(0, $balanceAfter),
                                            2
                                        ) }}

                                    </strong>

                                    <small>
                                        {{ config('app.currency', 'MMK') }}
                                    </small>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center py-5 text-muted">

                                    Distribution Funding မရှိပါ။

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    <tfoot class="table-light">

                        <tr>

                            <th colspan="6"
                                class="text-end">

                                စုစုပေါင်း အသုံးပြုငွေ

                            </th>

                            <th class="text-end text-danger">

                                -

                                {{ number_format(
                                    $usedFundingAmount,
                                    2
                                ) }}

                                {{ config('app.currency', 'MMK') }}

                            </th>

                            <th class="text-end text-success">

                                {{ number_format(
                                    $remainingFundingAmount,
                                    2
                                ) }}

                                {{ config('app.currency', 'MMK') }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>


    {{-- =====================================================
        PAGINATION
    ====================================================== --}}

    <div class="mt-3">

        {{ $distributionFundings->links() }}

    </div>

</div>

@endsection
