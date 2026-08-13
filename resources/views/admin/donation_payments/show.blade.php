@extends('layouts.admin')

@section('title')
    အလှူငွေပေးချေမှု အသေးစိတ်
@endsection

@section('button')

<a
    href="{{ route('backend.donation_payments.index') }}"
    class="btn btn-secondary">

    <i class="fas fa-arrow-left me-1"></i>
    နောက်သို့

</a>

@endsection

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h4 class="mb-0 fw-bold text-primary">

            <i class="fas fa-money-check-alt me-2"></i>
            အလှူငွေပေးချေမှု အသေးစိတ်

        </h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered align-middle">

            {{-- Payment ID --}}
            <tr>

                <th width="220" class="bg-light">
                    ငွေပေးချေမှု အမှတ်
                </th>

                <td>
                    <span class="badge bg-primary">
                        #{{ $donationPayment->id }}
                    </span>
                </td>

            </tr>

            {{-- Donation Money ID --}}
            <tr>

                <th class="bg-light">
                    အလှူငွေမှတ်တမ်း အမှတ်
                </th>

                <td>
                    #{{ $donationPayment->donation_money_id }}
                </td>

            </tr>

            {{-- Donor --}}
            <tr>

                <th class="bg-light">
                    အလှူရှင်အမည်
                </th>

                <td>

                    <strong>
                        {{ $donationPayment
                            ->donationMoney
                            ->donation
                            ->donor
                            ->name ?? 'မသိရှိပါ' }}
                    </strong>

                </td>

            </tr>

            {{-- Payment Method --}}
            <tr>

                <th class="bg-light">
                    ငွေပေးချေမှုနည်းလမ်း
                </th>

                <td>

                    @if($donationPayment->payment_method)

                        <span class="badge bg-info text-dark">
                            {{ $donationPayment->payment_method }}
                        </span>

                    @else

                        -

                    @endif

                </td>

            </tr>

            {{-- Transaction Reference --}}
            <tr>

                <th class="bg-light">
                    ငွေလွှဲမှတ်တမ်းအမှတ်
                </th>

                <td>
                    {{ $donationPayment->transaction_reference ?? '-' }}
                </td>

            </tr>

            {{-- Payment Date --}}
            <tr>

                <th class="bg-light">
                    ငွေပေးချေသည့်နေ့
                </th>

                <td>

                    {{ $donationPayment->payment_date
                        ? $donationPayment->payment_date->format('d-m-Y')
                        : '-' }}

                </td>

            </tr>

            {{-- Account Name --}}
            <tr>

                <th class="bg-light">
                    အကောင့်အမည်
                </th>

                <td>
                    {{ $donationPayment->account_name ?? '-' }}
                </td>

            </tr>

            {{-- Account Number --}}
            <tr>

                <th class="bg-light">
                    အကောင့်နံပါတ်
                </th>

                <td>
                    {{ $donationPayment->account_number ?? '-' }}
                </td>

            </tr>

            {{-- Amount --}}
            <tr>

                <th class="bg-light">
                    အလှူငွေပမာဏ
                </th>

                <td>

                    <strong class="text-success fs-5">

                        {{ number_format(
                            $donationPayment->amount,
                            2
                        ) }}

                        {{ $donationPayment
                            ->donationMoney
                            ->currency ?? '' }}

                    </strong>

                </td>

            </tr>

            {{-- Payment Proof --}}
            <tr>

                <th class="bg-light">
                    ငွေပေးချေမှု အထောက်အထား
                </th>

                <td>

                    @if($donationPayment->proof)

                        <a
                            href="{{ asset(
                                'storage/' . $donationPayment->proof
                            ) }}"
                            target="_blank"
                            class="btn btn-sm btn-info">

                            <i class="fas fa-file-image me-1"></i>
                            အထောက်အထားကြည့်ရန်

                        </a>

                    @else

                        <span class="text-muted">
                            အထောက်အထား မတင်ထားပါ။
                        </span>

                    @endif

                </td>

            </tr>

            {{-- Status --}}
            <tr>

                <th class="bg-light">
                    ငွေပေးချေမှု အခြေအနေ
                </th>

                <td>

                    @if($donationPayment->status === 'Completed')

                        <span class="badge bg-success px-3 py-2">

                            <i class="fas fa-check-circle me-1"></i>
                            ပြီးမြောက်ပြီး

                        </span>

                    @elseif($donationPayment->status === 'Pending')

                        <span class="badge bg-warning text-dark px-3 py-2">

                            <i class="fas fa-clock me-1"></i>
                            စောင့်ဆိုင်းဆဲ

                        </span>

                    @elseif($donationPayment->status === 'Failed')

                        <span class="badge bg-danger px-3 py-2">

                            <i class="fas fa-times-circle me-1"></i>
                            မအောင်မြင်ပါ

                        </span>

                    @else

                        <span class="badge bg-secondary px-3 py-2">

                            <i class="fas fa-ban me-1"></i>
                            ပယ်ဖျက်ပြီး

                        </span>

                    @endif

                </td>

            </tr>

            {{-- Note --}}
            <tr>

                <th class="bg-light">
                    မှတ်ချက်
                </th>

                <td>
                    {{ $donationPayment->note ?? 'မှတ်ချက်မရှိပါ။' }}
                </td>

            </tr>

            {{-- Created At --}}
            <tr>

                <th class="bg-light">
                    မှတ်တမ်းတင်သည့်အချိန်
                </th>

                <td>

                    {{ $donationPayment->created_at
                        ? $donationPayment->created_at->format('d-m-Y H:i:s')
                        : '-' }}

                </td>

            </tr>

            {{-- Updated At --}}
            <tr>

                <th class="bg-light">
                    နောက်ဆုံးပြင်ဆင်သည့်အချိန်
                </th>

                <td>

                    {{ $donationPayment->updated_at
                        ? $donationPayment->updated_at->format('d-m-Y H:i:s')
                        : '-' }}

                </td>

            </tr>

        </table>

        {{-- Bottom Action --}}
        <div class="mt-4 pt-3 border-top">

            <a
                href="{{ route('backend.donation_payments.index') }}"
                class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                အလှူငွေပေးချေမှုစာရင်းသို့ ပြန်သွားရန်

            </a>

            <a
                href="{{ route('backend.donation_payments.edit', $donationPayment->id) }}"
                class="btn btn-warning text-white">

                <i class="fas fa-edit me-1"></i>
                ပြင်ဆင်ရန်

            </a>

        </div>

    </div>

</div>

@endsection
