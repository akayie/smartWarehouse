@extends('layouts.admin')

@section('title')
    Edit Relief Request
@endsection

@section('button')

<a
    href="{{ route('backend.relief_requests.index') }}"
    class="btn btn-secondary">

    Back

</a>

@endsection

@section('content')

<div class="card">

    <div class="card-header">

        <h4>Edit Relief Request</h4>

    </div>

    <div class="card-body">

        <form
            action="{{ route(
                'backend.relief_requests.update',
                $reliefRequest->id
            ) }}"
            method="POST"
        >

            @csrf

            @method('PUT')


            <!-- Disaster -->

            <div class="form-group mb-3">

                <label>
                    Disaster
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="disaster_id"
                    class="form-control"
                >

                    @foreach($disasters as $disaster)

                        <option
                            value="{{ $disaster->id }}"
                            {{ old(
                                'disaster_id',
                                $reliefRequest->disaster_id
                            ) == $disaster->id
                                ? 'selected'
                                : '' }}
                        >

                            {{ $disaster->name }}

                            -
                            {{ $disaster->location }}

                        </option>

                    @endforeach

                </select>

                @error('disaster_id')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Requested By -->

            <div class="form-group mb-3">

                <label>
                    Requested By
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="requested_by"
                    class="form-control"
                >

                    @foreach($users as $user)

                        <option
                            value="{{ $user->id }}"
                            {{ old(
                                'requested_by',
                                $reliefRequest->requested_by
                            ) == $user->id
                                ? 'selected'
                                : '' }}
                        >

                            {{ $user->name }}

                            -
                            {{ $user->email }}

                        </option>

                    @endforeach

                </select>

                @error('requested_by')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Location -->

            <div class="form-group mb-3">

                <label>
                    Request Location
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="location"
                    value="{{ old(
                        'location',
                        $reliefRequest->location
                    ) }}"
                    class="form-control"
                >

                @error('location')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Request Date -->

            <div class="form-group mb-3">

                <label>
                    Request Date
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="request_date"
                    value="{{ old(
                        'request_date',
                        $reliefRequest->request_date
                            ? $reliefRequest->request_date->format('Y-m-d')
                            : ''
                    ) }}"
                    class="form-control"
                >

                @error('request_date')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- Status -->

            <div class="form-group mb-3">

                <label>
                    Status
                    <span class="text-danger">*</span>
                </label>

                <select
                    name="status"
                    class="form-control"
                >

                    @foreach([
                        'Pending',
                        'Approved',
                        'Rejected',
                        'Processing',
                        'Completed',
                        'Cancelled'
                    ] as $status)

                        <option
                            value="{{ $status }}"
                            {{ old(
                                'status',
                                $reliefRequest->status
                            ) == $status
                                ? 'selected'
                                : '' }}
                        >

                            {{ $status }}

                        </option>

                    @endforeach

                </select>

                @error('status')

                    <div class="text-danger">
                        {{ $message }}
                    </div>

                @enderror

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
                    $reliefRequest->note
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

                Update Request

            </button>

            <a
                href="{{ route('backend.relief_requests.index') }}"
                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

@endsection
