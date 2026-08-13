@extends('layouts.admin')

@section('title')
    Request Item Details
@endsection

@section('button')

<a
    href="{{ route('backend.request_items.index') }}"
    class="btn btn-secondary">

    Back

</a>

@endsection

@section('content')

<div class="card">

    <div class="card-header">

        <h4>Request Item Details</h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>

                <th width="220">
                    ID
                </th>

                <td>
                    #{{ $requestItem->id }}
                </td>

            </tr>

            <tr>

                <th>
                    Request ID
                </th>

                <td>
                    #{{ $requestItem->request_id }}
                </td>

            </tr>

            <tr>

                <th>
                    Disaster
                </th>

                <td>

                    {{ $requestItem->request->disaster->name
                        ?? 'N/A' }}

                </td>

            </tr>

            <tr>

                <th>
                    Request Location
                </th>

                <td>

                    {{ $requestItem->request->location
                        ?? 'N/A' }}

                </td>

            </tr>

            <tr>

                <th>
                    Requested By
                </th>

                <td>

                    {{ $requestItem->request->requestedBy->name
                        ?? 'N/A' }}

                </td>

            </tr>

            <tr>

                <th>
                    Item
                </th>

                <td>

                    {{ $requestItem->item->name
                        ?? 'N/A' }}

                </td>

            </tr>

            <tr>

                <th>
                    Unit
                </th>

                <td>

                    {{ $requestItem->item->unit
                        ?? '-' }}

                </td>

            </tr>

            <tr>

                <th>
                    Quantity
                </th>

                <td>

                    {{ $requestItem->quantity }}

                    {{ $requestItem->item->unit
                        ?? '' }}

                </td>

            </tr>

            <tr>

                <th>
                    Request Status
                </th>

                <td>

                    {{ $requestItem->request->status
                        ?? '-' }}

                </td>

            </tr>

            <tr>

                <th>
                    Created At
                </th>

                <td>

                    {{ $requestItem->created_at
                        ->format('d-m-Y H:i:s') }}

                </td>

            </tr>

            <tr>

                <th>
                    Updated At
                </th>

                <td>

                    {{ $requestItem->updated_at
                        ->format('d-m-Y H:i:s') }}

                </td>

            </tr>

        </table>

    </div>

</div>

@endsection
