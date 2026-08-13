@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-exchange-alt me-2 text-primary"></i>ပစ္စည်း အဝင်/အထွက် စစ်ဆေးမှု အစီရင်ခံစာ
        </h2>
        <button onclick="window.print()" class="btn btn-secondary d-print-none">
            <i class="fas fa-print me-1"></i> ပရင့်ထုတ်မည် / PDF ရယူမည်
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-4 d-print-none">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.reports.stock-movement') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">စတင်သည့် ရက်စွဲ</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">ပြီးဆုံးသည့် ရက်စွဲ</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">အမျိုးအစား</label>
                    <select name="type" class="form-select">
                        <option value="">အမျိုးအစား အားလုံး</option>
                        <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>အဝင် (Stock IN)</option>
                        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>အထွက် (Stock OUT)</option>
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>ပြင်ဆင်ညှိနှိုင်းမှု (Adjustment)</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i> ရှာဖွေမည်
                    </button>
                    <a href="{{ route('backend.reports.stock-movement') }}" class="btn btn-outline-secondary">
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
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 170px;">ရက်စွဲနှင့် အချိန်</th>
                            <th>ပစ္စည်းအမည်</th>
                            <th>ကုန်လှောင်ရုံ / စခန်း</th>
                            <th class="text-center" style="width: 140px;">အမျိုးအစား</th>
                            <th class="text-end" style="width: 150px;">အရေအတွက်</th>
                            <th>ဆောင်ရွက်သူ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $m)
                            <tr>
                                <td>{{ $m->created_at->format('d-M-Y h:i A') }}</td>
                                <td class="fw-bold">{{ $m->item->name ?? 'မရှိပါ' }}</td>
                                <td>{{ $m->warehouse->name ?? 'မရှိပါ' }}</td>
                                <td class="text-center">
                                    @php
                                        $type = strtolower($m->type ?? '');
                                    @endphp

                                    @if($type === 'in' || $type === 'အဝင်')
                                        <span class="badge bg-success">အဝင်</span>
                                    @elseif($type === 'out' || $type === 'အထွက်')
                                        <span class="badge bg-danger">အထွက်</span>
                                    @else
                                        <span class="badge bg-warning text-dark">ပြင်ဆင်ညှိနှိုင်းမှု</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($m->quantity) }} {{ $m->item->unit ?? '' }}
                                </td>
                                <td>{{ $m->user->name ?? 'စနစ် (System)' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    ပစ္စည်း အဝင်/အထွက် မှတ်တမ်းများ မရှိသေးပါ။
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
