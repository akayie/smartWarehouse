@extends('layouts.admin')

@section('title', 'လှူဒါန်းမှုဆိုင်ရာ မှတ်ချက်စာ')

@section('button')

<a
    href="{{ route('backend.donation_payments.index') }}"
    class="btn btn-secondary me-2">

    <i class="fas fa-arrow-left me-1"></i>
    နောက်သို့

</a>

<a
    href="{{ route('backend.donation_payments.pdf', $donationPayment->id) }}"
    target="_blank"
    class="btn btn-danger">

    <i class="fas fa-file-pdf me-1"></i>
    PDF ထုတ်ရန်

</a>

@endsection


@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        REMARK LETTER
    ========================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-body p-5">

            {{-- HEADER --}}

            <div class="text-center mb-4">

                <h2 class="fw-bold">
                    SmartWarehouse
                </h2>

                <h4 class="fw-bold mt-3">
                    လှူဒါန်းမှုဆိုင်ရာ မှတ်ချက်စာ
                </h4>

                <p class="text-muted mb-0">
                    Donation Remark Letter
                </p>

            </div>


            {{-- DATE --}}

            <div class="mb-3">

                <strong>
                    ရက်စွဲ (Date):
                </strong>

                {{ optional($donationPayment->payment_date)
                    ->format('Y ခုနှစ်၊ m လ d ရက်') }}

            </div>


            {{-- SUBJECT --}}

            <div class="mb-4">

                <strong>
                    အကြောင်းအရာ (Subject):
                </strong>

                လှူဒါန်းမှုဆိုင်ရာ မှတ်တမ်းနှင့် ကျေးဇူးတင်စကား

            </div>


            {{-- =================================================
                1. OVERVIEW
            ================================================== --}}

            <h5 class="fw-bold text-primary mt-4">
                ၁။ ခြုံငုံသုံးသပ်ချက် (Overview)
            </h5>

            <p class="lh-lg">

                စေတနာရှင်
                <strong>
                    {{ $donor->name ?? 'မသိရှိပါ' }}
                </strong>
                မှ SmartWarehouse စနစ်မှတစ်ဆင့်
                သဘာဝဘေးအန္တရာယ်ခံစားနေရသော ပြည်သူများအတွက်
                လှူဒါန်းခဲ့သည့် ပစ္စည်းနှင့် ငွေကြေးများကို
                အောက်ပါအတိုင်း မှတ်တမ်းတင်အပ်ပါသည်။

            </p>


            {{-- =================================================
                2. DONOR INFORMATION
            ================================================== --}}

            <h5 class="fw-bold text-primary mt-4">
                ၂။ လှူဒါန်းသူအချက်အလက် (Donor Information)
            </h5>

            <table class="table table-bordered">

                <tr>
                    <th width="35%">စဉ်</th>
                    <th>အချက်အလက်</th>
                    <th>အသေးစိတ်</th>
                </tr>

                <tr>
                    <td>၁</td>
                    <td>အမည်</td>
                    <td>
                        {{ $donor->name ?? 'မသိရှိပါ' }}
                    </td>
                </tr>

                <tr>
                    <td>၂</td>
                    <td>ဖုန်းနံပါတ်</td>
                    <td>
                        {{ $donor->phone ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>၃</td>
                    <td>အီးမေးလ်</td>
                    <td>
                        {{ $donor->email ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>၄</td>
                    <td>လှူဒါန်းသည့်နေ့</td>
                    <td>
                        {{ optional($donationPayment->payment_date)
                            ->format('Y-m-d') }}
                    </td>
                </tr>

            </table>


            {{-- =================================================
                3. DONATED ITEMS
            ================================================== --}}

            <h5 class="fw-bold text-primary mt-4">
                ၃။ လှူဒါန်းထားသော ပစ္စည်းစာရင်း (Donated Items List)
            </h5>

            <table class="table table-bordered">

                <thead class="table-light">

                    <tr>
                        <th>စဉ်</th>
                        <th>ပစ္စည်းအမည်</th>
                        <th>အရေအတွက်</th>
                        <th>ယူနစ်</th>
                        <th>သိုလှောင်ရုံ</th>
                        <th>မှတ်ချက်</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($donatedItems as $index => $donationItem)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ optional($donationItem->item)->name ?? '-' }}
                            </td>

                            <td>
                                {{ number_format(
                                    $donationItem->quantity ?? 0
                                ) }}
                            </td>

                            <td>
                                {{ optional($donationItem->item)->unit ?? '-' }}
                            </td>

                            <td>
                                {{ optional($warehouse)->name ?? '-' }}
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    လက်ခံရရှိပြီး
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted">

                                ဤလှူဒါန်းမှုတွင်
                                ပစ္စည်းလှူဒါန်းမှု မရှိပါ။

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>


            {{-- =================================================
                4. MONETARY DONATION
            ================================================== --}}

            <h5 class="fw-bold text-primary mt-4">
                ၄။ ငွေကြေးလှူဒါန်းမှု အချက်အလက်
                (Monetary Donation Details)
            </h5>

            <table class="table table-bordered">

                <tr>
                    <th width="35%">စဉ်</th>
                    <th>အချက်အလက်</th>
                    <th>အသေးစိတ်</th>
                </tr>

                <tr>
                    <td>၁</td>
                    <td>ငွေပမာဏ</td>
                    <td>
                        <strong class="text-success">

                            {{ number_format(
                                $donationPayment->amount ?? 0,
                                0
                            ) }}

                            {{ $donationMoney->currency ?? 'MMK' }}

                        </strong>
                    </td>
                </tr>

                <tr>
                    <td>၂</td>
                    <td>ငွေပေးချေနည်း</td>
                    <td>
                        {{ $donationPayment->payment_method ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>၃</td>
                    <td>ငွေလွှဲအကိုးအကား</td>
                    <td>
                        {{ $donationPayment->transaction_reference ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>၄</td>
                    <td>ငွေပေးချေသည့်နေ့</td>
                    <td>
                        {{ optional($donationPayment->payment_date)
                            ->format('Y-m-d') }}
                    </td>
                </tr>

                <tr>
                    <td>၅</td>
                    <td>အခြေအနေ</td>
                    <td>

                        @if($donationPayment->status === 'Completed')

                            <span class="badge bg-success">
                                ပြီးစီး (Completed)
                            </span>

                        @elseif($donationPayment->status === 'Pending')

                            <span class="badge bg-warning text-dark">
                                စောင့်ဆိုင်းဆဲ (Pending)
                            </span>

                        @elseif($donationPayment->status === 'Failed')

                            <span class="badge bg-danger">
                                မအောင်မြင် (Failed)
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $donationPayment->status }}
                            </span>

                        @endif

                    </td>
                </tr>

                <tr>
                    <td>၆</td>
                    <td>ငွေလွှဲအထောက်အထား</td>
                    <td>

                        @if($donationPayment->proof)

                            <a
                                href="{{ asset(
                                    'storage/' . $donationPayment->proof
                                ) }}"
                                target="_blank">

                                ပူးတွဲပါရှိ
                                (Payment Proof)

                            </a>

                        @else

                            မပါရှိပါ

                        @endif

                    </td>
                </tr>

            </table>


            {{-- =================================================
                5. CURRENT STATUS
            ================================================== --}}

            <h5 class="fw-bold text-primary mt-4">
                ၅။ လက်ရှိအခြေအနေ (Current Status)
            </h5>

            <table class="table table-bordered">

                <tr>

                    <th width="35%">
                        လှူဒါန်းမှုအခြေအနေ
                    </th>

                    <td>
                        <span class="badge bg-success">
                            Received (လက်ခံရရှိပြီး)
                        </span>
                    </td>

                </tr>

                <tr>

                    <th>
                        ပစ္စည်းသိုလှောင်မှု
                    </th>

                    <td>
                        {{ $warehouse->name ?? 'သိုလှောင်ရုံ မသတ်မှတ်ရသေးပါ' }}
                        တွင် သိမ်းဆည်းထားရှိ
                    </td>

                </tr>

                <tr>

                    <th>
                        ငွေကြေးအခြေအနေ
                    </th>

                    <td>

                        @if($donationPayment->status === 'Completed')

                            အကောင့်သို့ ရောက်ရှိပြီး

                        @else

                            {{ $donationPayment->status }}

                        @endif

                    </td>

                </tr>

            </table>


            {{-- =================================================
                6. REMARKS
            ================================================== --}}

            <h5 class="fw-bold text-primary mt-4">
                ၆။ မှတ်ချက်/အကြံပြုချက်
                (Remarks/Suggestions)
            </h5>

            <ol class="lh-lg">

                <li>
                    <strong>ကျေးဇူးတင်စကား</strong> -
                    {{ $donor->name ?? 'လှူဒါန်းသူ' }}
                    အား ၎င်း၏ ရက်ရောသော လှူဒါန်းမှုအတွက်
                    အထူးကျေးဇူးတင်ရှိပါသည်။
                    ဤလှူဒါန်းမှုသည် ဘေးအန္တရာယ်ခံစားနေရသော
                    ပြည်သူများအတွက် အထူးအရေးပါသော
                    အထောက်အပံ့ဖြစ်ပါသည်။
                </li>

                <li>
                    <strong>ပစ္စည်းအရည်အသွေး</strong> -
                    လှူဒါန်းထားသော ပစ္စည်းများသည်
                    အရည်အသွေးကောင်းမွန်ပြီး
                    သတ်မှတ်စံချိန်စံညွှန်းများနှင့်
                    ကိုက်ညီပါသည်။
                </li>

                <li>
                    <strong>ငွေကြေးအသုံးပြုမှု</strong> -
                    လှူဒါန်းထားသော ငွေကြေးကို
                    ဘေးအန္တရာယ်ခံစားနေရသော ပြည်သူများအတွက်
                    လိုအပ်သော ပစ္စည်းများ ထပ်မံဝယ်ယူရာတွင်
                    အသုံးပြုသွားမည်ဖြစ်ပါသည်။
                </li>

                <li>
                    <strong>ဆက်လက်ဆောင်ရွက်ရန်</strong> -
                    လှူဒါန်းထားသော ပစ္စည်းများကို
                    လိုအပ်သည့်ဒေသများသို့ အမြန်ဆုံး
                    ဖြန့်ဝေနိုင်ရေး ဆောင်ရွက်သွားမည်ဖြစ်ပါသည်။
                </li>

                <li>
                    <strong>အစီရင်ခံတင်ပြခြင်း</strong> -
                    လှူဒါန်းသူအား ပစ္စည်းများ မည်သို့
                    အသုံးပြုခဲ့ကြောင်း အစီရင်ခံစာ
                    ပြန်လည်တင်ပြသွားမည်ဖြစ်ပါသည်။
                </li>

            </ol>


            {{-- =================================================
                7. CONCLUSION
            ================================================== --}}

            <h5 class="fw-bold text-primary mt-4">
                ၇။ နိဂုံး (Conclusion)
            </h5>

            <p class="lh-lg">

                စေတနာရှင်
                <strong>
                    {{ $donor->name ?? 'လှူဒါန်းသူ' }}
                </strong>
                မှ လှူဒါန်းခဲ့သော ပစ္စည်းများနှင့်
                ငွေကျပ်
                <strong>
                    {{ number_format(
                        $donationPayment->amount ?? 0,
                        0
                    ) }}
                </strong>
                တို့ကို စနစ်တွင် မှတ်တမ်းတင်ထားရှိပြီးဖြစ်ပါသည်။

                ဤလှူဒါန်းမှုများကို
                ဘေးအန္တရာယ်ခံစားနေရသော ပြည်သူများအတွက်
                ထိရောက်စွာ အသုံးပြုသွားမည်ဖြစ်ပါသည်။

            </p>


            {{-- =================================================
                SIGNATURE
            ================================================== --}}

            <div class="row mt-5 pt-4">

                <div class="col-md-6">

                    <h6 class="fw-bold">
                        ပြုစုသူ
                    </h6>

                    <br>


                    <p>
                        အမည်:
                        <strong>Akayie</strong>
                    </p>

                    <p>
                        ရာထူး:
                        စီမံခန့်ခွဲရေးမှူး (Admin)
                    </p>

                    <p>
                        ဌာန:
                        SmartWarehouse စီမံခန့်ခွဲရေးဌာန
                    </p>

                    <p>
                        ဖုန်း:
                        09950371675
                    </p>

                </div>


                <div class="col-md-6">

                    <h6 class="fw-bold">
                        အတည်ပြုသူ
                    </h6>

                    <br>


                    <p>
                        အမည်:
                        <strong>Yamin</strong>
                    </p>

                    <p>
                        ရာထူး:
                        သိုလှောင်ရုံမန်နေဂျာ
                    </p>

                    <p>
                        ဌာန:
                        {{ $warehouse->name ?? '-' }}
                    </p>

                    <p>
                        ဖုန်း:
                        09950371673
                    </p>

                </div>

            </div>


            {{-- =================================================
                ATTACHMENTS
            ================================================== --}}

            <div class="mt-5 pt-4 border-top">

                <h5 class="fw-bold text-primary">
                    📎 တွဲဖက်တင်ပြသည့် အချက်အလက်များ
                    (Attachments)
                </h5>

                <ol>

                    <li>

                        @if($donationPayment->proof)

                            <a
                                href="{{ asset(
                                    'storage/' . $donationPayment->proof
                                ) }}"
                                target="_blank">

                                ငွေလွှဲအထောက်အထား

                            </a>

                        @else

                            ငွေလွှဲအထောက်အထား မပါရှိပါ။

                        @endif

                    </li>

                    <li>
                        လှူဒါန်းမှုလက်ခံဖြတ်ပိုင်း
                    </li>

                </ol>

            </div>


            {{-- =================================================
                BOTTOM BUTTONS
            ================================================== --}}

            <div class="mt-5 pt-4 border-top text-center">

                <a
                    href="{{ route('backend.donation_payments.index') }}"
                    class="btn btn-secondary me-2">

                    <i class="fas fa-arrow-left me-1"></i>
                    စာရင်းသို့ ပြန်သွားရန်

                </a>

                <a
                    href="{{ route(
                        'backend.donation_payments.edit',
                        $donationPayment->id
                    ) }}"
                    class="btn btn-warning text-white me-2">

                    <i class="fas fa-edit me-1"></i>
                    ပြင်ဆင်ရန်

                </a>

                <a
                    href="{{ route(
                        'backend.donation_payments.pdf',
                        $donationPayment->id
                    ) }}"
                    target="_blank"
                    class="btn btn-danger">

                    <i class="fas fa-file-pdf me-1"></i>
                    PDF ထုတ်ရန်

                </a>

            </div>

        </div>

    </div>

</div>

@endsection
