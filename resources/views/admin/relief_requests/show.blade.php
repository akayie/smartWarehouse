@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">ကယ်ဆယ်ရေး အကူအညီ တောင်းခံမှု အသေးစိတ် (#{{ $reliefRequest->id }})</h3>
        <a href="{{ route('backend.relief_requests.index') }}" class="btn btn-secondary btn-sm">နောက်သို့</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            {{-- Request Information --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold">အခြေခံ အချက်အလက်များ</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ဘေးအန္တရာယ်</th>
                            <td>{{ $reliefRequest->disaster->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>ထုတ်ယူမည့် ဂိုဒေါင်</th>
                            <td>{{ $reliefRequest->warehouse->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>တောင်းခံသူ</th>
                            <td>{{ $reliefRequest->requestedBy->name ?? 'အများပြည်သူ' }}</td>
                        </tr>
                        <tr>
                            <th>တည်နေရာ</th>
                            <td>{{ $reliefRequest->location }}</td>
                        </tr>
                        <tr>
                            <th>အခြေအနေ (Status)</th>
                            <td>
                                @if($reliefRequest->status === 'Approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($reliefRequest->status === 'Rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>မှတ်ချက်</th>
                            <td>{{ $reliefRequest->note ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Items Requested --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold">တောင်းခံထားသော ပစ္စည်းများ</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ပစ္စည်းအမည်</th>
                                <th>အရေအတွက်</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reliefRequest->requestItems as $key => $reqItem)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $reqItem->item->name ?? 'N/A' }}</td>
                                    <td>{{ $reqItem->quantity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">တောင်းခံထားသော ပစ္စည်း မရှိပါ။</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Actions Sidebar --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold">လုပ်ဆောင်ချက်များ</div>
                <div class="card-body">
                    @if($reliefRequest->status === 'Pending')
                        <form action="{{ route('backend.relief_requests.approve', $reliefRequest->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('အတည်ပြုမှာ သေချာပါသလား?')">
                                အတည်ပြုမည် (Approve)
                            </button>
                        </form>

                        <form action="{{ route('backend.relief_requests.reject', $reliefRequest->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('ပယ်ဖျက်မှာ သေချာပါသလား?')">
                                ပယ်ဖျက်မည် (Reject)
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info mb-0">
                            ဤတောင်းခံမှုကို <strong>{{ $reliefRequest->status }}</strong> ပြုလုပ်ပြီး ဖြစ်ပါသည်။
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
