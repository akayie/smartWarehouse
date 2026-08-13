@extends('layouts.admin')

@section('title', 'လှူဒါန်းမှုများ')

@section('button')

<a href="{{ route('backend.donations.create') }}"
   class="btn btn-primary">

    <i class="fas fa-plus me-1"></i>
    လှူဒါန်းမှုအသစ် ထည့်ရန်

</a>

@endsection

@section('content')

<div class="card shadow-sm border-0">

    {{-- Header --}}
    <div class="card-header bg-white py-3">

        <h5 class="mb-0 fw-bold">

            <i class="fas fa-hand-holding-heart text-primary me-2"></i>

            လှူဒါန်းမှုစာရင်းနှင့် လက်ခံစီမံခြင်း

        </h5>

    </div>


    <div class="card-body">

        {{-- Success Message --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="fas fa-check-circle me-1"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- Error Message --}}
        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="fas fa-exclamation-circle me-1"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- Donations Table --}}
        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th style="width: 50px;">
                            #
                        </th>

                        <th>
                            အလှူရှင်
                        </th>

                        <th>
                            ဂိုဒေါင်
                        </th>

                        <th>
                            လှူဒါန်းသည့်ပစ္စည်းများ
                        </th>

                        <th>
                            အခြေအနေ
                        </th>

                        <th
                            class="text-center"
                            style="width: 250px;">

                            လုပ်ဆောင်ချက်

                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($donations as $donation)

                        <tr>

                            {{-- Number --}}
                            <td>

                                {{
                                    $loop->iteration +
                                    ($donations->currentPage() - 1)
                                    * $donations->perPage()
                                }}

                            </td>


                            {{-- Donor --}}
                            <td>

                                <strong class="text-primary">

                                    {{ $donation->donor->name ?? 'မရှိပါ' }}

                                </strong>

                                <br>

                                <small class="text-muted">

                                    {{ $donation->donor->phone ?? '-' }}

                                </small>

                            </td>


                            {{-- Warehouse --}}
                            <td>

                                {{ $donation->warehouse->name ?? '-' }}

                            </td>


                            {{-- Donation Items --}}
                            <td>

                                @if($donation->donationItems->count())

                                    <ul class="list-unstyled mb-0">

                                        @foreach($donation->donationItems as $item)

                                            <li class="mb-1">

                                                <i class="fas fa-box text-secondary me-1"></i>

                                                {{ $item->item->name ?? 'ပစ္စည်းမရှိပါ' }}

                                                -

                                                <span class="badge bg-secondary">

                                                    {{ $item->quantity }}

                                                    {{ $item->item->unit ?? '' }}

                                                </span>

                                            </li>

                                        @endforeach

                                    </ul>

                                @else

                                    <span class="text-muted">
                                        လှူဒါန်းပစ္စည်း မရှိပါ။
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($donation->status === 'Pending')

                                    <span class="badge bg-warning text-dark">

                                        <i class="fas fa-clock me-1"></i>

                                        စောင့်ဆိုင်းနေသည်

                                    </span>


                                @elseif($donation->status === 'Received')

                                    <span class="badge bg-success">

                                        <i class="fas fa-check-circle me-1"></i>

                                        လက်ခံပြီး (Stock တိုးပြီး)

                                    </span>


                                @else

                                    <span class="badge bg-danger">

                                        <i class="fas fa-times-circle me-1"></i>

                                        ပယ်ဖျက်ထားသည်

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="text-center">

                                @if($donation->status === 'Pending')

                                    {{-- Receive Donation --}}
                                    <form
                                        action="{{ route(
                                            'backend.donations.receive',
                                            $donation->id
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            'ဤလှူဒါန်းမှုကို လက်ခံမည်မှာ သေချာပါသလား? လက်ခံပြီးပါက Inventory Stock ပမာဏ တိုးသွားမည်ဖြစ်ပါသည်။'
                                        )"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-success fw-bold"
                                        >

                                            <i class="fas fa-check me-1"></i>

                                            လက်ခံရန်

                                        </button>

                                    </form>

                                @endif


                                {{-- View --}}
                                <a
                                    href="{{ route(
                                        'backend.donations.show',
                                        $donation->id
                                    ) }}"
                                    class="btn btn-sm btn-outline-info"
                                >

                                    <i class="fas fa-eye me-1"></i>
                                    ကြည့်ရန်

                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route(
                                        'backend.donations.edit',
                                        $donation->id
                                    ) }}"
                                    class="btn btn-sm btn-outline-warning"
                                >

                                    <i class="fas fa-edit me-1"></i>
                                    ပြင်ဆင်ရန်

                                </a>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-4 text-muted"
                            >

                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>

                                လှူဒါန်းမှုစာရင်း မရှိသေးပါ။

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="mt-3">

            {{ $donations->links() }}

        </div>

    </div>

</div>

@endsection
