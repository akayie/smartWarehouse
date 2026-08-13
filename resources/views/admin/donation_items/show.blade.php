@extends('layouts.admin')

@section('title', 'အလှူပစ္စည်းအသေးစိတ်')

@section('button')
<a href="{{ route('backend.donation_items.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i>
    အလှူပစ္စည်းစာရင်းသို့ ပြန်သွားရန်
</a>
@endsection

@section('content')

<div class="card shadow-sm border-0">

    {{-- Header --}}
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-1 fw-bold text-dark">
                <i class="fas fa-box-open me-2 text-primary"></i>
                အလှူပစ္စည်းအသေးစိတ်
            </h5>

            <small class="text-muted">
                အလှူပစ္စည်းမှတ်တမ်းနှင့် ပစ္စည်းပမာဏဆိုင်ရာ အသေးစိတ်အချက်အလက်များ
            </small>
        </div>

        <div>

            <a
                href="{{ route('backend.donation_items.edit', $donationItem->id) }}"
                class="btn btn-sm btn-warning text-white">

                <i class="fas fa-edit me-1"></i>
                ပြင်ရန်

            </a>

        </div>

    </div>

    {{-- Body --}}
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-striped table-bordered mb-0 align-middle">

                <tbody>

                    {{-- Donation ID --}}
                    <tr>

                        <th
                            width="240"
                            class="bg-light">

                            <i class="fas fa-file-alt me-2 text-primary"></i>
                            အလှူမှတ်တမ်းအမှတ်

                        </th>

                        <td>

                            <span class="badge bg-light text-dark border">

                                အလှူ #{{ $donationItem->donation_id }}

                            </span>

                        </td>

                    </tr>

                    {{-- Donor --}}
                    <tr>

                        <th class="bg-light">

                            <i class="fas fa-user me-2 text-primary"></i>
                            အလှူရှင်အမည်

                        </th>

                        <td>

                            <strong>

                                {{ $donationItem
                                    ->donation
                                    ->donor
                                    ->name
                                    ?? 'မသိရှိပါ'
                                }}

                            </strong>

                        </td>

                    </tr>

                    {{-- Warehouse --}}
                    <tr>

                        <th class="bg-light">

                            <i class="fas fa-warehouse me-2 text-primary"></i>
                            သိုလှောင်ရုံ

                        </th>

                        <td>

                            {{ $donationItem
                                ->donation
                                ->warehouse
                                ->name
                                ?? 'မသတ်မှတ်ရသေးပါ'
                            }}

                        </td>

                    </tr>

                    {{-- Item --}}
                    <tr>

                        <th class="bg-light">

                            <i class="fas fa-box me-2 text-primary"></i>
                            ပစ္စည်းအမည်

                        </th>

                        <td>

                            <strong class="text-primary">

                                {{ $donationItem
                                    ->item
                                    ->name
                                    ?? 'ပစ္စည်းအမည် မရှိပါ'
                                }}

                            </strong>

                        </td>

                    </tr>

                    {{-- Quantity --}}
                    <tr>

                        <th class="bg-light">

                            <i class="fas fa-cubes me-2 text-primary"></i>
                            အလှူပစ္စည်းပမာဏ

                        </th>

                        <td>

                            <span class="badge bg-success fs-6 px-3 py-2">

                                {{ number_format($donationItem->quantity) }}

                                {{ $donationItem->item?->unit ?? '' }}

                            </span>

                        </td>

                    </tr>

                    {{-- Donation Date --}}
                    <tr>

                        <th class="bg-light">

                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            အလှူလက်ခံသည့်နေ့

                        </th>

                        <td>

                            {{ $donationItem->donation->donation_date
                                ? $donationItem->donation->donation_date->format('d-m-Y')
                                : 'မသတ်မှတ်ရသေးပါ'
                            }}

                        </td>

                    </tr>

                    {{-- Created At --}}
                    <tr>

                        <th class="bg-light">

                            <i class="fas fa-clock me-2 text-primary"></i>
                            မှတ်တမ်းတင်သည့်အချိန်

                        </th>

                        <td>

                            {{ $donationItem->created_at
                                ? $donationItem->created_at->format('d-m-Y H:i:s')
                                : '-'
                            }}

                        </td>

                    </tr>

                    {{-- Updated At --}}
                    <tr>

                        <th class="bg-light">

                            <i class="fas fa-history me-2 text-primary"></i>
                            နောက်ဆုံးပြင်ဆင်သည့်အချိန်

                        </th>

                        <td>

                            {{ $donationItem->updated_at
                                ? $donationItem->updated_at->format('d-m-Y H:i:s')
                                : '-'
                            }}

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

    {{-- Footer Actions --}}
    <div class="card-footer bg-white py-3">

        <div class="d-flex justify-content-between align-items-center">

            <a
                href="{{ route('backend.donation_items.index') }}"
                class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                စာရင်းသို့ ပြန်သွားရန်

            </a>

            <a
                href="{{ route('backend.donation_items.edit', $donationItem->id) }}"
                class="btn btn-warning text-white">

                <i class="fas fa-edit me-1"></i>
                အလှူပစ္စည်းပြင်ရန်

            </a>

        </div>

    </div>

</div>

@endsection
