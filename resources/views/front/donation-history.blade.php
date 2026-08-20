@extends('layouts.front')

@section('title', 'လှူဒါန်းမှု မှတ်တမ်း - Smart Relief')

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-2">
                        <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>လှူဒါန်းမှု မှတ်တမ်း
                    </h2>
                    <p class="text-muted mb-0">
                        စနစ်အတွင်း ပြုလုပ်ထားသော လှူဒါန်းမှုများ၏ အခြေအနေနှင့် အသေးစိတ်မှတ်တမ်းများ
                    </p>
                </div>
                <a href="{{ route('public.donate.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-heart me-1"></i> လှူဒါန်းရန်
                </a>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body p-4">

            {{-- ALERTS --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>လှူဒါန်းမှုအမှတ်</th>
                            <th>လှူဒါန်းသူ</th>
                            <th>ရက်စွဲ</th>
                            <th>အမျိုးအစား</th>
                            <th>အသေးစိတ် / ငွေပေးချေမှု</th>
                            <th>လက်ခံမည့် Warehouse</th>
                            <th>ပမာဏ / အရေအတွက်</th>
                            <th>အခြေအနေ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                            <tr>
                                {{-- DONATION ID --}}
                                <td>
                                    <strong>#DON-{{ str_pad($donation->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                </td>

                                {{-- DONOR NAME --}}
                                <td>
                                    <div class="fw-bold text-dark">
                                        <i class="fa-solid fa-user-circle me-1 text-secondary"></i>
                                        @php
                                            $donor = $donation->donor;
                                            $donorName = optional($donor)->name;

                                            // အကယ်၍ အမည်မရှိပါက သို့မဟုတ် Guest အီးမေးလ်ပုံစံဖြစ်နေပါက "အများပြည်သူ (Guest)" ဟု ပြရန်
                                            if (!$donorName || str_starts_with($donorName, 'guest_') || str_contains(optional($donor)->email, '@relief.local')) {
                                                // Controller တွင် သိမ်းဆည်းခဲ့သော နာမည်ကို စစ်ဆေးသည်၊ မရှိလျှင် အများပြည်သူ (Guest) ဟုပြမည်
                                                $displayDonorName = $donorName ?: 'အများပြည်သူ (Guest)';
                                            } else {
                                                $displayDonorName = $donorName;
                                            }
                                        @endphp
                                        {{ $displayDonorName }}
                                    </div>
                                    @php
                                        $phone = optional($donor)->phone;
                                    @endphp
                                    @if($phone)
                                        <div class="small text-muted">{{ $phone }}</div>
                                    @endif
                                </td>

                                {{-- DATE --}}
                                <td>
                                    {{ $donation->donation_date ? \Carbon\Carbon::parse($donation->donation_date)->format('Y-m-d') : '-' }}
                                </td>

                                {{-- TYPE --}}
                                <td>
                                    @switch($donation->donation_type)
                                        @case('Cash')
                                            <span class="badge bg-success">ငွေသား</span>
                                            @break
                                        @case('Item')
                                            <span class="badge bg-info text-white">ပစ္စည်း</span>
                                            @break
                                        @case('Both')
                                            <span class="badge text-white" style="background-color: #6f42c1;">ငွေသား + ပစ္စည်း</span>
                                            @break
                                        @case('Food')
                                            <span class="badge bg-warning text-dark">စားနပ်ရိက္ခာ</span>
                                            @break
                                        @case('Water')
                                            <span class="badge bg-info">သောက်ရေ</span>
                                            @break
                                        @case('Clothing')
                                            <span class="badge bg-secondary">အဝတ်အထည်</span>
                                            @break
                                        @case('Medical')
                                            <span class="badge bg-danger">ကျန်းမာရေး</span>
                                            @break
                                        @case('Shelter')
                                            <span class="badge bg-primary">ခိုလှုံရေး</span>
                                            @break
                                        @case('Hygiene')
                                            <span class="badge bg-info">သန့်ရှင်းရေး</span>
                                            @break
                                        @case('Rescue Equipment')
                                            <span class="badge bg-dark">ကယ်ဆယ်ရေးပစ္စည်း</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $donation->donation_type ?? '-' }}</span>
                                    @endswitch
                                </td>

                                {{-- DETAILS --}}
                                <td>
                                    {{-- Cash Payment Information --}}
                                    @if(in_array($donation->donation_type, ['Cash', 'Both']))
                                        @if(optional($donation->payment)->amount > 0)
                                            <div class="small fw-bold text-dark">
                                                <i class="fa-solid fa-credit-card me-1 text-primary"></i>
                                                {{ $donation->payment->payment_method ?? 'N/A' }}
                                            </div>
                                            @if($donation->payment->transaction_reference)
                                                <div class="small text-muted ms-3">
                                                    Ref: {{ $donation->payment->transaction_reference }}
                                                </div>
                                            @endif
                                        @endif
                                    @endif

                                    {{-- Separator if Both types are present --}}
                                    @if($donation->donation_type === 'Both' && optional($donation->payment)->amount > 0 && $donation->donationItems->isNotEmpty())
                                        <hr class="my-1 border-secondary opacity-25">
                                    @endif

                                    {{-- Item Information --}}
                                    @if(!in_array($donation->donation_type, ['Cash']))
                                        @forelse($donation->donationItems as $donatedItem)
                                            <div class="small">
                                                <i class="fa-solid fa-box me-1 text-muted"></i>
                                                {{ optional($donatedItem->item)->name ?? 'ပစ္စည်း' }}
                                                <span class="text-secondary">({{ $donatedItem->quantity }} {{ $donatedItem->unit ?? '' }})</span>
                                            </div>
                                        @empty
                                            <span class="text-muted small">ပစ္စည်းမှတ်တမ်း မရှိပါ</span>
                                        @endforelse
                                    @endif
                                </td>

                                {{-- WAREHOUSE --}}
                                <td>
                                    @if($donation->warehouse)
                                        <div class="fw-bold">{{ $donation->warehouse->name }}</div>
                                        @if($donation->warehouse->location)
                                            <div class="small text-muted">{{ $donation->warehouse->location }}</div>
                                        @endif
                                    @else
                                        <span class="text-muted small">Warehouse မသတ်မှတ်ရသေးပါ</span>
                                    @endif
                                </td>

                                {{-- AMOUNT / QUANTITY --}}
                                <td>
                                    {{-- Cash Amount --}}
                                    @if(in_array($donation->donation_type, ['Cash', 'Both']))
                                        @if(optional($donation->payment)->amount > 0)
                                            <div class="text-success fw-bold">
                                                <i class="fa-solid fa-money-bill-wave me-1"></i>
                                                {{ number_format($donation->payment->amount) }} ကျပ်
                                            </div>
                                        @elseif($donation->donation_type === 'Cash')
                                            <div class="text-muted small">0 ကျပ်</div>
                                        @endif
                                    @endif

                                    {{-- Item Total Quantity --}}
                                    @if(!in_array($donation->donation_type, ['Cash']) && $donation->donationItems->isNotEmpty())
                                        <div class="fw-bold text-dark mt-1">
                                            <i class="fa-solid fa-cubes me-1 text-muted"></i>
                                            {{ $donation->donationItems->sum('quantity') }} ခု/ခုရေ
                                        </div>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @php
                                        $statusClasses = [
                                            'Pending'     => 'bg-warning text-dark',
                                            'Verified'    => 'bg-info text-white',
                                            'Approved'    => 'bg-primary',
                                            'Received'    => 'bg-info',
                                            'Distributed' => 'bg-primary',
                                            'Completed'   => 'bg-success',
                                            'Rejected'    => 'bg-danger',
                                            'Cancelled'   => 'bg-secondary',
                                        ];

                                        $statusLabels = [
                                            'Pending'     => 'စိစစ်ဆဲ',
                                            'Verified'    => 'စိစစ်ပြီး',
                                            'Approved'    => 'အတည်ပြုပြီး',
                                            'Received'    => 'လက်ခံရရှိ',
                                            'Distributed' => 'ဖြန့်ဝေပြီး',
                                            'Completed'   => 'ပြီးမြောက်',
                                            'Rejected'    => 'ငြင်းပယ်ထား',
                                            'Cancelled'   => 'ပယ်ဖျက်ထား',
                                        ];

                                        $badgeClass = $statusClasses[$donation->status] ?? 'bg-secondary';
                                        $statusLabel = $statusLabels[$donation->status] ?? ($donation->status ?? 'Unknown');
                                    @endphp

                                    <span class="badge {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                                    <h5>လှူဒါန်းမှု မှတ်တမ်း မရှိသေးပါ။</h5>
                                    <p class="text-muted">သင်၏ ပထမဆုံး လှူဒါန်းမှုကို ယခုစတင်နိုင်ပါသည်။</p>
                                    <a href="{{ route('public.donate.create') }}" class="btn btn-primary">
                                        <i class="fa-solid fa-heart me-1"></i> ပထမဆုံး လှူဒါန်းရန်
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($donations->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $donations->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
