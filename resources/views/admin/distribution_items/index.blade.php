@extends('layouts.admin')

@section('title', 'ဖြန့်ဝေထားသော ပစ္စည်းများ')

@section('button')
    <a href="{{ route('backend.distribution_items.create') }}"
       class="btn btn-primary shadow-sm">

        <i class="fa-solid fa-plus-circle me-1"></i>
        ဖြန့်ဝေမည့်ပစ္စည်း ထည့်ရန်

    </a>
@endsection


@section('content')

<div id="adm-distribution-items" class="sub-page">

    <div class="card shadow-sm border-0">


        {{-- =========================================================
             CARD HEADER & SEARCH
        ========================================================== --}}

        <div class="card-header bg-white py-3
                    d-flex flex-wrap align-items-center
                    justify-content-between gap-2">

            <h3 class="h5 mb-0 fw-bold text-secondary">

                <i class="fa-solid fa-boxes-stacked me-2 text-primary"></i>

                ဖြန့်ဝေထားသော ပစ္စည်းအသေးစိတ်စာရင်း

            </h3>


            {{-- Search --}}
            <div class="d-flex align-items-center gap-2">

                <form action="{{ route('backend.distribution_items.index') }}"
                      method="GET"
                      class="d-flex align-items-center">

                    <div class="input-group input-group-sm">

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="ပစ္စည်းအမည် သို့မဟုတ် ဖြန့်ဝေမှုအမှတ်ဖြင့် ရှာရန်..."
                               value="{{ request('search') }}">


                        <button class="btn btn-outline-secondary"
                                type="submit"
                                title="ရှာရန်">

                            <i class="fa-solid fa-magnifying-glass"></i>

                        </button>

                    </div>

                </form>

            </div>

        </div>



        <div class="card-body p-3">


            {{-- =========================================================
                 SUCCESS MESSAGE
            ========================================================== --}}

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show"
                     role="alert">

                    <i class="fa-solid fa-circle-check me-2"></i>

                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">

                    </button>

                </div>

            @endif



            {{-- =========================================================
                 ERROR MESSAGE
            ========================================================== --}}

            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show"
                     role="alert">

                    <i class="fa-solid fa-triangle-exclamation me-2"></i>

                    {{ session('error') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">

                    </button>

                </div>

            @endif



            {{-- =========================================================
                 TABLE
            ========================================================== --}}

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0 border">

                    <thead class="table-light">

                        <tr class="text-uppercase fs-7 text-muted">

                            <th style="width: 5%;">
                                #
                            </th>

                            <th style="width: 25%;">
                                ဖြန့်ဝေမှုအမှတ်
                            </th>

                            <th style="width: 35%;">
                                ပစ္စည်းအမည်
                            </th>

                            <th style="width: 15%;">
                                အရေအတွက်
                            </th>

                            <th style="width: 20%;" class="text-end">
                                လုပ်ဆောင်ချက်
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($distributionItems as $index => $dItem)

                            <tr>


                                {{-- Number --}}
                                <td class="text-muted fs-7">

                                    {{ $distributionItems->firstItem() + $index }}

                                </td>



                                {{-- Distribution Reference --}}
                                <td>

                                    <a href="{{ route('backend.distributions.show', $dItem->distribution_id) }}"
                                       class="fw-bold text-decoration-none text-primary">

                                        <i class="fa-solid fa-truck-ramp-box me-1"></i>

                                        #DSP-{{ $dItem->distribution_id }}

                                    </a>


                                    {{-- Warehouse --}}
                                    @if(optional($dItem->distribution)->warehouse)

                                        <br>

                                        <small class="text-muted fs-7">

                                            <i class="fa-solid fa-warehouse me-1"></i>

                                            {{ $dItem->distribution->warehouse->name }}

                                        </small>

                                    @endif

                                </td>



                                {{-- Item --}}
                                <td>

                                    <div class="fw-semibold text-dark">

                                        {{ $dItem->item->name ?? 'N/A' }}

                                    </div>


                                    {{-- Category --}}
                                    @if(optional($dItem->item)->category)

                                        <small class="badge bg-light text-secondary border">

                                            အမျိုးအစား -
                                            {{ $dItem->item->category->name }}

                                        </small>

                                    @endif

                                </td>



                                {{-- Quantity --}}
                                <td>

                                    <span class="badge bg-info text-dark px-2 py-1 fs-6">

                                        <i class="fa-solid fa-cubes me-1"></i>

                                        {{ number_format($dItem->quantity) }}

                                        {{ $dItem->item->unit ?? '' }}

                                    </span>

                                </td>



                                {{-- Actions --}}
                                <td class="text-end">

                                    <div class="btn-group btn-group-sm"
                                         role="group">


                                        {{-- View --}}
                                        <a href="{{ route('backend.distribution_items.show', $dItem->id) }}"
                                           class="btn btn-outline-primary"
                                           title="အသေးစိတ်ကြည့်ရန်">

                                            <i class="fa-solid fa-eye"></i>

                                        </a>



                                        {{-- Edit --}}
                                        <a href="{{ route('backend.distribution_items.edit', $dItem->id) }}"
                                           class="btn btn-outline-secondary"
                                           title="ပြင်ဆင်ရန်">

                                            <i class="fa-solid fa-pen-to-square"></i>

                                        </a>



                                        {{-- Delete --}}
                                        <form action="{{ route('backend.distribution_items.destroy', $dItem->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('ဤဖြန့်ဝေမှတ်တမ်းကို ဖျက်ရန် သေချာပါသလား။ ဖျက်လိုက်ပါက ပစ္စည်းလက်ကျန်ကို ပြန်လည်ဖြည့်သွင်းပေးမည်ဖြစ်ပါသည်။');">

                                            @csrf

                                            @method('DELETE')


                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    style="border-top-left-radius: 0; border-bottom-left-radius: 0;"
                                                    title="ဖျက်ရန်">

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                        @empty

                            {{-- Empty --}}
                            <tr>

                                <td colspan="5"
                                    class="text-center text-muted py-4">

                                    <div class="py-3">

                                        <i class="fa-solid fa-box-open fa-3x text-light mb-2"></i>

                                        <p class="mb-0">
                                            ဖြန့်ဝေထားသော ပစ္စည်းမှတ်တမ်း မရှိသေးပါ။
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>



            {{-- =========================================================
                 PAGINATION
            ========================================================== --}}

            <div class="d-flex justify-content-between
                        align-items-center mt-3 px-1">


                {{-- Showing Entries --}}
                <div class="text-muted small">

                    {{ $distributionItems->firstItem() ?? 0 }}
                    မှ
                    {{ $distributionItems->lastItem() ?? 0 }}
                    အထိ ပြသနေသည်။

                    စုစုပေါင်း
                    {{ $distributionItems->total() }}
                    ခု

                </div>



                {{-- Pagination --}}
                <div>

                    {{ $distributionItems
                        ->appends(request()->query())
                        ->links() }}

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
