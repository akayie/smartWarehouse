@extends('layouts.admin')

@section('title', 'ဖြန့်ဝေမှုမှတ်တမ်းများ')

@section('button')
<a href="{{ route('backend.distributions.create') }}" class="btn btn-primary">
    <i class="fa-solid fa-plus me-1"></i> ဖြန့်ဝေမှုအသစ်ပြုလုပ်ရန်
</a>
@endsection

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1 fw-bold">ကယ်ဆယ်ရေးပစ္စည်း ဖြန့်ဝေမှုမှတ်တမ်း</h4>
            <p class="text-muted mb-0">
                သိုလှောင်ရုံမှ ကယ်ဆယ်ရေးပစ္စည်းများ ဖြန့်ဝေပေးထားသည့် မှတ်တမ်းများကို စီမံခန့်ခွဲနိုင်ပါသည်။
            </p>
        </div>

        <a href="{{ route('backend.distributions.create') }}"
           class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i>
            ဖြန့်ဝေမှုအသစ်
        </a>
    </div>

    {{-- Distribution Card --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fa-solid fa-truck-fast me-2"></i>
                ဖြန့်ဝေမှုမှတ်တမ်းများ
            </h5>
        </div>

        <div class="card-body">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fa-solid fa-circle-check me-1"></i>
                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>
                </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                    {{ session('error') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>
                </div>
            @endif

            {{-- Table --}}
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th style="width:60px;">စဉ်</th>
                            <th>ဖြန့်ဝေသည့်ရက်</th>
                            <th>သိုလှောင်ရုံ</th>
                            <th>တောင်းခံမှု</th>
                            <th>ပစ္စည်းအရေအတွက်</th>
                            <th>ဆောင်ရွက်သူ</th>
                            <th style="width:140px;" class="text-center">
                                လုပ်ဆောင်ချက်
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($distributions as $distribution)

                            <tr>

                                {{-- ID --}}
                                <td>
                                    <span class="fw-semibold">
                                        #{{ $distribution->id }}
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td>
                                    @if($distribution->distribution_date)
                                        <i class="fa-regular fa-calendar me-1 text-muted"></i>
                                        {{ $distribution->distribution_date->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Warehouse --}}
                                <td>
                                    @if($distribution->warehouse)
                                        <i class="fa-solid fa-warehouse me-1 text-muted"></i>
                                        {{ $distribution->warehouse->name }}
                                    @else
                                        <span class="text-muted">မသတ်မှတ်ရသေးပါ</span>
                                    @endif
                                </td>

                                {{-- Request --}}
                                <td>

                                    @if($distribution->request)

                                        <span class="badge bg-primary">
                                            Request #{{ $distribution->request->id }}
                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            {{ $distribution->request->location ?? '-' }}
                                        </small>

                                    @else

                                        <span class="badge bg-secondary">
                                            တိုက်ရိုက်ဖြန့်ဝေမှု
                                        </span>

                                    @endif

                                </td>

                                {{-- Items Count --}}
                                <td>

                                    <span class="badge bg-info text-dark">
                                        {{ $distribution->distributionItems->count() }}
                                        မျိုး
                                    </span>

                                </td>

                                {{-- Handled By --}}
                                <td>

                                    @if($distribution->handledBy)

                                        <i class="fa-solid fa-user me-1 text-muted"></i>
                                        {{ $distribution->handledBy->name }}

                                    @else

                                        <span class="text-muted">
                                            System
                                        </span>

                                    @endif

                                </td>

                                {{-- Action --}}
                                <td class="text-center">

                                    <a href="{{ route(
                                        'backend.distributions.show',
                                        $distribution->id
                                    ) }}"
                                       class="btn btn-sm btn-info">

                                        <i class="fa-solid fa-eye me-1"></i>
                                        အသေးစိတ်ကြည့်ရန်

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5 text-muted">

                                    <i class="fa-solid fa-box-open fa-2x mb-2 d-block"></i>

                                    <div class="fw-semibold">
                                        ဖြန့်ဝေမှုမှတ်တမ်း မရှိသေးပါ။
                                    </div>

                                    <small>
                                        ကယ်ဆယ်ရေးပစ္စည်း ဖြန့်ဝေမှုများ ပြုလုပ်ပြီးနောက်
                                        မှတ်တမ်းများကို ဤနေရာတွင် တွေ့မြင်နိုင်ပါသည်။
                                    </small>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if($distributions->hasPages())

                <div class="mt-3">
                    {{ $distributions->links() }}
                </div>

            @endif

        </div>

    </div>

</div>

@endsection
