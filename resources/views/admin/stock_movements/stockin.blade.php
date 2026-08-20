@extends('layouts.admin')

@section('title')
    ပစ္စည်းဝင်ရောက်မှု (Stock In) မှတ်တမ်းများ
@endsection

@section('content')
<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h4 class="mb-0 fw-bold text-success">
            <i class="fas fa-arrow-circle-down me-2"></i> Stock In မှတ်တမ်းနှင့် စုစုပေါင်းစာရင်း
        </h4>
        <small class="text-muted">ကုန်ပစ္စည်း Stock ဝင်ရောက်မှု အချက်အလက်များနှင့် ငွေကြေးပမာဏများ</small>
    </div>

    <div class="card-body">

        {{-- Total Stock In Amount Summary Card --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-white text-success rounded-circle p-3 me-3">
                                <i class="fas fa-calculator fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="text-white-50 mb-1">Stock In စုစုပေါင်း တန်ဖိုး</h6>
                                <h4 class="fw-bold mb-0">
                                    {{ number_format($totalStockInAmount ?? 0, 2) }} <small class="fs-6">MMK</small>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table for History & Details --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>ပစ္စည်းအမည် (Item)</th>
                        <th>ဂိုဒေါင် (Warehouse)</th>
                        <th class="text-center">အရေအတွက် (Qty)</th>
                        <th class="text-end">ဈေးနှုန်း (Price)</th>
                        <th class="text-end">စုစုပေါင်းပမာဏ (Amount)</th>
                        <th>ရက်စွဲ (Date)</th>
                        <th>မှတ်တမ်း / History (Reference)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $calculatedTotalAmount = 0;
                    @endphp

                    @forelse($movements as $movement)
                        @if($movement->type === 'IN')
                            @php
                                $calculatedTotalAmount += $movement->amount;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>

                                {{-- Item Name --}}
                                <td>
                                    <span class="fw-bold text-dark">{{ $movement->item->name ?? 'N/A' }}</span>
                                    <br><small class="text-muted">Code: {{ $movement->item->barcode ?? '-' }}</small>
                                </td>

                                {{-- Warehouse --}}
                                <td>{{ $movement->warehouse->name ?? '-' }}</td>

                                {{-- Quantity --}}
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $movement->quantity }}</span>
                                </td>

                                {{-- Price --}}
                                <td class="text-end">
                                    {{ number_format($movement->price, 2) }}
                                </td>

                                {{-- Amount --}}
                                <td class="text-end fw-bold text-success">
                                    {{ number_format($movement->amount, 2) }} MMK
                                </td>

                                {{-- Date --}}
                                <td>
                                    <i class="far fa-calendar-alt text-muted me-1"></i>
                                    {{ $movement->created_at ? $movement->created_at->format('d-m-Y H:i') : '-' }}
                                </td>

                                {{-- History / Reference / Description --}}
                                <td>
                                    <span class="text-muted">{{ $movement->reference ?? $movement->description ?? 'Stock In Operation' }}</span>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                Stock In မှတ်တမ်း မရှိသေးပါ။
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- Table Footer for Total Amount Sum --}}
                <tfoot class="table-light">
                    <tr>
                        <td colspan="5" class="text-end fw-bold">စုစုပေါင်း (Total Sum):</td>
                        <td class="text-end fw-bold text-success fs-5">
                            {{ number_format($calculatedTotalAmount, 2) }} MMK
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($movements, 'hasPages') && $movements->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $movements->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
