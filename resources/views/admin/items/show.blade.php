@extends('layouts.admin')

@section('title', 'ပစ္စည်းအသေးစိတ် - ' . $item->name)

@section('content')

<div class="container-fluid py-3">

```
<!-- Header Controls -->
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1 fw-bold text-dark">
            <i class="fas fa-box text-primary me-2"></i>
            ပစ္စည်းအသေးစိတ်
        </h4>

        <p class="text-muted mb-0 small">
            ဤပစ္စည်း၏ အချက်အလက်များနှင့် လက်ကျန်ပစ္စည်း အခြေအနေကို
            ကြည့်ရှုနိုင်ပါသည်။
        </p>
    </div>

    <div class="d-flex gap-2">

        <a
            href="{{ route('backend.items.edit', $item->id) }}"
            class="btn btn-warning text-white btn-sm px-3"
        >
            <i class="fas fa-edit me-1"></i>
            ပစ္စည်းပြင်ဆင်ရန်
        </a>

        <a
            href="{{ route('backend.items.index') }}"
            class="btn btn-outline-secondary btn-sm px-3"
        >
            <i class="fas fa-arrow-left me-1"></i>
            နောက်သို့
        </a>

    </div>

</div>


<div class="row g-4">

    <!-- Main Information Card -->
    <div class="col-lg-8">

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white py-3">

                <div class="d-flex align-items-center">

                    <div
                        class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm"
                        style="width:50px; height:50px;"
                    >
                        <i class="fas fa-cubes text-primary fa-lg"></i>
                    </div>

                    <div>

                        <h5 class="mb-0 fw-bold">
                            {{ $item->name }}
                        </h5>

                        <small class="text-white-50">
                            SKU ကုဒ် -
                            {{ $item->barcode ?? 'SKU-' . str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                <div class="row g-3">

                    <!-- Category -->
                    <div class="col-md-6">

                        <div class="border rounded p-3 bg-light h-100">

                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-tags me-1 text-primary"></i>
                                ပစ္စည်းအမျိုးအစား
                            </small>

                            <h6 class="mb-0 fw-bold text-dark">
                                {{ $item->category->name ?? 'အမျိုးအစား မသတ်မှတ်ရသေးပါ' }}
                            </h6>

                        </div>

                    </div>


                    <!-- Unit -->
                    <div class="col-md-6">

                        <div class="border rounded p-3 bg-light h-100">

                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-balance-scale me-1 text-primary"></i>
                                ထုပ်ပိုး / တိုင်းတာယူနစ်
                            </small>

                            <h6 class="mb-0 fw-bold text-dark">
                                {{ $item->unit }}
                            </h6>

                        </div>

                    </div>


                    <!-- Description -->
                    <div class="col-12">

                        <div class="border rounded p-3">

                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-align-left me-1 text-primary"></i>
                                ပစ္စည်းအကြောင်းအရာ
                            </small>

                            <p class="mb-0 text-dark">

                                {{ $item->description
                                    ?? 'ဤပစ္စည်းအတွက် အသေးစိတ်အချက်အလက် ဖော်ပြထားခြင်းမရှိပါ။'
                                }}

                            </p>

                        </div>

                    </div>


                    <!-- Minimum Stock Threshold -->
                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-exclamation-triangle me-1 text-warning"></i>
                                အနည်းဆုံးလက်ကျန်သတ်မှတ်ချက်
                            </small>

                            <h5 class="mb-0 fw-bold text-dark">
                                {{ $item->minimum_stock }}
                                {{ $item->unit }}
                            </h5>

                        </div>

                    </div>


                    <!-- Current Item Status -->
                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-toggle-on me-1 text-primary"></i>
                                ပစ္စည်းအခြေအနေ
                            </small>

                            @if($item->status === 'Active')

                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>
                                    အသုံးပြုနိုင်သည်
                                </span>

                            @else

                                <span class="badge bg-secondary px-3 py-2">
                                    <i class="fas fa-times-circle me-1"></i>
                                    အသုံးပြု၍မရပါ
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">

                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    နောက်ဆုံးပြင်ဆင်ချိန် -
                    {{ $item->updated_at?->format('d M Y, h:i A') ?? 'မရှိပါ' }}
                </small>

                <small class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>
                    စတင်မှတ်တမ်းတင်သည့်ရက် -
                    {{ $item->created_at?->format('d M Y') ?? 'မရှိပါ' }}
                </small>

            </div>

        </div>

    </div>


    <!-- Side Panel: Stock Health & Barcode/QR -->
    <div class="col-lg-4">

        <!-- Stock Health Status Card -->
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-chart-line me-2 text-primary"></i>
                    လက်ကျန်ပစ္စည်း အခြေအနေ
                </h6>

            </div>


            <div class="card-body p-4 text-center">

                <div class="mb-3">

                    <span
                        class="display-5 fw-bold {{ $item->is_low_stock ? 'text-danger' : 'text-success' }}"
                    >
                        {{ $item->total_stock }}
                    </span>

                    <span class="text-muted">
                        / {{ $item->minimum_stock }}
                        {{ $item->unit }}
                    </span>

                </div>


                @if($item->is_low_stock)

                    <div
                        class="alert alert-danger mb-0 py-2"
                        role="alert"
                    >
                        <i class="fas fa-exclamation-circle me-1"></i>

                        <strong>လက်ကျန်နည်းနေပါသည်!</strong>

                        <br>

                        သတ်မှတ်ထားသော အနည်းဆုံးလက်ကျန်ပမာဏအောက်သို့
                        ရောက်ရှိနေပါသည်။
                    </div>

                @else

                    <div
                        class="alert alert-success mb-0 py-2"
                        role="alert"
                    >
                        <i class="fas fa-check-circle me-1"></i>

                        <strong>လက်ကျန်လုံလောက်ပါသည်။</strong>
                    </div>

                @endif


                <hr class="my-3">


                <!-- Expiry Date Status -->
                <div class="d-flex justify-content-between align-items-center">

                    <span class="text-muted small">
                        သက်တမ်းကုန်ဆုံးရက် -
                    </span>

                    <span class="fw-bold text-dark small">

                        {{ $item->expiry_date
                            ? $item->expiry_date->format('Y-m-d')
                            : 'မရှိပါ'
                        }}

                    </span>

                </div>


                <div class="mt-2">

                    @if($item->expiry_date)

                        @if($item->is_expired)

                            <span class="badge bg-danger w-100 py-2">

                                <i class="fas fa-times-circle me-1"></i>

                                သက်တမ်းကုန်ဆုံးပြီး
                            </span>

                        @elseif($item->is_expiring_soon)

                            <span class="badge bg-warning text-dark w-100 py-2">

                                <i class="fas fa-exclamation-triangle me-1"></i>

                                သက်တမ်းကုန်ဆုံးရန် နီးကပ်နေသည်
                            </span>

                        @else

                            <span class="badge bg-success-subtle text-success border border-success w-100 py-2">

                                <i class="fas fa-check me-1"></i>

                                သက်တမ်းမှန်ကန်ပါသည်
                            </span>

                        @endif

                    @endif

                </div>

            </div>

        </div>


        <!-- Barcode & QR Display Card -->
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white py-3 text-center">

                <h6 class="mb-0 fw-bold text-dark">

                    <i class="fas fa-qrcode me-2 text-primary"></i>

                    ဘားကုဒ်နှင့် QR Code
                </h6>

            </div>


            <div class="card-body p-4 text-center bg-light">

                @if($item->barcode)

                    <div class="p-3 bg-white rounded border d-inline-block mb-3 shadow-sm">

                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($item->barcode) }}"
                            alt="QR Code"
                            class="img-fluid"
                        >

                    </div>


                    <div>

                        <code class="px-3 py-1 bg-dark text-white rounded fs-6">
                            {{ $item->barcode }}
                        </code>

                    </div>

                @else

                    <div class="py-4 text-muted">

                        <i class="fas fa-barcode fa-3x mb-2 d-block text-secondary"></i>

                        ဤပစ္စည်းအတွက် ဘားကုဒ် သတ်မှတ်ထားခြင်းမရှိပါ။

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>
```

</div>
@endsection
