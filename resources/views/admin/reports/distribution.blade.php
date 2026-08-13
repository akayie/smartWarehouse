@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-dark mb-0">ပစ္စည်းဖြန့်ဝေမှု အစီရင်ခံစာ</h2>
        <button onclick="window.print()" class="btn btn-secondary d-print-none">
            <i class="fas fa-print me-1"></i> ပရင့်ထုတ်မည် / PDF ရယူမည်
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-4 d-print-none">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.reports.distribution') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">စတင်သည့် ရက်စွဲ</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">ပြီးဆုံးသည့် ရက်စွဲ</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">ကုန်လှောင်ရုံ / စခန်း</label>
                    <select name="warehouse_id" class="form-select">
                        <option value="">ကုန်လှောင်ရုံ/စခန်း အားလုံး</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i> ရှာဖွေမည်
                    </button>
                    <a href="{{ route('backend.reports.distribution') }}" class="btn btn-outline-secondary">
                        ပြန်စတင်မည်
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Report Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 130px;">ရက်စွဲ</th>
                            <th>ဖြန့်ဝေမှု အမှတ်စဉ်</th>
                            <th>ကုန်လှောင်ရုံ / စခန်း</th>
                            <th>ဖြန့်ဝေခဲ့သည့် ပစ္စည်းများ</th>
                            <th class="text-center" style="width: 120px;">အခြေအနေ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distributions as $dist)
                            <tr>
                                <td>{{ $dist->created_at->format('d-M-Y') }}</td>
                                <td class="fw-bold">{{ $dist->code ?? 'DIS-'.str_pad($dist->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $dist->warehouse->name ?? 'မရှိပါ' }}</td>
                                <td>
                                    <ul class="mb-0 ps-3">
                                        @foreach($dist->distributionItems as $dItem)
                                            <li>
                                                {{ $dItem->item->name ?? 'ပစ္စည်း' }}
                                                <span class="text-muted">(အရေအတွက်: {{ $dItem->quantity }} {{ $dItem->item->unit ?? '' }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center">
                                    @php
                                        $status = strtolower($dist->status ?? 'completed');
                                    @endphp

                                    @if($status === 'completed' || $status === 'ပြီးစီး')
                                        <span class="badge bg-success">ပြီးစီးပါပြီ</span>
                                    @elseif($status === 'pending' || $status === 'စောင့်ဆိုင်းဆဲ')
                                        <span class="badge bg-warning text-dark">စောင့်ဆိုင်းဆဲ</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($dist->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    ပစ္စည်းဖြန့်ဝေမှု မှတ်တမ်းများ မရှိသေးပါ။
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .d-print-none, sidebar, navbar, footer, .main-header, .main-sidebar {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    body {
        background-color: #fff !important;
    }
}
</style>
@endsection
