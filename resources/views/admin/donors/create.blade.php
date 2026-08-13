@extends('layouts.admin')

@section('title')
    Add Donor
@endsection

@section('button')

<a
    href="{{ route('backend.donors.index') }}"
    class="btn btn-secondary">

    Back

</a>

@endsection

@section('content')

<div class="card">

    <div class="card-header">
        <h4>Add Donor</h4>
    </div>

    <div class="card-body">

        <form
            action="{{ route('backend.donors.store') }}"
            method="POST"
        >

            @csrf

            <!-- Name -->
            <div class="form-group mb-3">

                <label>
                    Donor Name
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter donor name"
                    class="form-control @error('name') is-invalid @enderror"
                >

                @error('name')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <!-- Phone -->
            <div class="form-group mb-3">

                <label>
                    Phone
                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="Enter phone number"
                    class="form-control @error('phone') is-invalid @enderror"
                >

                @error('phone')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <!-- Email -->
            <div class="form-group mb-3">

                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter email address"
                    class="form-control @error('email') is-invalid @enderror"
                >

                @error('email')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <!-- Address -->
            <div class="form-group mb-3">

                <label>
                    Address
                </label>

                <textarea
                    name="address"
                    rows="4"
                    placeholder="Enter donor address"
                    class="form-control @error('address') is-invalid @enderror"
                >{{ old('address') }}</textarea>

                @error('address')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <!-- Buttons -->

            <button
                type="submit"
                class="btn btn-primary">

                Save Donor

            </button>

            <a
                href="{{ route('backend.donors.index') }}"
                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

@endsection
