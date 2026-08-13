@extends('layouts.admin')

@section('title')
    Add Warehouse
@endsection


@section('button')

    <a href="{{ route('backend.warehouses.index') }}"
       class="btn btn-sm btn-outline">
        ← Back
    </a>

@endsection


@section('content')

<div class="card">

    {{-- Header --}}
    <div style="
        margin-bottom:25px;
        padding-bottom:15px;
        border-bottom:1px solid #e5e7eb;
    ">

        <h3 style="
            margin:0;
        ">
            Add New Warehouse
        </h3>

        <p style="
            margin:5px 0 0;
            color:#6b7280;
            font-size:14px;
        ">
            Register a new disaster relief warehouse.
        </p>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div style="
            background:#fef2f2;
            border:1px solid #fecaca;
            color:#b91c1c;
            padding:15px;
            border-radius:8px;
            margin-bottom:20px;
        ">

            <strong>
                Please correct the following errors:
            </strong>

            <ul style="
                margin:8px 0 0 20px;
            ">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('backend.warehouses.store') }}"
        method="POST">

        @csrf


        {{-- Warehouse Name --}}
        <div class="form-group">

            <label for="name">
                Warehouse Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="e.g. Mandalay Central Hub"
                class="@error('name') error @enderror"
            >

            @error('name')

                <span class="error-text">
                    {{ $message }}
                </span>

            @enderror

        </div>


        {{-- Location --}}
        <div class="form-group">

            <label for="location">
                Location
            </label>

            <input
                type="text"
                id="location"
                name="location"
                value="{{ old('location') }}"
                placeholder="e.g. Mandalay Industrial Zone"
                class="@error('location') error @enderror"
            >

            @error('location')

                <span class="error-text">
                    {{ $message }}
                </span>

            @enderror

        </div>


        {{-- Phone --}}
        <div class="form-group">

            <label for="phone">
                Contact Phone
            </label>

            <input
                type="text"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="e.g. 09xxxxxxxxx"
                class="@error('phone') error @enderror"
            >

            @error('phone')

                <span class="error-text">
                    {{ $message }}
                </span>

            @enderror

        </div>


        {{-- Manager --}}
        <div class="form-group">

            <label for="manager_id">
                Warehouse Manager
            </label>

            <select
                id="manager_id"
                name="manager_id"
                class="@error('manager_id') error @enderror">

                <option value="">
                    Choose Manager
                </option>

                @foreach($users as $user)

                    <option
                        value="{{ $user->id }}"
                        {{ old('manager_id') == $user->id
                            ? 'selected'
                            : '' }}>

                        {{ $user->name }}

                    </option>

                @endforeach

            </select>

            @error('manager_id')

                <span class="error-text">
                    {{ $message }}
                </span>

            @enderror

        </div>


        {{-- Status --}}
        <div class="form-group">

            <label for="status">
                Status
            </label>

            <select
                id="status"
                name="status"
                class="@error('status') error @enderror">

                <option
                    value="Active"
                    {{ old('status', 'Active') === 'Active'
                        ? 'selected'
                        : '' }}>
                    Active
                </option>

                <option
                    value="Inactive"
                    {{ old('status') === 'Inactive'
                        ? 'selected'
                        : '' }}>
                    Inactive
                </option>

            </select>

            @error('status')

                <span class="error-text">
                    {{ $message }}
                </span>

            @enderror

        </div>


        {{-- Buttons --}}
        <div style="
            display:flex;
            gap:10px;
            margin-top:25px;
            padding-top:20px;
            border-top:1px solid #e5e7eb;
        ">

            <button
                type="submit"
                class="btn btn-sm btn-primary">

                Save Warehouse

            </button>


            <a
                href="{{ route('backend.warehouses.index') }}"
                class="btn btn-sm btn-outline">

                Cancel

            </a>

        </div>

    </form>

</div>

@endsection
