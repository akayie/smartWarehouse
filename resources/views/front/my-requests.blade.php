@extends('layouts.front')

@section('content')
<!-- PAGE 4: MY REQUESTS -->
<div id="pub-my-requests" class="sub-page py-4">
    <div class="container">
        <div class="card border-0 shadow-sm p-4">
            <div class="card-header-flex d-flex justify-content-between align-items-center mb-4 bg-white border-0 pb-0">
                <h2 class="fw-bold mb-0">
                    <i class="fa-solid fa-list-check text-primary me-2"></i> ကူညီကယ်ဆယ်ရေး တောင်းခံလွှာများ မှတ်တမ်း
                </h2>
                <a href="{{ route('public.request.create') }}" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> အကူအညီ တောင်းခံစာအသစ်
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" style="width: 100%; border-collapse: collapse;">
                    <thead class="table-light">
                        <tr>
                            <th>တောင်းခံလွှာ အမှတ်</th>
                            <th>သဘာဝဘေးအန္တရာယ် အမျိုးအစား</th>
                            <th>တည်နေရာ</th>
                            <th>တောင်းခံထားသော ပစ္စည်းများ</th>
                            <th>ရက်စွဲ</th>
                            <th>အခြေအနေ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr>
                                <td><strong>#REQ-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ optional($req->disaster)->name ?? 'N/A' }}</td>
                                <td>{{ $req->location }}</td>
                                <td>
                                    @if($req->requestItems->count() > 0)
                                        @foreach($req->requestItems as $reqItem)
                                            <span class="badge bg-info text-dark me-1 mb-1">
                                                {{ optional($reqItem->item)->name ?? 'ပစ္စည်း' }} ({{ $reqItem->quantity }})
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">{{ Str::limit($req->note, 30) ?? 'အထွေထွေ ကူညီကယ်ဆယ်ရေး' }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dateField = $req->request_date ?? $req->created_at;
                                    @endphp
                                    {{ $dateField ? \Carbon\Carbon::parse($dateField)->format('M d, Y') : '-' }}
                                </td>
                                <td>
                                    @if(in_array($req->status, ['Approved', 'Completed', 'Dispatched']))
                                        <span class="badge bg-success">{{ $req->status }}</span>
                                    @elseif(in_array($req->status, ['Pending', 'Processing']))
                                        <span class="badge bg-warning text-dark">{{ $req->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $req->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-2">အကူအညီတောင်းခံထားသော မှတ်တမ်းများ မရှိသေးပါ။</p>
                                    <a href="{{ route('public.request.create') }}" class="btn btn-primary btn-sm">
                                        အကူအညီတောင်းခံလွှာ အသစ်ပေးပို့ရန်
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($requests->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
