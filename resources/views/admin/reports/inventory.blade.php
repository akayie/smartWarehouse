@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-boxes me-2 text-primary"></i>ကုန်ပစ္စည်း လက်ကျန် အစီရင်ခံစာ
        </h2>
        <button onclick="window.print()" class="btn btn-secondary d-print-none">
            <i class="fas fa-print me-1"></i> ပရင့်ထုတ်မည် / PDF ရယူမည်
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-4 d-print-none">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.reports.inventory') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">ကုန်လှောင်ရုံ / စခန်း</label>
                    <select name="warehouse_id" class="form-select">
                        <option value="">ကုန်လှောင်ရုံ အားလုံး</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i> ရှာဖွေမည်
                    </button>
                    <a href="{{ route('backend.reports.inventory') }}" class="btn btn-outline-secondary">
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
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>ပစ္စည်းအမည်</th>
                            <th>အမျိုးအစား</th>
                            <th>ကုန်လှောင်ရုံ / စခန်း</th>
                            <th class="text-end" style="width: 200px;">လက်ရှိကျန်ရှိသော အရေအတွက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventories as $index => $inv)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $inv->item->name ?? 'မရှိပါ' }}</td>
                                <td>{{ $inv->item->category->name ?? 'မရှိပါ' }}</td>
                                <td>{{ $inv->warehouse->name ?? 'မရှိပါ' }}</td>
                                <td class="text-end fw-bold text-primary">
                                    {{ number_format($inv->quantity) }} {{ $inv->item->unit ?? '' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    ကုန်ပစ္စည်း လက်ကျန် မှတ်တမ်းများ မရှိသေးပါ။
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
