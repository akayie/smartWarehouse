@extends('layouts.admin')

@section('title')
    တောင်းဆိုထားသော ပစ္စည်း အသေးစိတ်
@endsection

@section('button')
    <a href="{{ route('backend.request_items.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> နောက်သို့
    </a>
@endsection

@section('content')

<div class="card shadow border-0 mb-4">

    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold text-dark">တောင်းဆိုထားသော ပစ္စည်း အသေးစိတ်</h4>
        <div>
            <a href="{{ route('backend.request_items.edit', $requestItem->id) }}" class="btn btn-warning btn-sm text-dark">
                <i class="fa-solid fa-pen-to-square me-1"></i> ပြင်ဆင်မည်
            </a>
        </div>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <tbody>
                    <tr>
                        <th width="220" class="bg-light">အမှတ် (ID)</th>
                        <td>#{{ $requestItem->id }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">တောင်းဆိုမှု အမှတ်</th>
                        <td class="fw-bold">#{{ $requestItem->request_id }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">ဘေးအန္တရာယ် အမျိုးအစား</th>
                        <td>{{ $requestItem->request->disaster->name ?? 'မရှိပါ' }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">တောင်းဆိုသည့် နေရာ</th>
                        <td>{{ $requestItem->request->location ?? 'မရှိပါ' }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">တောင်းဆိုသူ</th>
                        <td>{{ $requestItem->request->requestedBy->name ?? 'မရှိပါ' }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">ပစ္စည်းအမည်</th>
                        <td class="fw-bold text-primary">{{ $requestItem->item->name ?? 'မရှိပါ' }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">ရေတွက်ပုံ စံနှုန်း (ယူနစ်)</th>
                        <td>{{ $requestItem->item->unit ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">အရေအတွက်</th>
                        <td>
                            <span class="fs-6 fw-bold">
                                {{ $requestItem->quantity }} {{ $requestItem->item->unit ?? '' }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th class="bg-light">တောင်းဆိုမှု အခြေအနေ</th>
                        <td>
                            @php
                                $status = $requestItem->request->status ?? 'မရှိပါ';
                            @endphp

                            @if($status === 'Pending')
                                <span class="badge bg-warning text-dark">စောင့်ဆိုင်းဆဲ</span>
                            @elseif($status === 'Approved')
                                <span class="badge bg-primary">ခွင့်ပြုပြီး</span>
                            @elseif($status === 'Rejected')
                                <span class="badge bg-danger">ငြင်းပယ်ထားသည်</span>
                            @elseif($status === 'Processing')
                                <span class="badge bg-info text-dark">ဆောင်ရွက်နေဆဲ</span>
                            @elseif($status === 'Completed')
                                <span class="badge bg-success">ပြီးစီးပါပြီ</span>
                            @else
                                <span class="badge bg-secondary">{{ $status }}</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="bg-light">ဖန်တီးခဲ့သည့် အချိန်</th>
                        <td>{{ $requestItem->created_at ? $requestItem->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                    </tr>

                    <tr>
                        <th class="bg-light">နောက်ဆုံးပြင်ဆင်ခဲ့သည့် အချိန်</th>
                        <td>{{ $requestItem->updated_at ? $requestItem->updated_at->format('d-m-Y H:i:s') : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection
