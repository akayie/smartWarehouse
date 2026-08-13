@extends('layouts.admin')

@section('title')
    Edit Stock Movement
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
        <h4>Edit Stock Movement</h4>
    </div>

    <div class="card-body">

        <form action="{{ route(
            'backend.stock-movements.update',
            $stockMovement->id
        ) }}"
              method="POST">

            @csrf
            @method('PUT')


            <!-- Item -->

            <div class="form-group mb-3">

                <label>Item</label>

                <select name="item_id"
                        class="form-control @error('item_id') is-invalid @enderror">

                    @foreach($items as $item)

                        <option value="{{ $item->id }}"
                            {{ old(
                                'item_id',
                                $stockMovement->item_id
                            ) == $item->id ? 'selected' : '' }}>

                            {{ $item->name }}

                        </option>

                    @endforeach

                </select>

                @error('item_id')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <!-- Warehouse -->

            <div class="form-group mb-3">

                <label>Warehouse</label>

                <select name="warehouse_id"
                        class="form-control @error('warehouse_id') is-invalid @enderror">

                    @foreach($warehouses as $warehouse)

                        <option value="{{ $warehouse->id }}"
                            {{ old(
                                'warehouse_id',
                                $stockMovement->warehouse_id
                            ) == $warehouse->id ? 'selected' : '' }}>

                            {{ $warehouse->name }}

                        </option>

                    @endforeach

                </select>

                @error('warehouse_id')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <!-- Type -->

            <div class="form-group mb-3">

                <label>Movement Type</label>

                <select name="type"
                        class="form-control">

                    <option value="IN"
                        {{ old(
                            'type',
                            $stockMovement->type
                        ) == 'IN' ? 'selected' : '' }}>

                        Stock IN

                    </option>

                    <option value="OUT"
                        {{ old(
                            'type',
                            $stockMovement->type
                        ) == 'OUT' ? 'selected' : '' }}>

                        Stock OUT

                    </option>

                    <option value="TRANSFER"
                        {{ old(
                            'type',
                            $stockMovement->type
                        ) == 'TRANSFER' ? 'selected' : '' }}>

                        Transfer

                    </option>

                </select>

            </div>


            <!-- Quantity -->

            <div class="form-group mb-3">

                <label>Quantity</label>

                <input type="number"
                       name="quantity"
                       min="1"
                       value="{{ old(
                           'quantity',
                           $stockMovement->quantity
                       ) }}"
                       class="form-control">

            </div>


            <!-- Reference -->

            <div class="form-group mb-3">

                <label>Reference</label>

                <input type="text"
                       name="reference"
                       value="{{ old(
                           'reference',
                           $stockMovement->reference
                       ) }}"
                       class="form-control">

            </div>


            <button type="submit"
                    class="btn btn-primary">

                Update

            </button>

            <a href="{{ route('backend.stock-movements.index') }}"
               class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

@endsection
