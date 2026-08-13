@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>လက်ကျန်နည်းပါးမှု သတိပေးချက် အစီရင်ခံစာ
        </h2>
        <button onclick="window.print()" class="btn btn-secondary d-print-none">
            <i class="fas fa-print me-1"></i> ပရင့်ထုတ်မည် / PDF ရယူမည်
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-4 d-print-none">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.reports.low-stock') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">အနည်းဆုံး ရှိရမည့် ပစ္စည်းပမာဏ (<=)</label>
                    <input type="number" name="threshold" class="form-control" value="{{ $threshold }}" min="1">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i> စစ်ဆေးမည်
                    </button>
                    <a href="{{ route('backend.reports.low-stock') }}" class="btn btn-outline-secondary">
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
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>ပစ္စည်းအမည်</th>
                            <th>အမျိုးအစား</th>
                            <th>ကုန်လှောင်ရုံ / စခန်း</th>
                            <th class="text-end" style="width: 180px;">လက်ရှိကျန်ရှိသော အရေအတွက်</th>
                            <th class="text-center" style="width: 160px;">အခြေအနေ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStocks as $index => $stock)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $stock->item->name ?? 'မရှိပါ' }}</td>
                                <td>{{ $stock->item->category->name ?? 'မရှိပါ' }}</td>
                                <td>{{ $stock->warehouse->name ?? 'မရှိပါ' }}</td>
                                <td class="text-end fw-bold text-danger">
                                    {{ number_format($stock->quantity) }} {{ $stock->item->unit ?? '' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">လက်ကျန် နည်းနေပါသည်</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-success py-4">
                                    <i class="fas fa-check-circle me-1"></i> ပစ္စည်းအားလုံး၏ လက်ကျန်ပမာဏ လုံလောက်စွာ ရှိနေပါသည်။ (သတ်မှတ်ချက် {{ $threshold }} ထက် ပိုပါသည်။)
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
