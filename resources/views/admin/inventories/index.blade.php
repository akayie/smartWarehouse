@extends('layouts.admin')

@section('title', 'လက်ကျန်ပစ္စည်းစာရင်း')

@section('content')
<div class="container-fluid py-3">

    {{-- ရှာဖွေခြင်းနှင့် စစ်ထုတ်ခြင်း --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.inventories.index') }}" class="row g-2 align-items-center">

                <div class="col-md-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="ပစ္စည်းအမည် သို့မဟုတ် ဘားကုဒ်ဖြင့် ရှာဖွေပါ..."
                    >
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- သက်တမ်းအခြေအနေ အားလုံး --</option>
                        <option value="expiring_soon" {{ request('status') == 'expiring_soon' ? 'selected' : '' }}>
                            ရက် ၃၀ အတွင်း သက်တမ်းကုန်ဆုံးမည့်ပစ္စည်းများ
                        </option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                            သက်တမ်းကုန်ဆုံးပြီး
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> ရှာဖွေရန်
                    </button>
                </div>

                <div class="col-md-3 text-end">
                    <a href="{{ route('backend.scan') }}" class="btn btn-success">
                        <i class="fas fa-qrcode me-1"></i> Stock-In စကင်ဖတ်ရန်
                    </a>
                </div>

            </form>
        </div>
    </div>


    {{-- Inventory Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fas fa-boxes me-2"></i> လက်ကျန်ပစ္စည်းစာရင်း
                <small class="text-muted">(အသုတ်အလိုက် သက်တမ်းစစ်ဆေးမှု)</small>
            </h5>

            <a href="{{ route('backend.items.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> ပစ္စည်းအသစ် ထည့်သွင်းရန်
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">စဉ်</th>
                            {{-- <th>ဘားကုဒ်</th> --}}
                            <th>ပစ္စည်းအမည်</th>
                            <th>အမျိုးအစား</th>
                            <th>သိုလှောင်ရုံ</th>
                            <th>အရေအတွက်</th>
                            <th>သက်တမ်းကုန်ဆုံးရက်</th>
                            <th>အခြေအနေ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($inventories as $index => $inventory)
                            @php
                                $item = $inventory->item;

                                $expiryDate = $inventory->expiry_date
                                    ? \Carbon\Carbon::parse($inventory->expiry_date)
                                    : null;

                                $isExpired = $expiryDate ? $expiryDate->isPast() : false;

                                // ရက်စွဲစစ်ဆေးမှု ပြင်ဆင်ချက်
                                $isExpiringSoon = $expiryDate
                                    ? (!$isExpired && $expiryDate->diffInDays(now()) <= 30)
                                    : false;
                            @endphp

                            <tr>
                                {{-- No --}}
                                <td class="ps-3">
                                    {{ $inventories->firstItem() + $index }}
                                </td>

                                {{-- Barcode
                                <td>
                                    <code>{{ $item->barcode ?? 'N/A' }}</code>
                                </td> --}}

                                {{-- Item Name --}}
                                <td class="fw-bold">
                                    {{ $item->name ?? 'ပစ္စည်းမသိရှိပါ' }}
                                </td>

                                {{-- Category --}}
                                <td>
                                    {{ $item?->category?->name ?? 'N/A' }}
                                </td>

                                {{-- Warehouse --}}
                                <td>
                                    {{ $inventory->warehouse?->name ?? 'N/A' }}
                                </td>

                                {{-- Quantity --}}
                                <td>
                                    <span class="badge bg-info text-dark fs-6">
                                        {{ $inventory->quantity }} {{ $item->unit ?? '' }}
                                    </span>
                                </td>

                                {{-- Expiry Date --}}
                                <td>
                                    @if($expiryDate)
                                        <span class="badge {{ $isExpired ? 'bg-danger' : ($isExpiringSoon ? 'bg-warning text-dark' : 'bg-light text-dark border') }}">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $expiryDate->format('Y-m-d') }}
                                        </span>
                                    @else
                                        <span class="text-muted">သက်တမ်းသတ်မှတ်ထားခြင်းမရှိပါ</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($isExpired)
                                        <span class="badge bg-danger">သက်တမ်းကုန်ဆုံးပြီး</span>
                                    @elseif($isExpiringSoon)
                                        <span class="badge bg-warning text-dark">သက်တမ်းကုန်ဆုံးရန် နီးကပ်နေသည်</span>
                                    @else
                                        <span class="badge bg-success">ကောင်းမွန်သည်</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    လက်ကျန်ပစ္စည်းစာရင်း မရှိသေးပါ။<br>
                                    <small>ပစ္စည်းလက်ခံသိုလှောင်ခြင်း (Stock-In) ကို အရင်ပြုလုပ်ပေးပါ။</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination (Filter Parameter များ ပျောက်မသွားစေရန် appends ထည့်သွင်းထားသည်) --}}
        @if($inventories->hasPages())
            <div class="card-footer bg-white">
                {{ $inventories->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
