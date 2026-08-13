@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-hands-helping me-2 text-primary"></i>ကူညီထောက်ပံ့မှု တောင်းဆိုချက် အစီရင်ခံစာ
        </h2>
        <button onclick="window.print()" class="btn btn-secondary d-print-none">
            <i class="fas fa-print me-1"></i> ပရင့်ထုတ်မည် / PDF ရယူမည်
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-4 d-print-none">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.reports.relief-request') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">စတင်သည့် ရက်စွဲ</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">ပြီးဆုံးသည့် ရက်စွဲ</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">အခြေအနေ</label>
                    <select name="status" class="form-select">
                        <option value="">အခြေအနေ အားလုံး</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>စောင့်ဆိုင်းဆဲ</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>ခွင့်ပြုပြီး</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>ငြင်းပယ်ထားသည်</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i> ရှာဖွေမည်
                    </button>
                    <a href="{{ route('backend.reports.relief-request') }}" class="btn btn-outline-secondary">
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
                            <th style="width: 130px;">ရက်စွဲ</th>
                            <th>တောင်းဆိုသူ</th>
                            <th>ဘေးအန္တရာယ် ဖြစ်စဉ်</th>
                            <th>တောင်းဆိုထားသည့် ပစ္စည်းများ</th>
                            <th class="text-center" style="width: 150px;">အခြေအနေ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reliefRequests as $req)
                            <tr>
                                <td>{{ $req->created_at->format('d-M-Y') }}</td>
                                <td class="fw-bold">{{ $req->user->name ?? 'မရှိပါ' }}</td>
                                <td>{{ $req->disaster->name ?? 'အထွေထွေ ထောက်ပံ့မှု' }}</td>
                                <td>
                                    <ul class="mb-0 ps-3">
                                        @foreach($req->requestItems as $item)
                                            <li>
                                                {{ $item->item->name ?? 'ပစ္စည်း' }}
                                                <span class="text-muted">(အရေအတွက်: {{ $item->quantity }} {{ $item->item->unit ?? '' }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center">
                                    @php
                                        $status = strtolower($req->status ?? 'pending');
                                    @endphp

                                    @if($status === 'approved' || $status === 'ခွင့်ပြုပြီး')
                                        <span class="badge bg-success">ခွင့်ပြုပြီး</span>
                                    @elseif($status === 'rejected' || $status === 'ငြင်းပယ်ထားသည်')
                                        <span class="badge bg-danger">ငြင်းပယ်ထားသည်</span>
                                    @else
                                        <span class="badge bg-warning text-dark">စောင့်ဆိုင်းဆဲ</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    ကူညီထောက်ပံ့မှု တောင်းဆိုချက် မှတ်တမ်းများ မရှိသေးပါ။
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
