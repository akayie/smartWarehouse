@extends('layouts.admin')

@section('title')

Edit User

@endsection

@section('button')

<a href="{{ route('backend.users.index') }}" class="add-btn">
    ← Back
</a>

@endsection

@section('content')

<div class="box">

```
<h2>
    Edit User
</h2>


<form action="{{ route('backend.users.update', $user->id) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    @method('PUT')


    <!-- User Name -->

    <div class="form-group">

        <label>
            Name
        </label>

        <input type="text"
               name="name"
               value="{{ old('name', $user->name) }}"
               placeholder="Enter user name"
               class="@error('name') error @enderror">

        @error('name')

            <span class="error-text">
                {{ $message }}
            </span>

        @enderror

    </div>


    <!-- Phone -->

    <div class="form-group">

        <label>
            Phone
        </label>

        <input type="text"
               name="phone"
               value="{{ old('phone', $user->phone) }}"
               placeholder="Enter phone number"
               class="@error('phone') error @enderror">

        @error('phone')

            <span class="error-text">
                {{ $message }}
            </span>

        @enderror

    </div>


    <!-- Current Profile -->

    <div class="form-group">

        <label>
            Current Profile
        </label>

        <div style="margin:15px 0;">

            @if($user->profile)

                <img src="{{ asset($user->profile) }}"
                     width="150"
                     height="150"
                     style="border-radius:10px;object-fit:cover;">

            @else

                <p>
                    No Profile Image Available
                </p>

            @endif

        </div>


        <input type="hidden"
               name="old_profile"
               value="{{ $user->profile }}">

    </div>


    <!-- Change Profile -->

    <div class="form-group">

        <label>
            Change Profile
        </label>

        <input type="file"
               name="profile"
               accept="image/*"
               class="@error('profile') error @enderror">

        @error('profile')

            <span class="error-text">
                {{ $message }}
            </span>

        @enderror

    </div>


    <!-- Email -->

    <div class="form-group">

        <label>
            Email
        </label>

        <input type="email"
               name="email"
               value="{{ old('email', $user->email) }}"
               placeholder="Enter email address"
               class="@error('email') error @enderror">

        @error('email')

            <span class="error-text">
                {{ $message }}
            </span>

        @enderror

    </div>


    <!-- Role -->

    <div class="form-group">

        <label>
            Role
        </label>

        <select name="role"
                class="@error('role') error @enderror">

            <option value="User"
                {{ old('role', $user->role) == 'User' ? 'selected' : '' }}>
                User
            </option>

            <option value="Admin"
                {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>
                Admin
            </option>

            <option value="Super Admin"
                {{ old('role', $user->role) == 'Super Admin' ? 'selected' : '' }}>
                Super Admin
            </option>

        </select>

        @error('role')

            <span class="error-text">
                {{ $message }}
            </span>

        @enderror

    </div>


    <!-- Update Button -->

    <button type="submit" class="save-btn">
        Update User
    </button>


</form>
```

</div>

@endsection
