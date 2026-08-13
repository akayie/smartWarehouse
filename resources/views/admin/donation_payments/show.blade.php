@extends('layouts.admin')

@section('title')
    Donation Payment Details
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

        <h4>Donation Payment Details</h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>

                <th width="220">
                    Payment ID
                </th>

                <td>
                    #{{ $donationPayment->id }}
                </td>

            </tr>

            <tr>

                <th>
                    Donation Money ID
                </th>

                <td>
                    #{{ $donationPayment->donation_money_id }}
                </td>

            </tr>

            <tr>

                <th>
                    Donor
                </th>

                <td>

                    {{ $donationPayment
                        ->donationMoney
                        ->donation
                        ->donor
                        ->name ?? 'N/A' }}

                </td>

            </tr>

            <tr>

                <th>
                    Payment Method
                </th>

                <td>
                    {{ $donationPayment->payment_method }}
                </td>

            </tr>

            <tr>

                <th>
                    Transaction Reference
                </th>

                <td>
                    {{ $donationPayment->transaction_reference ?? '-' }}
                </td>

            </tr>

            <tr>

                <th>
                    Payment Date
                </th>

                <td>

                    {{ $donationPayment->payment_date
                        ? $donationPayment->payment_date->format('d-m-Y')
                        : '-'
                    }}

                </td>

            </tr>

            <tr>

                <th>
                    Account Name
                </th>

                <td>
                    {{ $donationPayment->account_name ?? '-' }}
                </td>

            </tr>

            <tr>

                <th>
                    Account Number
                </th>

                <td>
                    {{ $donationPayment->account_number ?? '-' }}
                </td>

            </tr>

            <tr>

                <th>
                    Amount
                </th>

                <td>

                    {{ number_format(
                        $donationPayment->amount,
                        2
                    ) }}

                    {{ $donationPayment
                        ->donationMoney
                        ->currency ?? '' }}

                </td>

            </tr>

            <tr>

                <th>
                    Proof
                </th>

                <td>

                    @if($donationPayment->proof)

                        <a
                            href="{{ asset(
                                'storage/' . $donationPayment->proof
                            ) }}"
                            target="_blank"
                            class="btn btn-sm btn-info"
                        >

                            View Payment Proof

                        </a>

                    @else

                        No proof uploaded.

                    @endif

                </td>

            </tr>

            <tr>

                <th>
                    Status
                </th>

                <td>

                    @if($donationPayment->status === 'Completed')

                        <span class="badge bg-success">
                            Completed
                        </span>

                    @elseif($donationPayment->status === 'Pending')

                        <span class="badge bg-warning">
                            Pending
                        </span>

                    @elseif($donationPayment->status === 'Failed')

                        <span class="badge bg-danger">
                            Failed
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Cancelled
                        </span>

                    @endif

                </td>

            </tr>

            <tr>

                <th>
                    Note
                </th>

                <td>
                    {{ $donationPayment->note ?? '-' }}
                </td>

            </tr>

            <tr>

                <th>
                    Created At
                </th>

                <td>
                    {{ $donationPayment->created_at->format(
                        'd-m-Y H:i:s'
                    ) }}
                </td>

            </tr>

            <tr>

                <th>
                    Updated At
                </th>

                <td>
                    {{ $donationPayment->updated_at->format(
                        'd-m-Y H:i:s'
                    ) }}
                </td>

            </tr>

        </table>

    </div>

</div>

@endsection
