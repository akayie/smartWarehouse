@extends('layouts.admin')

@section('title')
    Edit Donation
@endsection

@section('button')

<a
    href="{{ route('backend.donations.index') }}"
    class="btn btn-secondary">

    Back

</a>

@endsection

@section('content')

<div class="card">

    <div class="card-header">

        <h4>Edit Donation</h4>

    </div>

    <div class="card-body">

        <form
            action="{{ route(
                'backend.donations.update',
                $donation->id
            ) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <!-- Donor -->

            <div class="form-group mb-3">

                <label>
                    Donor
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="donor_id"
                    class="form-control"
                >

                    @foreach($donors as $donor)

                        <option
                            value="{{ $donor->id }}"
                            {{ old(
                                'donor_id',
                                $donation->donor_id
                            ) == $donor->id
                                ? 'selected'
                                : '' }}
                        >

                            {{ $donor->name }}

                        </option>

                    @endforeach

                </select>

                @error('donor_id')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Warehouse -->

            <div class="form-group mb-3">

                <label>
                    Warehouse
                    <small class="text-muted">
                        (Optional)
                    </small>
                </label>

                <select
                    name="warehouse_id"
                    class="form-control"
                >

                    <option value="">
                        -- No Warehouse --
                    </option>

                    @foreach($warehouses as $warehouse)

                        <option
                            value="{{ $warehouse->id }}"
                            {{ old(
                                'warehouse_id',
                                $donation->warehouse_id
                            ) == $warehouse->id
                                ? 'selected'
                                : '' }}
                        >

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


            <!-- Donation Type -->

            <div class="form-group mb-3">

                <label>
                    Donation Type
                </label>

                <select
                    name="donation_type"
                    class="form-control"
                >

                    @foreach([
                        'Food',
                        'Water',
                        'Clothing',
                        'Shelter',
                        'Medical',
                        'Equipment',
                        'Cash',
                        'Other'
                    ] as $type)

                        <option
                            value="{{ $type }}"
                            {{ old(
                                'donation_type',
                                $donation->donation_type
                            ) == $type
                                ? 'selected'
                                : '' }}
                        >

                            {{ $type }}

                        </option>

                    @endforeach

                </select>

                @error('donation_type')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Donation Date -->

            <div class="form-group mb-3">

                <label>
                    Donation Date
                </label>

                <input
                    type="date"
                    name="donation_date"
                    value="{{ old(
                        'donation_date',
                        $donation->donation_date
                            ? $donation->donation_date->format('Y-m-d')
                            : ''
                    ) }}"
                    class="form-control"
                >

                @error('donation_date')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Status -->

            <div class="form-group mb-3">

                <label>
                    Status
                </label>

                <select
                    name="status"
                    class="form-control"
                >

                    <option
                        value="Pending"
                        {{ old(
                            'status',
                            $donation->status
                        ) == 'Pending'
                            ? 'selected'
                            : '' }}
                    >
                        Pending
                    </option>

                    <option
                        value="Received"
                        {{ old(
                            'status',
                            $donation->status
                        ) == 'Received'
                            ? 'selected'
                            : '' }}
                    >
                        Received
                    </option>

                    <option
                        value="Cancelled"
                        {{ old(
                            'status',
                            $donation->status
                        ) == 'Cancelled'
                            ? 'selected'
                            : '' }}
                    >
                        Cancelled
                    </option>

                </select>

            </div>


            <!-- Note -->

            <div class="form-group mb-3">

                <label>
                    Note
                </label>

                <textarea
                    name="note"
                    rows="4"
                    class="form-control"
                >{{ old(
                    'note',
                    $donation->note
                ) }}</textarea>

                @error('note')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <button
                type="submit"
                class="btn btn-primary">

                Update Donation

            </button>

            <a
                href="{{ route('backend.donations.index') }}"
                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

@endsection
