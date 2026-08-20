@extends('layouts.admin')

@section('title')
    လှူဒါန်းငွေ ပေးချေမှုများ
@endsection

@section('button')
<a href="{{ route('backend.donation_payments.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> ပေးချေမှုအသစ် ထည့်သွင်းရန်
</a>
@endsection

@section('content')

<div class="card mb-4 shadow-sm border-0">

    {{-- Card Header --}}
    <div class="card-header bg-white py-3">
        <h4 class="mb-0 fw-bold text-primary">
            <i class="fas fa-money-bill-wave me-2"></i>
            လှူဒါန်းငွေ ပေးချေမှု စီမံခန့်ခွဲမှု
        </h4>
        <small class="text-muted">
            လှူဒါန်းမှုများနှင့် သက်ဆိုင်သော ငွေပေးချေမှုမှတ်တမ်းများကို စီမံခန့်ခွဲနိုင်ပါသည်။
        </small>
    </div>

    <div class="card-body">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Total Completed Amount Card --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 bg-white text-primary rounded-circle p-3 me-3">
                                <i class="fas fa-hand-holding-usd fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="text-white-50 mb-1">ပြီးစီးပြီး ငွေပမာဏ စုစုပေါင်း</h6>
                                <h4 class="fw-bold mb-0">
                                    {{ number_format($totalCompletedAmount, 2) }} <small class="fs-6">MMK</small>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search & Filter Form --}}
        <div class="card border-0 bg-light mb-4">
            <div class="card-body">

                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-filter me-2 text-primary"></i>
                    ရှာဖွေခြင်းနှင့် စစ်ထုတ်ခြင်း
                </h6>

                <form method="GET"
                      action="{{ route('backend.donation_payments.index') }}"
                      class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">ရှာဖွေရန်</label>
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="အလှူရှင်၊ ရည်ညွှန်းနံပါတ်..."
                               value="{{ request('search') }}">
                    </div>

                    {{-- Status --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">အခြေအနေ</label>
                        <select name="status" class="form-select">
                            <option value="">-- အခြေအနေအားလုံး --</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>ပြီးစီးပြီး</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>စောင့်ဆိုင်းဆဲ</option>
                            <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>မအောင်မြင်ပါ</option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>ပယ်ဖျက်ပြီး</option>
                        </select>
                    </div>

                    {{-- From Date --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">စတင်ရက်စွဲ</label>
                        <input type="date"
                               name="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">
                    </div>

                    {{-- To Date --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">နောက်ဆုံးရက်စွဲ</label>
                        <input type="date"
                               name="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">
                    </div>

                    {{-- Filter Button --}}
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="fas fa-search me-1"></i> ရှာဖွေ / စစ်ထုတ်ရန်
                        </button>
                    </div>

                    {{-- Clear Button --}}
                    @if(request()->hasAny(['search', 'status', 'from_date', 'to_date']))
                        <div class="col-md-1 d-flex align-items-end">
                            <a href="{{ route('backend.donation_payments.index') }}"
                               class="btn btn-outline-danger w-100"
                               title="စစ်ထုတ်မှုများကို ဖျက်ရန်">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    @endif

                </form>

            </div>
        </div>

        {{-- Payment Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th>လှူဒါန်းမှုအမှတ်</th>
                        <th>အလှူရှင်</th>
                        <th>ငွေပေးချေမှုနည်းလမ်း</th>
                        <th>ငွေပေးချေမှုရည်ညွှန်းနံပါတ်</th>
                        <th>ပေးချေသည့်ရက်စွဲ</th>
                        <th class="text-end">ပမာဏ</th>
                        <th class="text-center">အခြေအနေ</th>
                        <th class="text-center">လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $filteredCompletedSum = 0;
                    @endphp

                    @forelse($donationPayments as $payment)
                        @php
                            if ($payment->status === 'Completed') {
                                $filteredCompletedSum += $payment->amount;
                            }
                        @endphp
                        <tr>
                            {{-- Number --}}
                            <td class="text-center">
                                {{ $loop->iteration + ($donationPayments->currentPage() - 1) * $donationPayments->perPage() }}
                            </td>

                            {{-- Donation ID --}}
                            <td>
                                <span class="fw-bold text-primary">#{{ $payment->donation_id }}</span>
                            </td>

                            {{-- Donor --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $payment->donation->donor->name ?? 'အချက်အလက်မရှိပါ' }}
                                </div>
                                @if($payment->donation->donor->phone ?? false)
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i> {{ $payment->donation->donor->phone }}
                                    </small>
                                @endif
                            </td>

                            {{-- Payment Method --}}
                            <td>
                                @php
                                    $method = $payment->payment_method;
                                    $methodText = match($method) {
                                        'Cash' => 'ငွေသား',
                                        'Bank Transfer' => 'ဘဏ်ငွေလွှဲ',
                                        'Mobile Banking' => 'မိုဘိုင်းဘဏ်လုပ်ငန်း',
                                        'KBZPay' => 'KBZPay',
                                        'WavePay' => 'WavePay',
                                        'AYA Pay' => 'AYA Pay',
                                        default => $method ?? '-',
                                    };
                                @endphp
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-credit-card me-1"></i> {{ $methodText }}
                                </span>
                            </td>

                            {{-- Transaction Reference --}}
                            <td>
                                @if($payment->transaction_reference)
                                    <code>{{ $payment->transaction_reference }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Payment Date --}}
                            <td>
                                @if($payment->payment_date)
                                    <i class="far fa-calendar-alt me-1 text-primary"></i>
                                    {{ $payment->payment_date->format('d-m-Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Amount --}}
                            <td class="text-end">
                                <span class="fw-bold text-dark">
                                    {{ number_format($payment->amount, 2) }}
                                </span>
                                @if($payment->currency)
                                    <small class="text-muted">{{ $payment->currency }}</small>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                @if($payment->status === 'Completed')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> ပြီးစီးပြီး
                                    </span>
                                @elseif($payment->status === 'Pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i> စောင့်ဆိုင်းဆဲ
                                    </span>
                                @elseif($payment->status === 'Failed')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> မအောင်မြင်ပါ
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-ban me-1"></i> ပယ်ဖျက်ပြီး
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    {{-- View --}}
                                    <a href="{{ route('backend.donation_payments.show', $payment->id) }}"
                                       class="btn btn-outline-info"
                                       title="အသေးစိတ်ကြည့်ရန်">
                                        View
                                    </a>

                                    {{-- Edit
                                    <a href="{{ route('backend.donation_payments.edit', $payment->id) }}"
                                       class="btn btn-outline-warning"
                                       title="ပြင်ဆင်ရန်">
                                        <i class="fas fa-edit"></i>
                                    </a> --}}

                                    {{-- Delete
                                    <form action="{{ route('backend.donation_payments.destroy', $payment->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('ဤပေးချေမှုမှတ်တမ်းကို ဖျက်ရန် သေချာပါသလား?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-outline-danger"
                                                title="ဖျက်ရန်">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-file-invoice-dollar fa-3x mb-3 d-block"></i>
                                    <h6 class="fw-bold">လှူဒါန်းငွေ ပေးချေမှုမှတ်တမ်း မတွေ့ရှိပါ။</h6>
                                    <p class="mb-0 small">
                                        ပေးချေမှုအသစ် ထည့်သွင်းရန် “ပေးချေမှုအသစ် ထည့်သွင်းရန်” ခလုတ်ကို နှိပ်ပါ။
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- Table Footer for Total Amount (ပြီးစီးပြီး status တွေရဲ့ sum) --}}
                @if($donationPayments->isNotEmpty())
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="text-end fw-bold text-dark">
                                ပြီးစီးပြီး စုစုပေါင်း ပမာဏ (Current Page Total):
                            </td>
                            <td class="text-end fw-bold text-success fs-6">
                                {{ number_format($filteredCompletedSum, 2) }} MMK
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                @endif

            </table>
        </div>

        {{-- Pagination --}}
        @if($donationPayments->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $donationPayments->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
