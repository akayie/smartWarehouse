```blade
@extends('layouts.admin')

@section('title', 'လှူဒါန်းမှုအသေးစိတ် #' . $donationPayment->id)

@section('button')

<a href="{{ route('backend.donation_payments.index') }}"
   class="btn btn-secondary btn-sm">

    <i class="fas fa-arrow-left me-1"></i>

    လှူဒါန်းမှုစာရင်းသို့ ပြန်သွားရန်

</a>

@if(Route::has('backend.donation_payments.pdf'))

<a href="{{ route('backend.donation_payments.pdf', $donationPayment->id) }}"
   target="_blank"
   class="btn btn-danger btn-sm ms-2">

    <i class="fas fa-file-pdf me-1"></i>

    PDF

</a>

@endif

@endsection


@section('content')

{{-- =========================================================
    SUCCESS MESSAGE
========================================================= --}}

@if(session('success'))

<div class="alert alert-success alert-dismissible fade show mb-4">

    <i class="fas fa-check-circle me-1"></i>

    {{ session('success') }}

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

@endif


{{-- =========================================================
    ERROR MESSAGE
========================================================= --}}

@if(session('error'))

<div class="alert alert-danger alert-dismissible fade show mb-4">

    <i class="fas fa-exclamation-circle me-1"></i>

    {{ session('error') }}

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

@endif


<div class="row">

    {{-- =====================================================
        LEFT SIDE
        DONATION + DONOR + PAYMENT
    ====================================================== --}}

    <div class="col-lg-4 mb-4">

        {{-- Donation Information --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0 fw-bold">

                    <i class="fas fa-hand-holding-heart me-2"></i>

                    လှူဒါန်းမှုအသေးစိတ်

                </h5>

            </div>


            <div class="card-body">


                {{-- Payment ID --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        ငွေပေးချေမှုအမှတ်
                    </small>

                    <strong>

                        #DPY-{{ str_pad(
                            $donationPayment->id,
                            4,
                            '0',
                            STR_PAD_LEFT
                        ) }}

                    </strong>

                </div>


                {{-- =================================================
                    DONOR INFORMATION
                ================================================== --}}

                <hr>

                <h6 class="fw-bold text-primary mb-3">

                    <i class="fas fa-user me-2"></i>

                    အလှူရှင်အချက်အလက်

                </h6>


                {{-- Donor Name --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        အလှူရှင်အမည်
                    </small>

                    <strong>

                        {{ $donor?->name ?? 'စေတနာရှင် မသိရှိပါ' }}

                    </strong>

                </div>


                {{-- Donor Phone --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        ဖုန်းနံပါတ်
                    </small>

                    @if($donor?->phone)

                        <a href="tel:{{ $donor->phone }}"
                           class="text-decoration-none">

                            <i class="fas fa-phone me-1"></i>

                            {{ $donor->phone }}

                        </a>

                    @else

                        <span class="text-muted">-</span>

                    @endif

                </div>


                {{-- Donor Email --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        အီးမေးလ်
                    </small>

                    @if($donor?->email)

                        <a href="mailto:{{ $donor->email }}"
                           class="text-decoration-none">

                            <i class="fas fa-envelope me-1"></i>

                            {{ $donor->email }}

                        </a>

                    @else

                        <span class="text-muted">-</span>

                    @endif

                </div>


                {{-- Donor Address --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        လိပ်စာ
                    </small>

                    <span>

                        {{ $donor?->address ?? '-' }}

                    </span>

                </div>


                {{-- =================================================
                    WAREHOUSE
                ================================================== --}}

                <hr>

                <div class="mb-3">

                    <small class="text-muted d-block">
                        ဂိုဒေါင်
                    </small>

                    <strong>

                        {{ $warehouse?->name ?? '-' }}

                    </strong>

                </div>


            </div>

        </div>


        {{-- =====================================================
            PAYMENT INFORMATION
        ====================================================== --}}

        <div class="card shadow-sm border-0">

            <div class="card-header bg-success text-white">

                <h5 class="mb-0 fw-bold">

                    <i class="fas fa-money-bill-wave me-2"></i>

                    ငွေပေးချေမှုအချက်အလက်

                </h5>

            </div>


            <div class="card-body">


                {{-- Payment Method --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        ငွေပေးချေမှုနည်းလမ်း
                    </small>

                    <strong>

                        {{ $donationPayment->payment_method ?? '-' }}

                    </strong>

                </div>


                {{-- Transaction Reference --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        Transaction Reference
                    </small>

                    <strong>

                        {{ $donationPayment->transaction_reference ?? '-' }}

                    </strong>

                </div>


                {{-- Payment Date --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        ငွေပေးချေသည့်နေ့
                    </small>

                    <strong>

                        {{ $donationPayment->payment_date?->format('d-m-Y') ?? '-' }}

                    </strong>

                </div>


                {{-- Amount --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        ငွေပမာဏ
                    </small>

                    <h5 class="fw-bold text-success mb-0">

                        {{ number_format(
                            (float) $donationPayment->amount,
                            2
                        ) }}

                        {{ $donationPayment->currency ?? 'MMK' }}

                    </h5>

                </div>


                {{-- Account Name --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        ငွေလွှဲအကောင့်အမည်
                    </small>

                    <span>

                        {{ $donationPayment->account_name ?? '-' }}

                    </span>

                </div>


                {{-- Account Number --}}

                <div class="mb-3">

                    <small class="text-muted d-block">
                        ငွေလွှဲအကောင့်နံပါတ်
                    </small>

                    <span>

                        {{ $donationPayment->account_number ?? '-' }}

                    </span>

                </div>


                {{-- Status --}}

                <div class="mb-3">

                    <small class="text-muted d-block mb-1">
                        အခြေအနေ
                    </small>


                    @if($donationPayment->status === 'Pending')

                        <span class="badge bg-warning text-dark">

                            <i class="fas fa-clock me-1"></i>

                            စောင့်ဆိုင်းနေသည်

                        </span>

                    @elseif($donationPayment->status === 'Completed')

                        <span class="badge bg-success">

                            <i class="fas fa-check-circle me-1"></i>

                            ပြီးမြောက်သည်

                        </span>

                    @elseif($donationPayment->status === 'Cancelled')

                        <span class="badge bg-danger">

                            <i class="fas fa-times-circle me-1"></i>

                            ပယ်ဖျက်ထားသည်

                        </span>

                    @else

                        <span class="badge bg-secondary">

                            {{ $donationPayment->status ?? '-' }}

                        </span>

                    @endif

                </div>


                {{-- Note --}}

                <div class="mb-0">

                    <small class="text-muted d-block">
                        မှတ်ချက်
                    </small>

                    <span>

                        {{ $donationPayment->note ?? '-' }}

                    </span>

                </div>


            </div>

        </div>

    </div>


    {{-- =====================================================
        RIGHT SIDE
        PAYMENT PROOF + DONATED ITEMS
    ====================================================== --}}

    <div class="col-lg-8 mb-4">


        {{-- =================================================
            PAYMENT PROOF
        ================================================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0 fw-bold">

                    <i class="fas fa-receipt text-primary me-2"></i>

                    ငွေလွှဲအထောက်အထား

                </h5>

            </div>


            <div class="card-body text-center">


                @if($donationPayment->proof)

                    <a href="{{ asset(
                        'storage/' . $donationPayment->proof
                    ) }}"
                       target="_blank"
                       class="d-inline-block">

                        <img
                            src="{{ asset(
                                'storage/' . $donationPayment->proof
                            ) }}"
                            alt="Payment Proof"
                            class="img-fluid rounded border shadow-sm"
                            style="
                                max-height: 450px;
                                max-width: 100%;
                                object-fit: contain;
                            "
                        >

                    </a>


                    <div class="mt-3">

                        <a href="{{ asset(
                            'storage/' . $donationPayment->proof
                        ) }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm">

                            <i class="fas fa-external-link-alt me-1"></i>

                            အထောက်အထားကို အပြည့်အစုံကြည့်ရန်

                        </a>

                    </div>

                @else

                    <div class="py-5 text-muted">

                        <i class="fas fa-file-image fa-3x mb-3"></i>

                        <p class="mb-0">

                            ငွေလွှဲအထောက်အထား မရှိသေးပါ။

                        </p>

                    </div>

                @endif


            </div>

        </div>


        {{-- =================================================
            DONATED ITEMS
        ================================================== --}}

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0 fw-bold">

                    <i class="fas fa-boxes text-primary me-2"></i>

                    လှူဒါန်းထားသော ပစ္စည်းများ

                </h5>

            </div>


            <div class="card-body">

                @if($donatedItems->count())

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th style="width:60px;">
                                        #
                                    </th>

                                    <th>
                                        ပစ္စည်းအမည်
                                    </th>

                                    <th class="text-end">
                                        အရေအတွက်
                                    </th>

                                    <th>
                                        ယူနစ်
                                    </th>

                                    <th>
                                        ဂိုဒေါင်
                                    </th>

                                    <th>
                                        အခြေအနေ
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($donatedItems as $index => $donationItem)

                                    <tr>

                                        {{-- Number --}}

                                        <td>

                                            {{ $index + 1 }}

                                        </td>


                                        {{-- Item Name --}}

                                        <td>

                                            <strong>

                                                {{ $donationItem->item?->name ?? '-' }}

                                            </strong>

                                        </td>


                                        {{-- Quantity --}}

                                        <td class="text-end">

                                            {{ number_format(
                                                (float) ($donationItem->quantity ?? 0)
                                            ) }}

                                        </td>


                                        {{-- Unit --}}

                                        <td>

                                            {{ $donationItem->item?->unit ?? '-' }}

                                        </td>


                                        {{-- Warehouse --}}

                                        <td>

                                            {{ $warehouse?->name ?? '-' }}

                                        </td>


                                        {{-- Status --}}

                                        <td>

                                            <span class="badge bg-success">

                                                <i class="fas fa-check me-1"></i>

                                                လက်ခံရရှိပြီး

                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5 text-muted">

                        <i class="fas fa-box-open fa-3x mb-3 d-block"></i>

                        လှူဒါန်းထားသော ပစ္စည်းစာရင်း မရှိသေးပါ။

                    </div>

                @endif

            </div>

        </div>


    </div>

</div>

@endsection
