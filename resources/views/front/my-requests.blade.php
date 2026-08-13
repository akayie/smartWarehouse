@extends('layouts.front') {{-- သင့် Project ၏ Layout ဖိုင်အမည် ပေါင်းစပ်ပါ --}}

@section('content')
<!-- PAGE 4: MY REQUESTS -->
<div id="pub-my-requests" class="sub-page">
    <div class="card">
        <div class="card-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2><i class="fa-solid fa-list-check icon-blue"></i> ကျွန်ုပ်၏ ကူညီကယ်ဆယ်ရေး တောင်းခံလွှာများ</h2>
            <a href="{{ route('public.request.create') }}" class="btn btn-sm btn-danger">+ အကူအညီ တောင်းခံစာအသစ်</a>
        </div>

        <div class="table-responsive">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
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
                            <td>{{ $req->disaster->name ?? 'N/A' }}</td>
                            <td>{{ $req->location }}</td>
                            <td>
                                @if($req->requestItems->count() > 0)
                                    @foreach($req->requestItems as $reqItem)
                                        <span class="badge badge-info">
                                            {{ $reqItem->item->name ?? 'ပစ္စည်း' }} ({{ $reqItem->quantity }})
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">{{ Str::limit($req->note, 30) ?? 'အထွေထွေ ကူညီကယ်ဆယ်ရေး' }}</span>
                                @endif
                            </td>
                            <td>{{ $req->request_date ? $req->request_date->format('M d, Y') : $req->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($req->status === 'Approved' || $req->status === 'Completed' || $req->status === 'Dispatched')
                                    <span class="badge badge-success">{{ $req->status }}</span>
                                @elseif($req->status === 'Pending' || $req->status === 'Processing')
                                    <span class="badge badge-warning">{{ $req->status }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ $req->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 20px;">
                                အကူအညီတောင်းခံထားသော မှတ်တမ်းများ မရှိသေးပါ။ <a href="{{ route('public.request.create') }}">အကူအညီတောင်းခံလွှာ အသစ်ပေးပို့ရန်</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 15px;">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
