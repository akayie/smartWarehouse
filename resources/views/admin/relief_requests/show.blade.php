```blade
@extends('layouts.admin')

@section('title')
    Relief Request Details
@endsection

@section('button')

    <a href="{{ route('backend.relief_requests.index') }}"
       class="btn btn-secondary">
        ← Back to Requests
    </a>

@endsection

@section('content')

<div class="row">

    {{-- LEFT COLUMN --}}
    <div class="col-lg-8">

        {{-- Request Overview --}}
        <div class="card mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-1">
                        Relief Request Details
                    </h4>

                    <small class="text-muted">
                        Request #REQ-{{ str_pad($reliefRequest->id, 4, '0', STR_PAD_LEFT) }}
                    </small>
                </div>

                {{-- Status --}}
                <div>

                    @if($reliefRequest->status === 'Pending')

                        <span class="badge bg-warning text-dark">
                            Pending
                        </span>

                    @elseif($reliefRequest->status === 'Approved')

                        <span class="badge bg-primary">
                            Approved
                        </span>

                    @elseif($reliefRequest->status === 'Rejected')

                        <span class="badge bg-danger">
                            Rejected
                        </span>

                    @elseif($reliefRequest->status === 'Processing')

                        <span class="badge bg-info">
                            Processing
                        </span>

                    @elseif($reliefRequest->status === 'Completed')

                        <span class="badge bg-success">
                            Completed
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Cancelled
                        </span>

                    @endif

                </div>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        {{-- Request ID --}}
                        <tr>

                            <th width="220">
                                Request ID
                            </th>

                            <td>
                                <strong>
                                    #REQ-{{ str_pad(
                                        $reliefRequest->id,
                                        4,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}
                                </strong>
                            </td>

                        </tr>

                        {{-- Disaster --}}
                        <tr>

                            <th>
                                Disaster
                            </th>

                            <td>

                                <strong>
                                    {{ $reliefRequest->disaster->name ?? 'N/A' }}
                                </strong>

                                @if($reliefRequest->disaster)

                                    <br>

                                    <small class="text-muted">
                                        Type:
                                        {{ $reliefRequest->disaster->type ?? '-' }}
                                    </small>

                                @endif

                            </td>

                        </tr>

                        {{-- Requested By --}}
                        <tr>

                            <th>
                                Requested By
                            </th>

                            <td>

                                <strong>
                                    {{ $reliefRequest->requestedBy->name ?? 'N/A' }}
                                </strong>

                                <br>

                                <small class="text-muted">
                                    {{ $reliefRequest->requestedBy->email ?? '-' }}
                                </small>

                            </td>

                        </tr>

                        {{-- Location --}}
                        <tr>

                            <th>
                                Request Location
                            </th>

                            <td>
                                📍 {{ $reliefRequest->location }}
                            </td>

                        </tr>

                        {{-- Request Date --}}
                        <tr>

                            <th>
                                Request Date
                            </th>

                            <td>

                                {{ $reliefRequest->request_date
                                    ? $reliefRequest->request_date->format('d-m-Y')
                                    : '-'
                                }}

                            </td>

                        </tr>

                        {{-- Urgency --}}
                        <tr>

                            <th>
                                Urgency
                            </th>

                            <td>

                                @if($reliefRequest->urgency === 'High')

                                    <span class="badge bg-danger">
                                        High
                                    </span>

                                @elseif($reliefRequest->urgency === 'Medium')

                                    <span class="badge bg-warning text-dark">
                                        Medium
                                    </span>

                                @elseif($reliefRequest->urgency === 'Low')

                                    <span class="badge bg-success">
                                        Low
                                    </span>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                        </tr>

                        {{-- Status --}}
                        <tr>

                            <th>
                                Approval Status
                            </th>

                            <td>

                                @if($reliefRequest->status === 'Pending')

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @elseif($reliefRequest->status === 'Approved')

                                    <span class="badge bg-primary">
                                        Approved
                                    </span>

                                @elseif($reliefRequest->status === 'Rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @elseif($reliefRequest->status === 'Processing')

                                    <span class="badge bg-info">
                                        Processing
                                    </span>

                                @elseif($reliefRequest->status === 'Completed')

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Cancelled
                                    </span>

                                @endif

                            </td>

                        </tr>

                        {{-- Note --}}
                        <tr>

                            <th>
                                Note
                            </th>

                            <td>

                                @if($reliefRequest->note)

                                    {{ $reliefRequest->note }}

                                @else

                                    <span class="text-muted">
                                        No additional information.
                                    </span>

                                @endif

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>


        {{-- Required Items --}}
        <div class="card mb-4">

            <div class="card-header">

                <h4 class="mb-0">
                    Required Relief Items
                </h4>

            </div>

            <div class="card-body">

                @if(
                    $reliefRequest->items &&
                    $reliefRequest->items->count()
                )

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th width="70">
                                        #
                                    </th>

                                    <th>
                                        Item
                                    </th>

                                    <th>
                                        Quantity
                                    </th>

                                    <th>
                                        Unit
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach(
                                    $reliefRequest->items
                                    as $requestItem
                                )

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>

                                            <strong>
                                                {{
                                                    $requestItem->item->name
                                                    ?? 'Unknown Item'
                                                }}
                                            </strong>

                                        </td>

                                        <td>

                                            <strong>
                                                {{ $requestItem->quantity }}
                                            </strong>

                                        </td>

                                        <td>

                                            {{
                                                $requestItem->unit
                                                ?? '-'
                                            }}

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-4">

                        <p class="text-muted mb-0">
                            No relief items have been requested.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Action Section --}}
        @if($reliefRequest->status === 'Pending')

            <div class="card mb-4">

                <div class="card-header">

                    <h4 class="mb-0">
                        Request Approval
                    </h4>

                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Review the requested items and approve or reject
                        this relief request.
                    </p>

                    <div class="d-flex gap-2">

                        {{-- Approve --}}
                        <form
                            action="{{ route(
                                'backend.relief_requests.approve',
                                $reliefRequest->id
                            ) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-primary"
                                onclick="return confirm(
                                    'Approve and allocate this relief request?'
                                )"
                            >

                                ✓ Approve & Allocate

                            </button>

                        </form>


                        {{-- Reject --}}
                        <form
                            action="{{ route(
                                'backend.relief_requests.reject',
                                $reliefRequest->id
                            ) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-outline-danger"
                                onclick="return confirm(
                                    'Are you sure you want to reject this request?'
                                )"
                            >

                                ✕ Reject

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        @endif

    </div>


    {{-- RIGHT COLUMN --}}
    <div class="col-lg-4">

        {{-- Request Summary --}}
        <div class="card mb-4">

            <div class="card-header">

                <h4 class="mb-0">
                    Request Summary
                </h4>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <small class="text-muted">
                        Request ID
                    </small>

                    <h5 class="mb-0">
                        #REQ-{{ str_pad(
                            $reliefRequest->id,
                            4,
                            '0',
                            STR_PAD_LEFT
                        ) }}
                    </h5>

                </div>


                <div class="mb-3">

                    <small class="text-muted">
                        Requester
                    </small>

                    <h5 class="mb-0">

                        {{
                            $reliefRequest->requestedBy->name
                            ?? 'N/A'
                        }}

                    </h5>

                </div>


                <div class="mb-3">

                    <small class="text-muted">
                        Location
                    </small>

                    <h5 class="mb-0">

                        {{ $reliefRequest->location }}

                    </h5>

                </div>


                <div class="mb-3">

                    <small class="text-muted">
                        Required Items
                    </small>

                    <h5 class="mb-0">

                        {{
                            $reliefRequest->items
                            ? $reliefRequest->items->count()
                            : 0
                        }}

                        Items

                    </h5>

                </div>


                <div>

                    <small class="text-muted">
                        Request Date
                    </small>

                    <h5 class="mb-0">

                        {{ $reliefRequest->request_date
                            ? $reliefRequest->request_date->format('d-m-Y')
                            : '-'
                        }}

                    </h5>

                </div>

            </div>

        </div>


        {{-- Requester Information --}}
        <div class="card mb-4">

            <div class="card-header">

                <h4 class="mb-0">
                    Requester Information
                </h4>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <small class="text-muted">
                        Name
                    </small>

                    <div>
                        {{
                            $reliefRequest->requestedBy->name
                            ?? 'N/A'
                        }}
                    </div>

                </div>


                <div class="mb-3">

                    <small class="text-muted">
                        Email
                    </small>

                    <div>
                        {{
                            $reliefRequest->requestedBy->email
                            ?? '-'
                        }}
                    </div>

                </div>


                <div>

                    <small class="text-muted">
                        Phone
                    </small>

                    <div>
                        {{
                            $reliefRequest->requestedBy->phone
                            ?? '-'
                        }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Record Information --}}
        <div class="card">

            <div class="card-header">

                <h4 class="mb-0">
                    Record Information
                </h4>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <small class="text-muted">
                        Created At
                    </small>

                    <div>

                        {{ $reliefRequest->created_at
                            ? $reliefRequest->created_at->format(
                                'd-m-Y H:i:s'
                            )
                            : '-'
                        }}

                    </div>

                </div>


                <div>

                    <small class="text-muted">
                        Last Updated
                    </small>

                    <div>

                        {{ $reliefRequest->updated_at
                            ? $reliefRequest->updated_at->format(
                                'd-m-Y H:i:s'
                            )
                            : '-'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

