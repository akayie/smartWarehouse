@extends('layouts.admin')

@section('title', 'ကူညီထောက်ပံ့မှု တောင်းဆိုချက်များ')

@section('content')

<div class="card shadow-sm border-0">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="card-header bg-white py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="mb-1 fw-bold text-dark">

                    <i class="fas fa-hands-helping me-2 text-primary"></i>

                    ကူညီထောက်ပံ့မှု တောင်းဆိုချက်များ

                </h5>

                <small class="text-muted">
                    Relief Request Management
                </small>

            </div>

            <div>

                <span class="badge bg-primary">
                    စုစုပေါင်း
                    {{ $reliefRequests->total() }}
                    ခု
                </span>

            </div>

        </div>

    </div>


    <div class="card-body">

        {{-- =====================================================
             SUCCESS ALERT
        ====================================================== --}}
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
                    aria-label="Close"
                ></button>

            </div>

        @endif


        {{-- =====================================================
             ERROR ALERT
        ====================================================== --}}
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
                    aria-label="Close"
                ></button>

            </div>

        @endif



        {{-- =====================================================
             TABLE
        ====================================================== --}}
        <div class="table-responsive">

            <table
                class="table table-bordered table-hover align-middle mb-0"
            >

                <thead class="table-dark">

                    <tr>

                        <th
                            style="width: 60px;"
                            class="text-center"
                        >
                            စဉ်
                        </th>


                        <th>
                            တောင်းဆိုသူ
                        </th>


                        <th>
                            ဖုန်းနံပါတ်
                        </th>


                        <th>
                            ဘေးအန္တရာယ် ဖြစ်စဉ်
                        </th>


                        <th>
                            တည်နေရာ
                        </th>


                        <th>
                            ဂိုဒေါင်
                        </th>


                        <th
                            class="text-center"
                            style="width: 130px;"
                        >
                            အခြေအနေ
                        </th>


                        <th
                            class="text-center"
                            style="width: 220px;"
                        >
                            ဆောင်ရွက်ချက်
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($reliefRequests as $request)

                        <tr>

                            {{-- =================================================
                                 NUMBER
                            ================================================== --}}
                            <td class="text-center fw-bold">

                                {{ $reliefRequests->firstItem() + $loop->index }}

                            </td>



                            {{-- =================================================
                                 REQUESTER NAME
                            ================================================== --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div
                                        class="bg-primary text-white rounded-circle
                                               d-flex align-items-center justify-content-center
                                               me-2"
                                        style="
                                            width: 38px;
                                            height: 38px;
                                            min-width: 38px;
                                        "
                                    >

                                        <i class="fas fa-user"></i>

                                    </div>


                                    <div>

                                        <div class="fw-bold">

                                            {{ $request->name
                                                ?? $request->requestedBy->name
                                                ?? 'အများပြည်သူ' }}

                                        </div>


                                        @if($request->request_date)

                                            <small class="text-muted">

                                                <i class="far fa-calendar me-1"></i>

                                                {{ \Carbon\Carbon::parse($request->request_date)->format('d-m-Y') }}

                                            </small>

                                        @endif

                                    </div>

                                </div>

                            </td>



                            {{-- =================================================
                                 PHONE NUMBER
                            ================================================== --}}
                            <td>

                                @if($request->phone_number)

                                    <a
                                        href="tel:{{ $request->phone_number }}"
                                        class="text-decoration-none"
                                    >

                                        <i
                                            class="fas fa-phone-alt
                                                   text-success me-1"
                                        ></i>

                                        {{ $request->phone_number }}

                                    </a>

                                @else

                                    <span class="text-muted">
                                        မဖော်ပြထားပါ
                                    </span>

                                @endif

                            </td>



                            {{-- =================================================
                                 DISASTER
                            ================================================== --}}
                            <td>

                                <div class="fw-bold">

                                    {{
                                        $request->disaster->name
                                        ?? $request->disaster->title
                                        ?? 'အထွေထွေ ထောက်ပံ့မှု'
                                    }}

                                </div>


                                @if($request->disaster && $request->disaster->type)

                                    <small class="text-muted">

                                        <i class="fas fa-tag me-1"></i>

                                        {{ $request->disaster->type }}

                                    </small>

                                @endif

                            </td>



                            {{-- =================================================
                                 LOCATION
                            ================================================== --}}
                            <td>

                                @if($request->location)

                                    <span>

                                        <i
                                            class="fas fa-location-dot
                                                   text-danger me-1"
                                        ></i>

                                        {{ $request->location }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        မဖော်ပြထားပါ
                                    </span>

                                @endif

                            </td>



                            {{-- =================================================
                                 WAREHOUSE
                            ================================================== --}}
                            <td>

                                @if($request->warehouse)

                                    <div class="fw-bold">

                                        <i
                                            class="fas fa-warehouse
                                                   text-primary me-1"
                                        ></i>

                                        {{ $request->warehouse->name }}

                                    </div>


                                    @if($request->warehouse->location)

                                        <small class="text-muted">

                                            {{ $request->warehouse->location }}

                                        </small>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        မသတ်မှတ်ရသေးပါ
                                    </span>

                                @endif

                            </td>



                            {{-- =================================================
                                 STATUS
                            ================================================== --}}
                            <td class="text-center">

                                @php

                                    $status =
                                        strtolower(
                                            trim(
                                                $request->status ?? ''
                                            )
                                        );

                                @endphp


                                @if(
                                    $status === 'pending' ||
                                    $status === 'စောင့်ဆိုင်းဆဲ'
                                )

                                    <span class="badge bg-warning text-dark">

                                        <i class="fas fa-clock me-1"></i>

                                        စောင့်ဆိုင်းဆဲ

                                    </span>


                                @elseif(
                                    $status === 'approved' ||
                                    $status === 'ခွင့်ပြုပြီး'
                                )

                                    <span class="badge bg-success">

                                        <i class="fas fa-check me-1"></i>

                                        ခွင့်ပြုပြီး

                                    </span>


                                @elseif(
                                    $status === 'rejected' ||
                                    $status === 'ငြင်းပယ်ထားသည်'
                                )

                                    <span class="badge bg-danger">

                                        <i class="fas fa-times me-1"></i>

                                        ငြင်းပယ်ထားသည်

                                    </span>


                                @elseif(
                                    $status === 'completed' ||
                                    $status === 'ပြီးစီး'
                                )

                                    <span class="badge bg-info">

                                        <i class="fas fa-check-double me-1"></i>

                                        ပြီးစီး

                                    </span>


                                @else

                                    <span class="badge bg-secondary">

                                        {{ $request->status ?? 'မသတ်မှတ်ရသေးပါ' }}

                                    </span>

                                @endif

                            </td>



                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}
                            <td class="text-center">

                                {{-- VIEW --}}
                                <a
                                    href="{{ route(
                                        'backend.relief_requests.show',
                                        $request->id
                                    ) }}"
                                    class="btn btn-sm btn-info text-white mb-1"
                                    title="ကြည့်မည်"
                                >

                                    <i class="fas fa-eye me-1"></i>

                                    ကြည့်မည်

                                </a>



                                @if($status === 'pending')

                                    {{-- APPROVE --}}
                                    <form
                                        action="{{ route(
                                            'backend.relief_requests.approve',
                                            $request->id
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            'ဤတောင်းဆိုချက်အား ခွင့်ပြုရန် သေချာပါသလား။'
                                        )"
                                    >

                                        @csrf

                                        @method('PATCH')


                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-success mb-1"
                                            title="ခွင့်ပြုမည်"
                                        >

                                            <i class="fas fa-check"></i>

                                        </button>

                                    </form>



                                    {{-- REJECT --}}
                                    <form
                                        action="{{ route(
                                            'backend.relief_requests.reject',
                                            $request->id
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            'ဤတောင်းဆိုချက်အား ငြင်းပယ်ရန် သေချာပါသလား။'
                                        )"
                                    >

                                        @csrf

                                        @method('PATCH')


                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger mb-1"
                                            title="ငြင်းပယ်မည်"
                                        >

                                            <i class="fas fa-times"></i>

                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i
                                        class="fas fa-inbox fa-3x mb-3"
                                    ></i>


                                    <h6 class="fw-bold">

                                        ကူညီထောက်ပံ့မှု
                                        တောင်းဆိုချက်များ မရှိသေးပါ။

                                    </h6>


                                    <small>

                                        Relief Request မှတ်တမ်း
                                        မတွေ့ရှိပါ။

                                    </small>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>



        {{-- =====================================================
             PAGINATION
        ====================================================== --}}
        @if($reliefRequests->hasPages())

            <div class="mt-4 d-flex justify-content-end">

                {{ $reliefRequests->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
