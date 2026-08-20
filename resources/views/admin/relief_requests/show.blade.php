@extends('layouts.admin')

@section('title', 'ကယ်ဆယ်ရေး အကူအညီ တောင်းခံမှု အသေးစိတ်')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1 fw-bold text-dark">

                <i class="fas fa-hands-helping text-primary me-2"></i>

                ကယ်ဆယ်ရေး အကူအညီ တောင်းခံမှု အသေးစိတ်

            </h3>

            <small class="text-muted">
                Request #{{ $reliefRequest->id }}
            </small>

        </div>


        <a
            href="{{ route('backend.relief_requests.index') }}"
            class="btn btn-secondary"
        >

            <i class="fas fa-arrow-left me-1"></i>

            နောက်သို့

        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <i class="fas fa-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            <i class="fas fa-exclamation-circle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row g-4">


        {{-- =====================================================
             LEFT COLUMN
        ====================================================== --}}

        <div class="col-lg-8">


            {{-- =================================================
                 REQUESTER INFORMATION
            ================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-user text-primary me-2"></i>

                        တောင်းခံသူ အချက်အလက်

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        {{-- REQUESTER NAME --}}

                        <div class="col-md-6">

                            <label class="text-muted small">
                                တောင်းခံသူ
                            </label>

                            <div class="fw-bold fs-5">

                                <i class="fas fa-user-circle text-primary me-2"></i>

                                {{ $reliefRequest->name
                                    ?? $reliefRequest->requestedBy->name
                                    ?? 'အများပြည်သူ' }}

                            </div>

                        </div>


                        {{-- PHONE --}}

                        <div class="col-md-6">

                            <label class="text-muted small">
                                ဖုန်းနံပါတ်
                            </label>

                            <div class="fw-bold">

                                @if($reliefRequest->phone_number)

                                    <a
                                        href="tel:{{ $reliefRequest->phone_number }}"
                                        class="text-decoration-none"
                                    >

                                        <i class="fas fa-phone text-success me-2"></i>

                                        {{ $reliefRequest->phone_number }}

                                    </a>

                                @else

                                    <span class="text-muted">
                                        မဖော်ပြထားပါ
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- REQUEST DATE --}}

                        <div class="col-md-6">

                            <label class="text-muted small">
                                တောင်းခံသည့်နေ့
                            </label>

                            <div class="fw-bold">

                                <i class="far fa-calendar-alt text-primary me-2"></i>

                                @if($reliefRequest->request_date)

                                    {{ \Carbon\Carbon::parse(
                                        $reliefRequest->request_date
                                    )->format('d-m-Y H:i') }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        {{-- REQUESTED BY USER --}}

                        <div class="col-md-6">

                            <label class="text-muted small">
                                System User
                            </label>

                            <div class="fw-bold">

                                @if($reliefRequest->requestedBy)

                                    <i class="fas fa-user-shield text-secondary me-2"></i>

                                    {{ $reliefRequest->requestedBy->name }}

                                @else

                                    <span class="text-muted">
                                        Public Request
                                    </span>

                                @endif

                            </div>

                        </div>


                    </div>

                </div>

            </div>



            {{-- =================================================
                 BASIC REQUEST INFORMATION
            ================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-info-circle text-primary me-2"></i>

                        တောင်းခံမှု အခြေခံအချက်အလက်များ

                    </h5>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle mb-0">


                            {{-- DISASTER --}}

                            <tr>

                                <th
                                    class="bg-light"
                                    style="width: 30%;"
                                >

                                    <i class="fas fa-house-damage text-danger me-2"></i>

                                    ဘေးအန္တရာယ်

                                </th>

                                <td>

                                    {{ $reliefRequest->disaster->name
                                        ?? $reliefRequest->disaster->title
                                        ?? 'N/A' }}

                                </td>

                            </tr>


                            {{-- WAREHOUSE --}}

                            <tr>

                                <th class="bg-light">

                                    <i class="fas fa-warehouse text-primary me-2"></i>

                                    ထုတ်ယူမည့် ဂိုဒေါင်

                                </th>

                                <td>

                                    @if($reliefRequest->warehouse)

                                        <strong>
                                            {{ $reliefRequest->warehouse->name }}
                                        </strong>

                                        @if($reliefRequest->warehouse->location)

                                            <br>

                                            <small class="text-muted">

                                                {{ $reliefRequest->warehouse->location }}

                                            </small>

                                        @endif

                                    @else

                                        N/A

                                    @endif

                                </td>

                            </tr>


                            {{-- LOCATION --}}

                            <tr>

                                <th class="bg-light">

                                    <i class="fas fa-location-dot text-danger me-2"></i>

                                    တောင်းခံသည့် တည်နေရာ

                                </th>

                                <td>

                                    {{ $reliefRequest->location ?? 'N/A' }}

                                </td>

                            </tr>


                            {{-- LATITUDE --}}

                            <tr>

                                <th class="bg-light">
                                    Latitude
                                </th>

                                <td>
                                    {{ $reliefRequest->latitude ?? '-' }}
                                </td>

                            </tr>


                            {{-- LONGITUDE --}}

                            <tr>

                                <th class="bg-light">
                                    Longitude
                                </th>

                                <td>
                                    {{ $reliefRequest->longitude ?? '-' }}
                                </td>

                            </tr>


                            {{-- STATUS --}}

                            <tr>

                                <th class="bg-light">

                                    <i class="fas fa-circle-info text-primary me-2"></i>

                                    အခြေအနေ

                                </th>

                                <td>

                                    @php

                                        $status = strtolower(
                                            trim(
                                                $reliefRequest->status ?? ''
                                            )
                                        );

                                    @endphp


                                    @if($status === 'pending')

                                        <span class="badge bg-warning text-dark px-3 py-2">

                                            <i class="fas fa-clock me-1"></i>

                                            စောင့်ဆိုင်းဆဲ

                                        </span>


                                    @elseif($status === 'approved')

                                        <span class="badge bg-success px-3 py-2">

                                            <i class="fas fa-check me-1"></i>

                                            ခွင့်ပြုပြီး

                                        </span>


                                    @elseif($status === 'rejected')

                                        <span class="badge bg-danger px-3 py-2">

                                            <i class="fas fa-times me-1"></i>

                                            ငြင်းပယ်ထားသည်

                                        </span>


                                    @elseif($status === 'completed')

                                        <span class="badge bg-info px-3 py-2">

                                            <i class="fas fa-check-double me-1"></i>

                                            ပြီးစီး

                                        </span>


                                    @else

                                        <span class="badge bg-secondary px-3 py-2">

                                            {{ $reliefRequest->status ?? 'N/A' }}

                                        </span>

                                    @endif

                                </td>

                            </tr>


                            {{-- NOTE --}}

                            <tr>

                                <th class="bg-light">

                                    <i class="fas fa-comment-alt text-secondary me-2"></i>

                                    မှတ်ချက်

                                </th>

                                <td>

                                    @if($reliefRequest->note)

                                        {{ $reliefRequest->note }}

                                    @else

                                        <span class="text-muted">
                                            မှတ်ချက်မရှိပါ
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>



            {{-- =================================================
                 HEALTH INFORMATION
            ================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-heartbeat text-danger me-2"></i>

                        ကျန်းမာရေးဆိုင်ရာ အချက်အလက်

                    </h5>

                </div>


                <div class="card-body">


                    @if($reliefRequest->is_health_related)

                        <div class="alert alert-danger">

                            <i class="fas fa-heartbeat me-2"></i>

                            <strong>
                                ကျန်းမာရေးနှင့် သက်ဆိုင်သော တောင်းခံမှု ဖြစ်ပါသည်။
                            </strong>

                        </div>


                        @if($reliefRequest->medical_proof)

                            <div class="mt-3">

                                <h6 class="fw-bold">
                                    ဆေးဘက်ဆိုင်ရာ အထောက်အထား
                                </h6>


                                @php

                                    $proofExtension = strtolower(
                                        pathinfo(
                                            $reliefRequest->medical_proof,
                                            PATHINFO_EXTENSION
                                        )
                                    );

                                    $proofUrl = asset(
                                        'storage/' .
                                        $reliefRequest->medical_proof
                                    );

                                @endphp


                                @if(
                                    in_array(
                                        $proofExtension,
                                        ['jpg', 'jpeg', 'png', 'webp']
                                    )
                                )

                                    <div class="text-center">

                                        <img
                                            src="{{ $proofUrl }}"
                                            alt="Medical Proof"
                                            class="img-fluid rounded border shadow-sm"
                                            style="
                                                max-height: 450px;
                                                cursor: pointer;
                                            "
                                            onclick="window.open(
                                                '{{ $proofUrl }}',
                                                '_blank'
                                            )"
                                        >

                                    </div>

                                    <div class="text-center mt-3">

                                        <a
                                            href="{{ $proofUrl }}"
                                            target="_blank"
                                            class="btn btn-outline-danger btn-sm"
                                        >

                                            <i class="fas fa-external-link-alt me-1"></i>

                                            အပြည့်အစုံကြည့်ရန်

                                        </a>

                                    </div>


                                @else

                                    <div class="text-center">

                                        <div
                                            class="border rounded p-4 bg-light"
                                        >

                                            <i
                                                class="fas fa-file-medical fa-3x text-danger mb-3"
                                            ></i>

                                            <p class="mb-3">
                                                ဆေးဘက်ဆိုင်ရာ PDF ဖိုင်
                                            </p>

                                            <a
                                                href="{{ $proofUrl }}"
                                                target="_blank"
                                                class="btn btn-danger"
                                            >

                                                <i class="fas fa-file-pdf me-1"></i>

                                                ဖိုင်ဖွင့်ရန်

                                            </a>

                                        </div>

                                    </div>

                                @endif

                            </div>

                        @else

                            <div class="alert alert-warning mb-0">

                                <i class="fas fa-exclamation-triangle me-2"></i>

                                ဆေးဘက်ဆိုင်ရာ အထောက်အထား ဖိုင်မတင်ထားပါ။

                            </div>

                        @endif


                    @else

                        <div class="alert alert-secondary mb-0">

                            <i class="fas fa-minus-circle me-2"></i>

                            ကျန်းမာရေးနှင့် သက်ဆိုင်သော တောင်းခံမှု မဟုတ်ပါ။

                        </div>

                    @endif

                </div>

            </div>



            {{-- =================================================
                 LOCATION MAP
            ================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-map-marked-alt text-danger me-2"></i>

                            တောင်းခံသည့် တည်နေရာမြေပုံ

                        </h5>


                        @if(
                            $reliefRequest->latitude &&
                            $reliefRequest->longitude
                        )

                            <a
                                href="https://www.google.com/maps?q={{ $reliefRequest->latitude }},{{ $reliefRequest->longitude }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary"
                            >

                                <i class="fas fa-map-marker-alt me-1"></i>

                                Google Maps

                            </a>

                        @endif

                    </div>

                </div>


                <div class="card-body p-0">


                    @if(
                        $reliefRequest->latitude &&
                        $reliefRequest->longitude
                    )

                        <div
                            id="reliefRequestMap"
                            style="
                                width: 100%;
                                height: 400px;
                                border-radius: 0 0 8px 8px;
                            "
                        ></div>


                        <div class="p-3 bg-light">

                            <div class="row">

                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Latitude
                                    </small>

                                    <div class="fw-bold">
                                        {{ $reliefRequest->latitude }}
                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted">
                                        Longitude
                                    </small>

                                    <div class="fw-bold">
                                        {{ $reliefRequest->longitude }}
                                    </div>

                                </div>

                            </div>


                            @if($reliefRequest->location)

                                <hr>

                                <div>

                                    <small class="text-muted">
                                        တည်နေရာ
                                    </small>

                                    <div class="fw-bold">

                                        <i class="fas fa-location-dot text-danger me-1"></i>

                                        {{ $reliefRequest->location }}

                                    </div>

                                </div>

                            @endif

                        </div>


                    @else

                        <div class="text-center py-5 text-muted">

                            <i class="fas fa-map-marker-alt fa-3x mb-3"></i>

                            <h6>
                                GPS Location မရှိသေးပါ။
                            </h6>

                            <small>
                                Latitude နှင့် Longitude မရှိသောကြောင့်
                                မြေပုံပြသ၍ မရပါ။
                            </small>

                        </div>

                    @endif

                </div>

            </div>



            {{-- =================================================
                 REQUESTED ITEMS
            ================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-boxes text-primary me-2"></i>

                        တောင်းခံထားသော ပစ္စည်းများ

                    </h5>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-striped table-hover mb-0">

                            <thead class="table-dark">

                                <tr>

                                    <th
                                        class="text-center"
                                        style="width: 70px;"
                                    >
                                        #
                                    </th>

                                    <th>
                                        ပစ္စည်းအမည်
                                    </th>

                                    <th>
                                        Unit
                                    </th>

                                    <th
                                        class="text-center"
                                        style="width: 150px;"
                                    >
                                        အရေအတွက်
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse(
                                    $reliefRequest->requestItems
                                    as $key => $reqItem
                                )

                                    <tr>

                                        <td class="text-center fw-bold">
                                            {{ $key + 1 }}
                                        </td>


                                        <td>

                                            <div class="fw-bold">

                                                <i
                                                    class="fas fa-box text-primary me-2"
                                                ></i>

                                                {{ $reqItem->item->name ?? 'N/A' }}

                                            </div>

                                        </td>


                                        <td>

                                            {{ $reqItem->item->unit ?? '-' }}

                                        </td>


                                        <td class="text-center">

                                            <span class="badge bg-primary fs-6">

                                                {{ $reqItem->quantity }}

                                            </span>

                                        </td>

                                    </tr>


                                @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="text-center text-muted py-4"
                                        >

                                            <i
                                                class="fas fa-box-open fa-2x mb-2"
                                            ></i>

                                            <br>

                                            တောင်းခံထားသော ပစ္စည်း မရှိပါ။

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>



        {{-- =====================================================
             RIGHT SIDEBAR
        ====================================================== --}}

        <div class="col-lg-4">


            {{-- =================================================
                 STATUS CARD
            ================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-tasks text-primary me-2"></i>

                        လုပ်ဆောင်ချက်များ

                    </h5>

                </div>


                <div class="card-body">

                    @php

                        $status = strtolower(
                            trim(
                                $reliefRequest->status ?? ''
                            )
                        );

                    @endphp


                    @if($status === 'pending')


                        {{-- APPROVE --}}

                        <form
                            action="{{ route(
                                'backend.relief_requests.approve',
                                $reliefRequest->id
                            ) }}"
                            method="POST"
                            class="mb-3"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-success w-100 py-2"
                                onclick="return confirm(
                                    'ဤတောင်းဆိုချက်အား ခွင့်ပြုရန် သေချာပါသလား။'
                                )"
                            >

                                <i class="fas fa-check-circle me-2"></i>

                                အတည်ပြုမည် (Approve)

                            </button>

                        </form>


                        {{-- REJECT --}}

                        <form
                            action="{{ route(
                                'backend.relief_requests.reject',
                                $reliefRequest->id
                            ) }}"
                            method="POST"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-danger w-100 py-2"
                                onclick="return confirm(
                                    'ဤတောင်းဆိုချက်အား ငြင်းပယ်ရန် သေချာပါသလား။'
                                )"
                            >

                                <i class="fas fa-times-circle me-2"></i>

                                ငြင်းပယ်မည် (Reject)

                            </button>

                        </form>


                    @elseif($status === 'approved')

                        <div class="alert alert-success mb-0">

                            <div class="fw-bold mb-2">

                                <i class="fas fa-check-circle me-2"></i>

                                အတည်ပြုပြီးပါပြီ

                            </div>

                            <small>

                                ဤတောင်းခံမှုကို အတည်ပြုပြီးဖြစ်ပါသည်။

                            </small>

                        </div>


                    @elseif($status === 'rejected')

                        <div class="alert alert-danger mb-0">

                            <div class="fw-bold mb-2">

                                <i class="fas fa-times-circle me-2"></i>

                                ငြင်းပယ်ထားပါသည်

                            </div>

                            <small>

                                ဤတောင်းခံမှုကို ငြင်းပယ်ပြီးဖြစ်ပါသည်။

                            </small>

                        </div>


                    @elseif($status === 'completed')

                        <div class="alert alert-info mb-0">

                            <div class="fw-bold mb-2">

                                <i class="fas fa-check-double me-2"></i>

                                ပြီးစီးပါပြီ

                            </div>

                            <small>

                                ဤတောင်းခံမှုသည် ပြီးစီးပြီးဖြစ်ပါသည်။

                            </small>

                        </div>


                    @else

                        <div class="alert alert-secondary mb-0">

                            လက်ရှိအခြေအနေ -

                            <strong>
                                {{ $reliefRequest->status ?? 'N/A' }}
                            </strong>

                        </div>

                    @endif

                </div>

            </div>



            {{-- =================================================
                 QUICK INFORMATION
            ================================================== --}}

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h6 class="mb-0 fw-bold">

                        <i class="fas fa-info text-primary me-2"></i>

                        အချက်အလက် အကျဉ်းချုပ်

                    </h6>

                </div>


                <div class="card-body">


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Request ID
                        </span>

                        <strong>
                            #{{ $reliefRequest->id }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            ပစ္စည်းအရေအတွက်
                        </span>

                        <strong>

                            {{ $reliefRequest->requestItems->count() }}

                            မျိုး

                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            ကျန်းမာရေး
                        </span>


                        @if($reliefRequest->is_health_related)

                            <span class="badge bg-danger">
                                လိုအပ်
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                မလိုအပ်
                            </span>

                        @endif

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            GPS
                        </span>


                        @if(
                            $reliefRequest->latitude &&
                            $reliefRequest->longitude
                        )

                            <span class="badge bg-success">

                                <i class="fas fa-map-marker-alt me-1"></i>

                                Available

                            </span>

                        @else

                            <span class="badge bg-secondary">
                                မရှိ
                            </span>

                        @endif

                    </div>

                </div>

            </div>



            {{-- =================================================
                 GOOGLE MAP BUTTON
            ================================================== --}}

            @if(
                $reliefRequest->latitude &&
                $reliefRequest->longitude
            )

                <div class="card shadow-sm border-0 mb-4">

                    <div class="card-body text-center">

                        <i
                            class="fas fa-map-marker-alt fa-2x text-danger mb-3"
                        ></i>

                        <h6 class="fw-bold">
                            Google Maps တွင်ကြည့်ရန်
                        </h6>

                        <p class="small text-muted">

                            {{ $reliefRequest->latitude }},
                            {{ $reliefRequest->longitude }}

                        </p>


                        <a
                            href="https://www.google.com/maps?q={{ $reliefRequest->latitude }},{{ $reliefRequest->longitude }}"
                            target="_blank"
                            class="btn btn-primary w-100"
                        >

                            <i class="fas fa-external-link-alt me-2"></i>

                            Google Maps ဖွင့်မည်

                        </a>

                    </div>

                </div>

            @endif


        </div>

    </div>

</div>


{{-- =============================================================
     LEAFLET MAP
============================================================== --}}

@if(
    $reliefRequest->latitude &&
    $reliefRequest->longitude
)

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >


    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    ></script>


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const latitude = {{ (float) $reliefRequest->latitude }};

            const longitude = {{ (float) $reliefRequest->longitude }};


            const map = L.map('reliefRequestMap').setView(
                [latitude, longitude],
                15
            );


            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                {
                    maxZoom: 19,
                    attribution:
                        '&copy; OpenStreetMap contributors'
                }
            ).addTo(map);


            const marker = L.marker(
                [latitude, longitude]
            ).addTo(map);


            marker.bindPopup(`
                <div style="min-width:220px">

                    <strong>
                        ကယ်ဆယ်ရေး တောင်းခံသည့်နေရာ
                    </strong>

                    <hr>

                    <div>
                        <strong>တောင်းခံသူ:</strong>
                        {{ $reliefRequest->name
                            ?? $reliefRequest->requestedBy->name
                            ?? 'အများပြည်သူ' }}
                    </div>

                    <div class="mt-1">
                        <strong>တည်နေရာ:</strong>
                        {{ $reliefRequest->location ?? 'N/A' }}
                    </div>

                    <div class="mt-1">
                        <strong>Latitude:</strong>
                        ${latitude}
                    </div>

                    <div class="mt-1">
                        <strong>Longitude:</strong>
                        ${longitude}
                    </div>

                </div>
            `).openPopup();


            /*
            |--------------------------------------------------------------------------
            | Resize Map
            |--------------------------------------------------------------------------
            */

            setTimeout(function () {

                map.invalidateSize();

            }, 300);

        });

    </script>

@endif

@endsection
