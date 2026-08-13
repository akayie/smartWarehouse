@extends('layouts.admin')

@section('title')
    Edit Donation Payment
@endsection

@section('button')

<a
    href="{{ route('backend.donation_payments.index') }}"
    class="btn btn-secondary">

    Back

</a>

@endsection

@section('content')

<div class="card">

    <div class="card-header">

        <h4>Edit Donation Payment</h4>

    </div>

    <div class="card-body">

        <form
            action="{{ route(
                'backend.donation_payments.update',
                $donationPayment->id
            ) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            <!-- Donation Money -->

            <div class="form-group mb-3">

                <label>
                    Money Donation
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="donation_money_id"
                    class="form-control"
                >

                    @foreach($donationMoney as $money)

                        <option
                            value="{{ $money->id }}"
                            {{ old(
                                'donation_money_id',
                                $donationPayment->donation_money_id
                            ) == $money->id
                                ? 'selected'
                                : '' }}
                        >

                            #{{ $money->id }}

                            -

                            {{ $money->donation->donor->name ?? 'N/A' }}

                            -

                            {{ number_format($money->amount, 2) }}

                            {{ $money->currency }}

                        </option>

                    @endforeach

                </select>

                @error('donation_money_id')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Payment Method -->

            <div class="form-group mb-3">

                <label>
                    Payment Method
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="payment_method"
                    class="form-control"
                >

                    @foreach([
                        'Cash',
                        'Bank Transfer',
                        'Mobile Banking',
                        'Mobile Wallet',
                        'Cheque'
                    ] as $method)

                        <option
                            value="{{ $method }}"
                            {{ old(
                                'payment_method',
                                $donationPayment->payment_method
                            ) == $method
                                ? 'selected'
                                : '' }}
                        >

                            {{ $method }}

                        </option>

                    @endforeach

                </select>

                @error('payment_method')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Transaction Reference -->

            <div class="form-group mb-3">

                <label>
                    Transaction Reference
                </label>

                <input
                    type="text"
                    name="transaction_reference"
                    value="{{ old(
                        'transaction_reference',
                        $donationPayment->transaction_reference
                    ) }}"
                    class="form-control"
                >

                @error('transaction_reference')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Payment Date -->

            <div class="form-group mb-3">

                <label>
                    Payment Date
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="payment_date"
                    value="{{ old(
                        'payment_date',
                        $donationPayment->payment_date
                            ? $donationPayment->payment_date->format('Y-m-d')
                            : ''
                    ) }}"
                    class="form-control"
                >

                @error('payment_date')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Account Name -->

            <div class="form-group mb-3">

                <label>
                    Account Name
                </label>

                <input
                    type="text"
                    name="account_name"
                    value="{{ old(
                        'account_name',
                        $donationPayment->account_name
                    ) }}"
                    class="form-control"
                >

            </div>


            <!-- Account Number -->

            <div class="form-group mb-3">

                <label>
                    Account Number
                </label>

                <input
                    type="text"
                    name="account_number"
                    value="{{ old(
                        'account_number',
                        $donationPayment->account_number
                    ) }}"
                    class="form-control"
                >

            </div>


            <!-- Amount -->

            <div class="form-group mb-3">

                <label>
                    Amount
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="number"
                    name="amount"
                    min="0.01"
                    step="0.01"
                    value="{{ old(
                        'amount',
                        $donationPayment->amount
                    ) }}"
                    class="form-control"
                >

                @error('amount')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Existing Proof -->

            @if($donationPayment->proof)

                <div class="form-group mb-3">

                    <label>
                        Current Proof
                    </label>

                    <div>

                        <a
                            href="{{ asset(
                                'storage/' . $donationPayment->proof
                            ) }}"
                            target="_blank"
                            class="btn btn-sm btn-info"
                        >

                            View Current Proof

                        </a>

                    </div>

                </div>

            @endif


            <!-- New Proof -->

            <div class="form-group mb-3">

                <label>
                    Replace Payment Proof
                </label>

                <input
                    type="file"
                    name="proof"
                    accept=".jpg,.jpeg,.png,.pdf"
                    class="form-control"
                >

                <small class="text-muted">
                    JPG, JPEG, PNG or PDF. Maximum 5MB.
                </small>

                @error('proof')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Status -->

            <div class="form-group mb-3">

                <label>
                    Status
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="status"
                    class="form-control"
                >

                    @foreach([
                        'Pending',
                        'Completed',
                        'Failed',
                        'Cancelled'
                    ] as $status)

                        <option
                            value="{{ $status }}"
                            {{ old(
                                'status',
                                $donationPayment->status
                            ) == $status
                                ? 'selected'
                                : '' }}
                        >

                            {{ $status }}

                        </option>

                    @endforeach

                </select>

                @error('status')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Note -->

            <div class="form-group mb-3">

                <label>
                    Note
                </label>

                <textarea
                    name="note"
                    rows="4"
                    class="form-control"
                >{{ old(
                    'note',
                    $donationPayment->note
                ) }}</textarea>

            </div>


            <button
                type="submit"
                class="btn btn-primary">

                Update Payment

            </button>

            <a
                href="{{ route('backend.donation_payments.index') }}"
                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

@endsection
