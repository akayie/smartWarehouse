```blade
@extends('layouts.admin')

@section('title')
    Create User
@endsection

@section('button')

    <a href="{{ route('backend.users.index') }}"
       class="btn btn-secondary">
        ← Back
    </a>

@endsection

@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="mb-1">
            Create New User
        </h3>

        <p class="text-muted mb-0">
            Create a new system user and assign a role.
        </p>

    </div>


    <div class="card-body">

        <form
            action="{{ route('backend.users.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            <div class="row">

                {{-- Name --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Full Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter full name"
                        class="form-control @error('name') is-invalid @enderror"
                    >

                    @error('name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Phone --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Phone Number
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Enter phone number"
                        class="form-control @error('phone') is-invalid @enderror"
                    >

                    @error('phone')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Email --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Email Address
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter email address"
                        class="form-control @error('email') is-invalid @enderror"
                    >

                    @error('email')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Password --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Password
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter password"
                        class="form-control @error('password') is-invalid @enderror"
                    >

                    @error('password')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Profile --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Profile Image
                    </label>

                    <input
                        type="file"
                        name="profile"
                        accept="image/*"
                        class="form-control @error('profile') is-invalid @enderror"
                    >

                    @error('profile')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Role --}}
                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Role
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="role"
                        class="form-select @error('role') is-invalid @enderror"
                    >

                        <option value="">
                            -- Select Role --
                        </option>

                        <option value="User"
                            {{ old('role') === 'User'
                                ? 'selected'
                                : '' }}>
                            User
                        </option>

                        <option value="Admin"
                            {{ old('role') === 'Admin'
                                ? 'selected'
                                : '' }}>
                            Admin
                        </option>

                        <option value="Super Admin"
                            {{ old('role') === 'Super Admin'
                                ? 'selected'
                                : '' }}>
                            Super Admin
                        </option>

                        <option value="Warehouse Manager"
                            {{ old('role') === 'Warehouse Manager'
                                ? 'selected'
                                : '' }}>
                            Warehouse Manager
                        </option>

                    </select>

                    @error('role')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            <hr>


            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save User
                </button>

                <a
                    href="{{ route('backend.users.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
```
