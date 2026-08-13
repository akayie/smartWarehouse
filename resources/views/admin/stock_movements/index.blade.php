@extends('layouts.admin')

@section('title', 'စတော့ အဝင်/အထွက် မှတ်တမ်းများ')

@section('content')
<div class="container-fluid py-4">

    {{-- Title & Top Action Buttons --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-dark mb-0">စတော့ အဝင်/အထွက် မှတ်တမ်းများ</h3>
        <div class="d-flex gap-2">
            {{-- QR/Barcode Scanning --}}
            <a href="{{ route('backend.scan') }}" class="btn btn-primary">
                <i class="fa-solid fa-qrcode me-1"></i> QR / Barcode ဖတ်ရန်
            </a>

            {{-- Manual Movement Creation --}}
            <a href="{{ route('backend.stock-movements.create') }}" class="btn btn-success">
                <i class="fa-solid fa-plus me-1"></i> စတော့ အဝင်/အထွက် စာရင်းသွင်းရန်
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Form --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.stock-movements.index') }}" class="row g-3">
                {{-- Filter by Item --}}
                <div class="col-md-2">
                    <select name="item_id" class="form-select">
                        <option value="">-- ပစ္စည်းအားလုံး --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter by Warehouse --}}
                <div class="col-md-2">
                    <select name="warehouse_id" class="form-select">
                        <option value="">-- ကုန်လှောင်ရုံအားလုံး --</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter by Type --}}
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">-- အမျိုးအစားအားလုံး --</option>
                        <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>အဝင် (IN)</option>
                        <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>အထွက် (OUT)</option>
                        <option value="TRANSFER" {{ request('type') == 'TRANSFER' ? 'selected' : '' }}>လွှဲပြောင်း (TRANSFER)</option>
                    </select>
                </div>

                {{-- Date Range Filters --}}
                <div class="col-md-2">
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" placeholder="စတင်သည့်ရက်">
                </div>

                <div class="col-md-2">
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" placeholder="ပြီးဆုံးသည့်ရက်">
                </div>

                {{-- Action Buttons --}}
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> စစ်ထုတ်မည်
                    </button>
                    <a href="{{ route('backend.stock-movements.index') }}" class="btn btn-outline-secondary">
                        ပြန်စတင်မည်
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Movements Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" width="60">စဉ်</th>
                            <th>ရက်စွဲနှင့် အချိန်</th>
                            <th>ပစ္စည်းအမည်</th>
                            <th>ကုန်လှောင်ရုံ</th>
                            <th>အမျိုးအစား</th>
                            <th>အရေအတွက်</th>
                            <th>အကိုးအကား</th>
                            <th>စာရင်းသွင်းသူ</th>
                            <th class="text-end pe-3" width="120">လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockMovements as $movement)
                            <tr>
                                <td class="ps-3 fw-bold">
                                    {{ $loop->iteration + ($stockMovements->currentPage() - 1) * $stockMovements->perPage() }}
                                </td>
                                <td>{{ $movement->created_at->format('Y-m-d h:i A') }}</td>
                                <td class="fw-bold">{{ $movement->item->name ?? '-' }}</td>
                                <td>{{ $movement->warehouse->name ?? '-' }}</td>
                                <td>
                                    @if($movement->type === 'IN')
                                        <span class="badge bg-success">အဝင်</span>
                                    @elseif($movement->type === 'OUT')
                                        <span class="badge bg-danger">အထွက်</span>
                                    @else
                                        <span class="badge bg-info text-dark">လွှဲပြောင်း</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $movement->type === 'IN' ? 'text-success' : 'text-danger' }}">
                                        {{ $movement->type === 'IN' ? '+' : '-' }}{{ $movement->quantity }}
                                    </strong>
                                </td>
                                <td>{{ $movement->reference ?? '-' }}</td>
                                <td>{{ $movement->creator->name ?? 'စနစ်မှ' }}</td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('backend.stock-movements.show', $movement->id) }}"
                                           class="btn btn-outline-primary"
                                           title="အသေးစိတ်ကြည့်ရန်">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <form action="{{ route('backend.stock-movements.destroy', $movement->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('ဤစတော့ အဝင်/အထွက် မှတ်တမ်းကို ဖျက်ရန် သေချာပါသလား? ဤလုပ်ဆောင်ချက်သည် စတော့လက်ကျန် အရေအတွက်ကို မူလအတိုင်း ပြန်လည်ပြင်ဆင်သွားပါမည်။');"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    title="ဖျက်မည် (စတော့လက်ကျန် ပြန်ပြင်မည်)">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>
                                    စတော့ အဝင်/အထွက် မှတ်တမ်းများ မရှိသေးပါ။
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if($stockMovements->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $stockMovements->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
