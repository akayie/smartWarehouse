@extends('layouts.admin')

@section('title', 'ဖြန့်ဝေမှုအသေးစိတ် #' . $distribution->id)

@section('button')
<a href="{{ route('backend.distributions.index') }}" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left me-1"></i>
    နောက်သို့
</a>
@endsection

@section('content')

<div class="container-fluid py-3">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold text-dark mb-1">
                ဖြန့်ဝေမှုမှတ်တမ်း #{{ $distribution->id }}
            </h3>

            <p class="text-muted mb-0">
                ကယ်ဆယ်ရေးပစ္စည်းများ ဖြန့်ဝေပေးထားမှု အသေးစိတ်မှတ်တမ်း
            </p>
        </div>

        <div>

            <button onclick="window.print()"
                    class="btn btn-outline-secondary me-2">
                <i class="fa-solid fa-print me-1"></i>
                မှတ်တမ်းထုတ်ရန်
            </button>

            <a href="{{ route('backend.distributions.index') }}"
               class="btn btn-primary">
                <i class="fa-solid fa-arrow-left me-1"></i>
                မှတ်တမ်းစာရင်းသို့
            </a>

        </div>

    </div>


    {{-- Distribution Information --}}
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


                {{-- Distribution Date --}}
                <div class="col-md-3">

                    <span class="text-muted d-block mb-1">
                        ဖြန့်ဝေသည့်ရက်
                    </span>

                    <strong class="fs-6">

                        @if($distribution->distribution_date)

                            {{ $distribution->distribution_date->format('d-m-Y') }}

                        @else

                            -

                        @endif

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


                {{-- Total Items --}}
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
                <div class="col-md-3">

                    <span class="text-muted d-block mb-1">
                        စုစုပေါင်းဖြန့်ဝေသည့်ပမာဏ
                    </span>

                    <strong class="fs-6 text-danger">

                        {{ number_format(
                            $distribution->distributionItems->sum('quantity')
                        ) }}

                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Distributed Items --}}
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

                            <th class="ps-3" style="width:60px;">
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

                                {{-- Number --}}
                                <td class="ps-3">
                                    {{ $index + 1 }}
                                </td>


                                {{-- Barcode --}}
                                <td>

                                    @if($detail->item->barcode ?? false)

                                        <code>
                                            {{ $detail->item->barcode }}
                                        </code>

                                    @else

                                        <span class="text-muted">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- Item Name --}}
                                <td>

                                    <strong>
                                        {{ $detail->item->name ?? 'ပစ္စည်းမတွေ့ပါ' }}
                                    </strong>

                                </td>


                                {{-- Quantity --}}
                                <td class="text-end">

                                    <span class="badge bg-danger fs-6">

                                        - {{ number_format($detail->quantity) }}

                                    </span>

                                </td>


                                {{-- Unit --}}
                                <td>

                                    {{ $detail->item->unit ?? 'ယူနစ်' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="text-center py-5 text-muted">

                                    <i class="fa-solid fa-box-open fa-2x mb-2 d-block"></i>

                                    <div class="fw-semibold">
                                        ဖြန့်ဝေထားသော ပစ္စည်းမှတ်တမ်း မရှိသေးပါ။
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    {{-- Total --}}
                    @if($distribution->distributionItems->count() > 0)

                        <tfoot class="table-light">

                            <tr>

                                <th colspan="3"
                                    class="text-end">

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


    {{-- Footer Actions --}}
    <div class="d-flex justify-content-between align-items-center mt-4">

        <a href="{{ route('backend.distributions.index') }}"
           class="btn btn-secondary">

            <i class="fa-solid fa-arrow-left me-1"></i>

            ဖြန့်ဝေမှုစာရင်းသို့ ပြန်သွားရန်

        </a>


        <button onclick="window.print()"
                class="btn btn-outline-primary">

            <i class="fa-solid fa-print me-1"></i>

            ဖြန့်ဝေမှုမှတ်တမ်း ထုတ်ရန်

        </button>

    </div>

</div>


{{-- Print CSS --}}
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
