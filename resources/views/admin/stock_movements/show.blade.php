@extends('layouts.admin')

@section('title')
    Stock Movement Details
@endsection

@section('button')

<a href="{{ route('backend.stock-movements.index') }}"
   class="btn btn-secondary">

    Back

</a>

@endsection

@section('content')

<div class="card">

    <div class="card-header">

        <h4>Stock Movement Details</h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>

                <th width="200">
                    Item
                </th>

                <td>
                    {{ $stockMovement->item->name ?? 'N/A' }}
                </td>

            </tr>

            <tr>

                <th>
                    Warehouse
                </th>

                <td>
                    {{ $stockMovement->warehouse->name ?? 'N/A' }}
                </td>

            </tr>

            <tr>

                <th>
                    Type
                </th>

                <td>

                    @if($stockMovement->type === 'IN')

                        <span class="badge bg-success">
                            Stock IN
                        </span>

                    @elseif($stockMovement->type === 'OUT')

                        <span class="badge bg-danger">
                            Stock OUT
                        </span>

                    @else

                        <span class="badge bg-warning">
                            Transfer
                        </span>

                    @endif

                </td>

            </tr>

            <tr>

                <th>
                    Quantity
                </th>

                <td>
                    {{ $stockMovement->quantity }}
                </td>

            </tr>

            <tr>

                <th>
                    Reference
                </th>

                <td>
                    {{ $stockMovement->reference ?? '-' }}
                </td>

            </tr>

            <tr>

                <th>
                    Created By
                </th>

                <td>
                    {{ $stockMovement->creator->name ?? 'N/A' }}
                </td>

            </tr>

            <tr>

                <th>
                    Created At
                </th>

                <td>
                    {{ $stockMovement->created_at->format('d-m-Y H:i:s') }}
                </td>

            </tr>

            <tr>

                <th>
                    Updated At
                </th>

                <td>
                    {{ $stockMovement->updated_at->format('d-m-Y H:i:s') }}
                </td>

            </tr>

        </table>

    </div>

</div>

@endsection
