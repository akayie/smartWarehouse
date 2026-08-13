@extends('layouts.admin')

@section('title')
    စတော့ အဝင်/အထွက် အသေးစိတ်
@endsection

@section('button')
    <a href="{{ route('backend.stock-movements.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> နောက်သို့
    </a>
@endsection

@section('content')

<div class="card shadow-sm border-0">

    {{-- Card Header --}}
    <div class="card-header bg-white py-3">
        <h4 class="mb-0 fw-bold text-dark">စတော့ အဝင်/အထွက် အသေးစိတ်အချက်အလက်များ</h4>
    </div>

    {{-- Card Body --}}
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered align-middle">

                {{-- Item Name --}}
                <tr>
                    <th width="220" class="bg-light">ပစ္စည်းအမည်</th>
                    <td>
                        <strong class="text-primary fs-6">
                            {{ $stockMovement->item->name ?? 'သတ်မှတ်မထားပါ' }}
                        </strong>
                    </td>
                </tr>

                {{-- Warehouse --}}
                <tr>
                    <th class="bg-light">ကုန်လှောင်ရုံ / စခန်း</th>
                    <td>
                        {{ $stockMovement->warehouse->name ?? 'သတ်မှတ်မထားပါ' }}
                    </td>
                </tr>

                {{-- Movement Type --}}
                <tr>
                    <th class="bg-light">အမျိုးအစား</th>
                    <td>
                        @if($stockMovement->type === 'IN')
                            <span class="badge bg-success">
                                <i class="fa-solid fa-arrow-down me-1"></i> စတော့ အဝင် (IN)
                            </span>
                        @elseif($stockMovement->type === 'OUT')
                            <span class="badge bg-danger">
                                <i class="fa-solid fa-arrow-up me-1"></i> စတော့ အထွက် (OUT)
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                <i class="fa-solid fa-arrows-rotate me-1"></i> လွှဲပြောင်း (Transfer)
                            </span>
                        @endif
                    </td>
                </tr>

                {{-- Quantity --}}
                <tr>
                    <th class="bg-light">အရေအတွက်</th>
                    <td>
                        <strong class="{{ $stockMovement->type === 'IN' ? 'text-success' : 'text-danger' }} fs-6">
                            {{ $stockMovement->type === 'IN' ? '+' : '-' }}{{ $stockMovement->quantity }}
                        </strong>
                    </td>
                </tr>

                {{-- Reference --}}
                <tr>
                    <th class="bg-light">အကိုးအကား / မှတ်ချက်</th>
                    <td>
                        {{ $stockMovement->reference ?? '-' }}
                    </td>
                </tr>

                {{-- Created By --}}
                <tr>
                    <th class="bg-light">စာရင်းသွင်းသူ</th>
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ $stockMovement->creator->name ?? 'စနစ်မှ' }}
                        </span>
                    </td>
                </tr>

                {{-- Created At --}}
                <tr>
                    <th class="bg-light">စာရင်းသွင်းသည့် အချိန်</th>
                    <td>
                        {{ $stockMovement->created_at ? $stockMovement->created_at->format('d-m-Y h:i:s A') : '-' }}
                    </td>
                </tr>

                {{-- Updated At --}}
                <tr>
                    <th class="bg-light">နောက်ဆုံး ပြင်ဆင်သည့် အချိန်</th>
                    <td>
                        {{ $stockMovement->updated_at ? $stockMovement->updated_at->format('d-m-Y h:i:s A') : '-' }}
                    </td>
                </tr>

            </table>
        </div>

    </div>

</div>

@endsection
