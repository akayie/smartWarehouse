@extends('layouts.admin')

@section('title', 'Distribution Item Details')

@section('button')
    <a href="{{ route('backend.distribution_items.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to List
    </a>
@endsection

@section('content')
<div id="adm-distribution-item-show" class="sub-page">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-secondary">
                        <i class="fa-solid fa-box-open me-2 text-primary"></i>Distribution Item Details
                    </h5>
                    <span class="badge bg-primary">
                        #DSP-{{ $distributionItem->distribution_id }}
                    </span>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <th style="width: 35%;" class="bg-light">Distribution Ref</th>
                                    <td>
                                        <a href="{{ route('backend.distributions.show', $distributionItem->distribution_id) }}" class="fw-bold text-decoration-none">
                                            #DSP-{{ $distributionItem->distribution_id }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Relief Request Ref</th>
                                    <td>
                                        @if(optional($distributionItem->distribution)->relief_request_id)
                                            <span class="badge bg-outline-info text-dark border">
                                                #REQ-{{ $distributionItem->distribution->relief_request_id }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Source Warehouse</th>
                                    <td>
                                        <i class="fa-solid fa-warehouse me-1 text-secondary"></i>
                                        {{ $distributionItem->distribution->warehouse->name ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Item Name</th>
                                    <td class="fw-bold text-dark">
                                        {{ $distributionItem->item->name ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Dispatched Quantity</th>
                                    <td>
                                        <span class="badge bg-info text-dark fs-6 px-3 py-1">
                                            {{ number_format($distributionItem->quantity) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Handled By</th>
                                    <td>
                                        <i class="fa-solid fa-user me-1 text-muted"></i>
                                        {{ $distributionItem->distribution->handledBy->name ?? 'System Admin' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Created At</th>
                                    <td>{{ $distributionItem->created_at ? $distributionItem->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Last Updated</th>
                                    <td>{{ $distributionItem->updated_at ? $distributionItem->updated_at->format('d M Y, h:i A') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('backend.distribution_items.edit', $distributionItem->id) }}" class="btn btn-warning">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit Item
                        </a>
                        <form action="{{ route('backend.distribution_items.destroy', $distributionItem->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this record? Stock will be restored.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fa-solid fa-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
