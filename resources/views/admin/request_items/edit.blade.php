@extends('layouts.admin')

@section('title')
    Edit Request Item
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

        <h4>Edit Request Item</h4>

    </div>

    <div class="card-body">

        <form
            action="{{ route(
                'backend.request_items.update',
                $requestItem->id
            ) }}"
            method="POST"
        >

            @csrf

            @method('PUT')


            <!-- Relief Request -->

            <div class="form-group mb-3">

                <label>
                    Relief Request
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="request_id"
                    class="form-control"
                >

                    @foreach($requests as $request)

                        <option
                            value="{{ $request->id }}"
                            {{ old(
                                'request_id',
                                $requestItem->request_id
                            ) == $request->id
                                ? 'selected'
                                : '' }}
                        >

                            Request #{{ $request->id }}

                            -

                            {{ $request->disaster->name
                                ?? 'N/A' }}

                            -

                            {{ $request->location }}

                        </option>

                    @endforeach

                </select>

                @error('request_id')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Item -->

            <div class="form-group mb-3">

                <label>
                    Item
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="item_id"
                    class="form-control"
                >

                    @foreach($items as $item)

                        <option
                            value="{{ $item->id }}"
                            {{ old(
                                'item_id',
                                $requestItem->item_id
                            ) == $item->id
                                ? 'selected'
                                : '' }}
                        >

                            {{ $item->name }}

                            @if($item->unit)
                                ({{ $item->unit }})
                            @endif

                        </option>

                    @endforeach

                </select>

                @error('item_id')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Quantity -->

            <div class="form-group mb-3">

                <label>
                    Quantity
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="number"
                    name="quantity"
                    min="1"
                    value="{{ old(
                        'quantity',
                        $requestItem->quantity
                    ) }}"
                    class="form-control"
                >

                @error('quantity')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <button
                type="submit"
                class="btn btn-primary">

                Update Request Item

            </button>

            <a
                href="{{ route('backend.request_items.index') }}"
                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

@endsection
