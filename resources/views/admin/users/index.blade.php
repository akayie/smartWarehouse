```blade
@extends('layouts.admin')

@section('title')
    Users & Roles
@endsection

@section('button')

    <a href="{{ route('backend.users.create') }}"
       class="btn btn-primary">
        + Add User
    </a>

@endsection

@section('content')

<div class="card">

    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center">

        <div>
            <h3 class="mb-1">
                User Accounts & Role Permissions
            </h3>

            <p class="text-muted mb-0">
                Manage system users, roles and account status.
            </p>
        </div>

        <a href="{{ route('backend.users.create') }}"
           class="btn btn-sm btn-primary">

            + Add User

        </a>

    </div>


    <div class="card-body">

        {{-- Success Message --}}
        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        {{-- Error Message --}}
        @if(session('error'))

            <div class="alert alert-danger">
                {{ session('error') }}
            </div>

        @endif


        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead>

                    <tr>

                        <th width="60">
                            #
                        </th>

                        <th>
                            User
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Phone
                        </th>

                        <th>
                            Role
                        </th>

                        <th>
                            Assigned Hub
                        </th>

                        <th>
                            Status
                        </th>

                        <th width="180">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($users as $user)

                        <tr>

                            {{-- Number --}}
                            <td>
                                {{ $users->firstItem() + $loop->index }}
                            </td>


                            {{-- User --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    @if($user->profile)

                                        <img
                                            src="{{ asset($user->profile) }}"
                                            width="45"
                                            height="45"
                                            class="rounded-circle me-2"
                                            style="object-fit: cover;"
                                        >

                                    @else

                                        <div
                                            class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2"
                                            style="
                                                width:45px;
                                                height:45px;
                                            "
                                        >
                                            {{ strtoupper(
                                                substr($user->name, 0, 1)
                                            ) }}
                                        </div>

                                    @endif


                                    <div>

                                        <strong>
                                            {{ $user->name }}
                                        </strong>

                                    </div>

                                </div>

                            </td>


                            {{-- Email --}}
                            <td>
                                {{ $user->email }}
                            </td>


                            {{-- Phone --}}
                            <td>
                                {{ $user->phone ?? '-' }}
                            </td>


                            {{-- Role --}}
                            <td>

                                @if($user->role === 'Super Admin')

                                    <span class="badge bg-danger">
                                        Super Admin
                                    </span>

                                @elseif($user->role === 'Admin')

                                    <span class="badge bg-primary">
                                        Admin
                                    </span>

                                @elseif($user->role === 'Warehouse Manager')

                                    <span class="badge bg-info">
                                        Warehouse Manager
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $user->role ?? 'User' }}
                                    </span>

                                @endif

                            </td>


                            {{-- Assigned Hub --}}
                            <td>

                                @if(isset($user->warehouse))

                                    {{ $user->warehouse->name }}

                                @else

                                    <span class="text-muted">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if(isset($user->status))

                                    @if($user->status === 'Active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @elseif($user->status === 'Inactive')

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            {{ $user->status }}
                                        </span>

                                    @endif

                                @else

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <a
                                    href="{{ route(
                                        'backend.users.edit',
                                        $user->id
                                    ) }}"
                                    class="btn btn-sm btn-warning"
                                >
                                    Edit
                                </a>


                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger delete-user"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                >
                                    Delete
                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <div class="text-muted">

                                    <h5>
                                        No Users Found
                                    </h5>

                                    <p>
                                        No user accounts are currently
                                        available.
                                    </p>

                                    <a
                                        href="{{ route(
                                            'backend.users.create'
                                        ) }}"
                                        class="btn btn-primary"
                                    >
                                        + Create User
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="mt-3">

            {{ $users->links() }}

        </div>

    </div>

</div>


{{-- Delete Modal --}}
<div
    class="modal fade"
    id="deleteModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Delete User
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <p class="mb-0">

                    Are you sure you want to delete

                    <strong id="deleteUserName"></strong>?

                </p>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>


                <form
                    action=""
                    method="POST"
                    id="deleteForm"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Yes, Delete
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection


@section('script')

<script>

$(document).ready(function () {

    $('.delete-user').on('click', function () {

        let id = $(this).data('id');
        let name = $(this).data('name');

        $('#deleteUserName').text(name);

        $('#deleteForm').attr(
            'action',
            '{{ url("admin/users") }}/' + id
        );

        $('#deleteModal').modal('show');

    });

});

</script>

@endsection
```
