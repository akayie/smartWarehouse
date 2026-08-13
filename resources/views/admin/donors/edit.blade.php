@extends('layouts.admin')

@section('title')
    Edit Donor
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

        <h4>Edit Donor</h4>

    </div>

    <div class="card-body">

        <form
            action="{{ route(
                'backend.donors.update',
                $donor->id
            ) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <!-- Name -->

            <div class="form-group mb-3">

                <label>
                    Donor Name
                    <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old(
                        'name',
                        $donor->name
                    ) }}"
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
                    value="{{ old(
                        'phone',
                        $donor->phone
                    ) }}"
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
                    value="{{ old(
                        'email',
                        $donor->email
                    ) }}"
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
                    class="form-control @error('address') is-invalid @enderror"
                >{{ old(
                    'address',
                    $donor->address
                ) }}</textarea>

                @error('address')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            <button
                type="submit"
                class="btn btn-primary">

                Update Donor

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
