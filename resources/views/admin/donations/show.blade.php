@extends('layouts.admin')

@section('title', 'လှူဒါန်းမှုအသေးစိတ် #' . $donation->id)

@section('button')

<a href="{{ route('backend.donations.index') }}"
   class="btn btn-secondary">

    <i class="fas fa-arrow-left me-1"></i>
    လှူဒါန်းမှုစာရင်းသို့ ပြန်သွားရန်

</a>

@endsection

@section('content')

<div class="container-fluid">

    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show mb-3">

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

        <div class="alert alert-danger alert-dismissible fade show mb-3">

            <i class="fas fa-exclamation-circle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row">

        {{-- ========================= --}}
        {{-- Donation Information --}}
        {{-- ========================= --}}

        <div class="col-md-4 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-hand-holding-heart me-2"></i>

                        လှူဒါန်းမှုအသေးစိတ်

                    </h5>

                </div>


                <div class="card-body">

                    {{-- Donation ID --}}
                    <p>

                        <strong>
                            လှူဒါန်းမှုအမှတ်:
                        </strong>

                        #DON-{{ str_pad(
                            $donation->id,
                            4,
                            '0',
                            STR_PAD_LEFT
                        ) }}

                    </p>


                    {{-- Donor --}}
                    <p>

                        <strong>
                            အလှူရှင်:
                        </strong>

                        {{ $donation->donor->name ?? 'မရှိပါ' }}

                    </p>


                    {{-- Warehouse --}}
                    <p>

                        <strong>
                            ဂိုဒေါင်:
                        </strong>

                        {{ $donation->warehouse->name ?? '-' }}

                    </p>


                    {{-- Status --}}
                    <p>

                        <strong>
                            အခြေအနေ:
                        </strong>

                        @if($donation->status === 'Pending')

                            <span class="badge bg-warning text-dark">

                                <i class="fas fa-clock me-1"></i>

                                စောင့်ဆိုင်းနေသည်

                            </span>

                        @elseif($donation->status === 'Received')

                            <span class="badge bg-success">

                                <i class="fas fa-check-circle me-1"></i>

                                လက်ခံပြီး

                            </span>

                        @else

                            <span class="badge bg-danger">

                                <i class="fas fa-times-circle me-1"></i>

                                ပယ်ဖျက်ထားသည်

                            </span>

                        @endif

                    </p>


                    {{-- Note --}}
                    <p>

                        <strong>
                            မှတ်ချက်:
                        </strong>

                        {{ $donation->note ?? '-' }}

                    </p>


                    <hr>


                    {{-- Receive Donation --}}
                    @if($donation->status === 'Pending')

                        <div class="alert alert-info">

                            <i class="fas fa-info-circle me-1"></i>

                            လှူဒါန်းထားသောပစ္စည်းများကို လက်ခံပြီးပါက
                            Inventory Stock ထဲသို့ အလိုအလျောက်
                            ထည့်သွင်းသွားမည်ဖြစ်ပါသည်။

                        </div>


                        <form
                            action="{{ route(
                                'backend.donations.receive',
                                $donation->id
                            ) }}"
                            method="POST"
                            onsubmit="return confirm(
                                'ဤလှူဒါန်းမှုကို လက်ခံမည်မှာ သေချာပါသလား? လက်ခံပြီးပါက Inventory Stock ထဲသို့ ပစ္စည်းအရေအတွက်များ တိုးသွားမည်ဖြစ်ပါသည်။'
                            )"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success w-100 fw-bold"
                            >

                                <i class="fas fa-check-circle me-1"></i>

                                လှူဒါန်းမှု လက်ခံရန်
                                (Stock IN)

                            </button>

                        </form>

                    @elseif($donation->status === 'Received')

                        <div class="alert alert-success mb-0">

                            <i class="fas fa-check-circle me-1"></i>

                            ဤလှူဒါန်းမှုကို လက်ခံပြီးဖြစ်ပြီး
                            Inventory Stock ထဲသို့ ထည့်သွင်းပြီးဖြစ်ပါသည်။

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ========================= --}}
        {{-- Donated Items --}}
        {{-- ========================= --}}

        <div class="col-md-8 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-boxes text-primary me-2"></i>

                        လှူဒါန်းထားသော ပစ္စည်းများ

                    </h5>

                </div>


                <div class="card-body">

                    @if($donation->donationItems->count())

                        <div class="table-responsive">

                            <table class="table table-bordered align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th style="width:60px;">
                                            #
                                        </th>

                                        <th>
                                            ပစ္စည်းအမည်
                                        </th>

                                        <th>
                                            ယူနစ်
                                        </th>

                                        <th class="text-end">
                                            အရေအတွက်
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($donation->donationItems as $dItem)

                                        <tr>

                                            {{-- Number --}}
                                            <td>

                                                {{ $loop->iteration }}

                                            </td>


                                            {{-- Item Name --}}
                                            <td>

                                                <i class="fas fa-box text-secondary me-1"></i>

                                                <strong>
                                                    {{ $dItem->item->name ?? 'ပစ္စည်းမရှိပါ' }}
                                                </strong>

                                            </td>


                                            {{-- Unit --}}
                                            <td>

                                                {{ $dItem->item->unit ?? '-' }}

                                            </td>


                                            {{-- Quantity --}}
                                            <td class="text-end">

                                                <strong class="text-primary">

                                                    {{ number_format(
                                                        $dItem->quantity
                                                    ) }}

                                                </strong>

                                                <span class="text-muted">

                                                    {{ $dItem->item->unit ?? '' }}

                                                </span>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-4 text-muted">

                            <i class="fas fa-box-open fa-2x mb-2 d-block"></i>

                            လှူဒါန်းထားသော ပစ္စည်းစာရင်း မရှိသေးပါ။

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
