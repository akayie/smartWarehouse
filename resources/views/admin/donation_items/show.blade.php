@extends('layouts.admin')

@section('title', 'Donation Item Details')

@section('button')
<a href="{{ route('backend.donation_items.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Back
</a>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">Donation Item Details</h5>
        <div>
            <a href="{{ route('backend.donation_items.edit', $donationItem->id) }}" class="btn btn-sm btn-warning">
                Edit Item
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped table-bordered mb-0">
            <tbody>
                <tr>
                    <th width="220" class="bg-light">Donation ID</th>
                    <td>#{{ $donationItem->donation_id }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Donor</th>
                    <td>{{ $donationItem->donation->donor->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Warehouse</th>
                    <td>{{ $donationItem->donation->warehouse->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Item</th>
                    <td><strong class="text-primary">{{ $donationItem->item->name ?? 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <th class="bg-light">Quantity</th>
                    <td>
                        <span class="badge bg-success">
                            {{ $donationItem->quantity }} {{ $donationItem->item?->unit }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th class="bg-light">Donation Date</th>
                    <td>
                        {{ $donationItem->donation->donation_date ? $donationItem->donation->donation_date->format('d-m-Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <th class="bg-light">Created At</th>
                    <td>{{ $donationItem->created_at->format('d-m-Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th class="bg-light">Updated At</th>
                    <td>{{ $donationItem->updated_at->format('d-m-Y H:i:s') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
