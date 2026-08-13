@extends('layouts.admin')

@section('title')
    Request Items
@endsection

@section('button')

<a
    href="{{ route('backend.request_items.create') }}"
    class="btn btn-primary">

    + Add Request Item

</a>

@endsection

@section('content')

<div class="card">

    <div class="card-header">

        <h4>Request Items</h4>

    </div>

    <div class="card-body">

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Request ID</th>

                        <th>Disaster</th>

                        <th>Item</th>

                        <th>Quantity</th>

                        <th>Location</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($requestItems as $requestItem)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                #{{ $requestItem->request_id }}
                            </td>

                            <td>

                                {{ $requestItem->request->disaster->name
                                    ?? 'N/A' }}

                            </td>

                            <td>

                                {{ $requestItem->item->name
                                    ?? 'N/A' }}

                            </td>

                            <td>
                                {{ $requestItem->quantity }}

                                {{ $requestItem->item->unit
                                    ?? '' }}
                            </td>

                            <td>

                                {{ $requestItem->request->location
                                    ?? 'N/A' }}

                            </td>

                            <td>

                                @php
                                    $status =
                                        $requestItem
                                            ->request
                                            ->status
                                            ?? 'N/A';
                                @endphp

                                @if($status === 'Pending')

                                    <span class="badge bg-warning">
                                        Pending
                                    </span>

                                @elseif($status === 'Approved')

                                    <span class="badge bg-primary">
                                        Approved
                                    </span>

                                @elseif($status === 'Rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @elseif($status === 'Processing')

                                    <span class="badge bg-info">
                                        Processing
                                    </span>

                                @elseif($status === 'Completed')

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $status }}
                                    </span>

                                @endif

                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'backend.request_items.show',
                                        $requestItem->id
                                    ) }}"
                                    class="btn btn-sm btn-info"
                                >
                                    View
                                </a>

                                <a
                                    href="{{ route(
                                        'backend.request_items.edit',
                                        $requestItem->id
                                    ) }}"
                                    class="btn btn-sm btn-warning"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route(
                                        'backend.request_items.destroy',
                                        $requestItem->id
                                    ) }}"
                                    method="POST"
                                    style="display:inline;"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm(
                                            'Are you sure you want to delete this request item?'
                                        )"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center">

                                No request items found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $requestItems->links() }}

        </div>

    </div>

</div>

@endsection
