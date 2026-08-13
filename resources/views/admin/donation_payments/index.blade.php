@extends('layouts.admin')

@section('title')
    Donation Payments
@endsection

@section('button')
<a href="{{ route('backend.donation_payments.create') }}" class="btn btn-primary">
    + Add Payment
</a>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h4>Donation Payments</h4>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search & Filter Form --}}
        <form method="GET" action="{{ route('backend.donation_payments.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search Donor, Ref No, Account..." value="{{ request('search') }}">
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- All Status --</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
            </div>

            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Donation ID</th>
                        <th>Donor</th>
                        <th>Payment Method</th>
                        <th>Transaction Ref</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($donationPayments as $payment)
                        <tr>
                            <td>
                                {{ $loop->iteration + ($donationPayments->currentPage() - 1) * $donationPayments->perPage() }}
                            </td>

                            <td>
                                #{{ $payment->donation_id }}
                            </td>

                            <td>
                                {{ $payment->donation->donor->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $payment->payment_method }}
                            </td>

                            <td>
                                {{ $payment->transaction_reference ?? '-' }}
                            </td>

                            <td>
                                {{ $payment->payment_date ? $payment->payment_date->format('d-m-Y') : '-' }}
                            </td>

                            <td>
                                {{ number_format($payment->amount, 2) }} {{ $payment->currency ?? '' }}
                            </td>

                            <td>
                                @if($payment->status === 'Completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($payment->status === 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($payment->status === 'Failed')
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-secondary">Cancelled</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('backend.donation_payments.show', $payment->id) }}" class="btn btn-sm btn-info">
                                    View
                                </a>

                                <a href="{{ route('backend.donation_payments.edit', $payment->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('backend.donation_payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this payment?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                No donation payments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $donationPayments->links() }}
        </div>
    </div>
</div>
@endsection
