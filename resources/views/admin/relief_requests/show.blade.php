@extends('layouts.admin')

@section('title')
ကယ်ဆယ်ရေးအကူအညီ တောင်းခံမှုအသေးစိတ်
@endsection

@section('button')

```
<a href="{{ route('backend.relief_requests.index') }}"
   class="btn btn-secondary">
    ← တောင်းခံမှုများသို့ ပြန်သွားရန်
</a>
```

@endsection

@section('content')

<div class="row">

```
{{-- LEFT COLUMN --}}
<div class="col-lg-8">

    {{-- Request Overview --}}
    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>
                <h4 class="mb-1">
                    ကယ်ဆယ်ရေးအကူအညီ တောင်းခံမှုအသေးစိတ်
                </h4>

                <small class="text-muted">
                    တောင်းခံမှုအမှတ် -
                    #REQ-{{ str_pad($reliefRequest->id, 4, '0', STR_PAD_LEFT) }}
                </small>
            </div>

            {{-- Status --}}
            <div>

                @if($reliefRequest->status === 'Pending')

                    <span class="badge bg-warning text-dark">
                        စောင့်ဆိုင်းနေသည်
                    </span>

                @elseif($reliefRequest->status === 'Approved')

                    <span class="badge bg-primary">
                        အတည်ပြုပြီး
                    </span>

                @elseif($reliefRequest->status === 'Rejected')

                    <span class="badge bg-danger">
                        ပယ်ဖျက်ထားသည်
                    </span>

                @elseif($reliefRequest->status === 'Processing')

                    <span class="badge bg-info">
                        ဆောင်ရွက်နေသည်
                    </span>

                @elseif($reliefRequest->status === 'Completed')

                    <span class="badge bg-success">
                        ပြီးစီးပြီ
                    </span>

                @else

                    <span class="badge bg-secondary">
                        ပယ်ဖျက်ထားသည်
                    </span>

                @endif

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    {{-- Request ID --}}
                    <tr>
                        <th width="220">
                            တောင်းခံမှုအမှတ်
                        </th>

                        <td>
                            <strong>
                                #REQ-{{ str_pad(
                                    $reliefRequest->id,
                                    4,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}
                            </strong>
                        </td>
                    </tr>

                    {{-- Disaster --}}
                    <tr>
                        <th>
                            ဘေးအန္တရာယ်ဖြစ်စဉ်
                        </th>

                        <td>

                            <strong>
                                {{ $reliefRequest->disaster->name ?? 'အချက်အလက်မရှိပါ' }}
                            </strong>

                            @if($reliefRequest->disaster)

                                <br>

                                <small class="text-muted">
                                    အမျိုးအစား -
                                    {{ $reliefRequest->disaster->type ?? '-' }}
                                </small>

                            @endif

                        </td>
                    </tr>

                    {{-- Requested By --}}
                    <tr>
                        <th>
                            တောင်းခံသူ
                        </th>

                        <td>

                            <strong>
                                {{ $reliefRequest->requestedBy->name ?? 'အချက်အလက်မရှိပါ' }}
                            </strong>

                            <br>

                            <small class="text-muted">
                                {{ $reliefRequest->requestedBy->email ?? '-' }}
                            </small>

                        </td>
                    </tr>

                    {{-- Location --}}
                    <tr>
                        <th>
                            အကူအညီတောင်းခံသည့်နေရာ
                        </th>

                        <td>
                            📍 {{ $reliefRequest->location }}
                        </td>
                    </tr>

                    {{-- Request Date --}}
                    <tr>
                        <th>
                            တောင်းခံသည့်ရက်စွဲ
                        </th>

                        <td>

                            {{ $reliefRequest->request_date
                                ? $reliefRequest->request_date->format('d-m-Y')
                                : '-'
                            }}

                        </td>
                    </tr>

                    {{-- Urgency --}}
                    <tr>
                        <th>
                            အရေးပေါ်အဆင့်
                        </th>

                        <td>

                            @if($reliefRequest->urgency === 'High')

                                <span class="badge bg-danger">
                                    အရေးပေါ်မြင့်
                                </span>

                            @elseif($reliefRequest->urgency === 'Medium')

                                <span class="badge bg-warning text-dark">
                                    အလယ်အလတ်
                                </span>

                            @elseif($reliefRequest->urgency === 'Low')

                                <span class="badge bg-success">
                                    အရေးပေါ်နိမ့်
                                </span>

                            @else

                                <span class="text-muted">
                                    -
                                </span>

                            @endif

                        </td>
                    </tr>

                    {{-- Status --}}
                    <tr>
                        <th>
                            တောင်းခံမှုအခြေအနေ
                        </th>

                        <td>

                            @if($reliefRequest->status === 'Pending')

                                <span class="badge bg-warning text-dark">
                                    စောင့်ဆိုင်းနေသည်
                                </span>

                            @elseif($reliefRequest->status === 'Approved')

                                <span class="badge bg-primary">
                                    အတည်ပြုပြီး
                                </span>

                            @elseif($reliefRequest->status === 'Rejected')

                                <span class="badge bg-danger">
                                    ပယ်ဖျက်ထားသည်
                                </span>

                            @elseif($reliefRequest->status === 'Processing')

                                <span class="badge bg-info">
                                    ဆောင်ရွက်နေသည်
                                </span>

                            @elseif($reliefRequest->status === 'Completed')

                                <span class="badge bg-success">
                                    ပြီးစီးပြီ
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    ပယ်ဖျက်ထားသည်
                                </span>

                            @endif

                        </td>
                    </tr>

                    {{-- Note --}}
                    <tr>
                        <th>
                            မှတ်ချက်
                        </th>

                        <td>

                            @if($reliefRequest->note)

                                {{ $reliefRequest->note }}

                            @else

                                <span class="text-muted">
                                    ထပ်မံဖြည့်စွက်ထားသော အချက်အလက်မရှိပါ။
                                </span>

                            @endif

                        </td>
                    </tr>

                </table>

            </div>

        </div>

    </div>


    {{-- Required Items --}}
    <div class="card mb-4">

        <div class="card-header">

            <h4 class="mb-0">
                လိုအပ်သော ကယ်ဆယ်ရေးပစ္စည်းများ
            </h4>

        </div>

        <div class="card-body">

            @if(
                $reliefRequest->items &&
                $reliefRequest->items->count()
            )

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>
                            <tr>
                                <th width="70">စဉ်</th>
                                <th>ပစ္စည်းအမည်</th>
                                <th>အရေအတွက်</th>
                                <th>ယူနစ်</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach(
                                $reliefRequest->items
                                as $requestItem
                            )

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{
                                                $requestItem->item->name
                                                ?? 'ပစ္စည်းအမည်မသိရှိပါ'
                                            }}
                                        </strong>
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $requestItem->quantity }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{
                                            $requestItem->unit
                                            ?? '-'
                                        }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-4">

                    <p class="text-muted mb-0">
                        တောင်းခံထားသော ကယ်ဆယ်ရေးပစ္စည်းများ မရှိပါ။
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Action Section --}}
    @if($reliefRequest->status === 'Pending')

        <div class="card mb-4">

            <div class="card-header">

                <h4 class="mb-0">
                    တောင်းခံမှု အတည်ပြုခြင်း
                </h4>

            </div>

            <div class="card-body">

                <p class="text-muted">
                    တောင်းခံထားသော ပစ္စည်းများနှင့် အချက်အလက်များကို
                    စစ်ဆေးပြီးနောက် တောင်းခံမှုကို အတည်ပြုခြင်း သို့မဟုတ်
                    ပယ်ဖျက်ခြင်း ပြုလုပ်နိုင်ပါသည်။
                </p>

                <div class="d-flex gap-2">

                    {{-- Approve --}}
                    <form
                        action="{{ route(
                            'backend.relief_requests.approve',
                            $reliefRequest->id
                        ) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="btn btn-primary"
                            onclick="return confirm(
                                'ဤကယ်ဆယ်ရေးအကူအညီ တောင်းခံမှုကို အတည်ပြုပြီး ပစ္စည်းများ ခွဲဝေပေးမည်မှာ သေချာပါသလား?'
                            )"
                        >
                            ✓ အတည်ပြုပြီး ပစ္စည်းခွဲဝေပေးရန်
                        </button>

                    </form>


                    {{-- Reject --}}
                    <form
                        action="{{ route(
                            'backend.relief_requests.reject',
                            $reliefRequest->id
                        ) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="btn btn-outline-danger"
                            onclick="return confirm(
                                'ဤတောင်းခံမှုကို ပယ်ဖျက်ရန် သေချာပါသလား?'
                            )"
                        >
                            ✕ ပယ်ဖျက်ရန်
                        </button>

                    </form>

                </div>

            </div>

        </div>

    @endif

</div>


{{-- RIGHT COLUMN --}}
<div class="col-lg-4">

    {{-- Request Summary --}}
    <div class="card mb-4">

        <div class="card-header">

            <h4 class="mb-0">
                တောင်းခံမှု အကျဉ်းချုပ်
            </h4>

        </div>

        <div class="card-body">

            <div class="mb-3">

                <small class="text-muted">
                    တောင်းခံမှုအမှတ်
                </small>

                <h5 class="mb-0">
                    #REQ-{{ str_pad(
                        $reliefRequest->id,
                        4,
                        '0',
                        STR_PAD_LEFT
                    ) }}
                </h5>

            </div>

            <div class="mb-3">

                <small class="text-muted">
                    တောင်းခံသူ
                </small>

                <h5 class="mb-0">

                    {{
                        $reliefRequest->requestedBy->name
                        ?? 'အချက်အလက်မရှိပါ'
                    }}

                </h5>

            </div>

            <div class="mb-3">

                <small class="text-muted">
                    တောင်းခံသည့်နေရာ
                </small>

                <h5 class="mb-0">

                    {{ $reliefRequest->location }}

                </h5>

            </div>

            <div class="mb-3">

                <small class="text-muted">
                    လိုအပ်သော ပစ္စည်းအမျိုးအစား
                </small>

                <h5 class="mb-0">

                    {{
                        $reliefRequest->items
                        ? $reliefRequest->items->count()
                        : 0
                    }}

                    မျိုး

                </h5>

            </div>

            <div>

                <small class="text-muted">
                    တောင်းခံသည့်ရက်စွဲ
                </small>

                <h5 class="mb-0">

                    {{ $reliefRequest->request_date
                        ? $reliefRequest->request_date->format('d-m-Y')
                        : '-'
                    }}

                </h5>

            </div>

        </div>

    </div>


    {{-- Requester Information --}}
    <div class="card mb-4">

        <div class="card-header">

            <h4 class="mb-0">
                တောင်းခံသူ၏ အချက်အလက်
            </h4>

        </div>

        <div class="card-body">

            <div class="mb-3">

                <small class="text-muted">
                    အမည်
                </small>

                <div>
                    {{
                        $reliefRequest->requestedBy->name
                        ?? 'အချက်အလက်မရှိပါ'
                    }}
                </div>

            </div>

            <div class="mb-3">

                <small class="text-muted">
                    အီးမေးလ်
                </small>

                <div>
                    {{
                        $reliefRequest->requestedBy->email
                        ?? '-'
                    }}
                </div>

            </div>

            <div>

                <small class="text-muted">
                    ဖုန်းနံပါတ်
                </small>

                <div>
                    {{
                        $reliefRequest->requestedBy->phone
                        ?? '-'
                    }}
                </div>

            </div>

        </div>

    </div>


    {{-- Record Information --}}
    <div class="card">

        <div class="card-header">

            <h4 class="mb-0">
                မှတ်တမ်းအချက်အလက်
            </h4>

        </div>

        <div class="card-body">

            <div class="mb-3">

                <small class="text-muted">
                    စတင်မှတ်တမ်းတင်သည့်အချိန်
                </small>

                <div>

                    {{ $reliefRequest->created_at
                        ? $reliefRequest->created_at->format(
                            'd-m-Y H:i:s'
                        )
                        : '-'
                    }}

                </div>

            </div>

            <div>

                <small class="text-muted">
                    နောက်ဆုံးပြင်ဆင်သည့်အချိန်
                </small>

                <div>

                    {{ $reliefRequest->updated_at
                        ? $reliefRequest->updated_at->format(
                            'd-m-Y H:i:s'
                        )
                        : '-'
                    }}

                </div>

            </div>

        </div>

    </div>

</div>
```

</div>

@endsection
