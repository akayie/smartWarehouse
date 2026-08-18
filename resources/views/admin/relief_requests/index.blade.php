@extends('layouts.admin')

@section('title', 'ကူညီထောက်ပံ့မှု တောင်းဆိုချက်များ')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">
            <i class="fas fa-hands-helping me-2 text-primary"></i>ကူညီထောက်ပံ့မှု တောင်းဆိုချက်များ စီမံခန့်ခွဲမှု
        </h5>
    </div>

    <div class="card-body">
        {{-- Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Alert --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px;" class="text-center">စဉ်</th>
                        <th>တောင်းဆိုသူ</th>
                        <th>ဘေးအန္တရာယ် ဖြစ်စဉ်</th>
                        <th class="text-center" style="width: 140px;">အခြေအနေ</th>
                        <th class="text-center" style="width: 220px;">ဆောင်ရွက်ချက်</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reliefRequests as $request)
                        <tr>
                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $request->requestedBy->name ?? $request->user->name ?? 'အများပြည်သူ' }}</td>
                            <td>{{ $request->disaster->name ?? $request->disaster->title ?? 'အထွေထွေ ထောက်ပံ့မှု' }}</td>
                            <td class="text-center">
                                @php
                                    $status = strtolower($request->status ?? '');
                                @endphp

                                @if($status === 'pending' || $status === 'စောင့်ဆိုင်းဆဲ')
                                    <span class="badge bg-warning text-dark">စောင့်ဆိုင်းဆဲ</span>
                                @elseif($status === 'approved' || $status === 'ခွင့်ပြုပြီး')
                                    <span class="badge bg-success">ခွင့်ပြုပြီး</span>
                                @elseif($status === 'rejected' || $status === 'ငြင်းပယ်ထားသည်')
                                    <span class="badge bg-danger">ငြင်းပယ်ထားသည်</span>
                                @else
                                    <span class="badge bg-secondary">{{ $request->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('backend.relief_requests.show', $request->id) }}" class="btn btn-sm btn-info text-white me-1" title="ကြည့်မည်">
                                    <i class="fas fa-eye me-1"></i> ကြည့်မည်
                                </a>

                                @if(strtolower($request->status) === 'pending')
                                    {{-- Approve Button --}}
                                    <form action="{{ route('backend.relief_requests.approve', $request->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ဤတောင်းဆိုချက်အား ခွင့်ပြုရန် သေချာပါသလား။')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success me-1" title="ခွင့်ပြုမည်">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    {{-- Reject Button --}}
                                    <form action="{{ route('backend.relief_requests.reject', $request->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ဤတောင်းဆိုချက်အား ငြင်းပယ်ရန် သေချာပါသလား။')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger" title="ငြင်းပယ်မည်">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
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

        {{-- Pagination --}}
        <div class="mt-3 d-flex justify-content-end">
            {{ $reliefRequests->links() }}
        </div>
    </div>
</div>
@endsection
