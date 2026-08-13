@extends('layouts.admin')

@section('title')
    Warehouses
@endsection

@section('button')

    <a href="{{ route('backend.warehouses.create') }}"
       class="btn btn-sm btn-primary">
        + Add Warehouse
    </a>

@endsection


@section('content')

<div class="card">

    {{-- Page Header --}}
    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:20px;
        margin-bottom:20px;
        flex-wrap:wrap;
    ">

        <div>

            <h3 style="
                margin:0 0 5px 0;
            ">
                Warehouse Management
            </h3>

            <p style="
                margin:0;
                color:#6b7280;
                font-size:14px;
            ">
                Manage relief warehouses, locations and assigned managers.
            </p>

        </div>

        <div style="
            background:#f3f4f6;
            padding:8px 14px;
            border-radius:8px;
            font-size:14px;
            font-weight:600;
            color:#374151;
        ">
            Total: {{ $warehouses->total() }}
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div style="
            background:#ecfdf5;
            border:1px solid #a7f3d0;
            color:#047857;
            padding:12px 15px;
            border-radius:8px;
            margin-bottom:20px;
        ">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div style="
            background:#fef2f2;
            border:1px solid #fecaca;
            color:#b91c1c;
            padding:12px 15px;
            border-radius:8px;
            margin-bottom:20px;
        ">
            {{ session('error') }}
        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div style="
            background:#fef2f2;
            border:1px solid #fecaca;
            color:#b91c1c;
            padding:12px 15px;
            border-radius:8px;
            margin-bottom:20px;
        ">

            <strong>Please check the following:</strong>

            <ul style="margin:8px 0 0 20px;">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Warehouse Table --}}
    <div style="overflow-x:auto;">

        <table class="data-table">

            <thead>

                <tr>

                    <th>Code</th>

                    <th>Name</th>

                    <th>Location</th>

                    <th>Phone</th>

                    <th>Manager</th>

                    <th>Status</th>

                    <th style="text-align:center;">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($warehouses as $warehouse)

                    <tr>

                        {{-- Code --}}
                        <td>

                            <strong>
                                WH-{{ str_pad(
                                    $warehouse->id,
                                    3,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}
                            </strong>

                        </td>


                        {{-- Name --}}
                        <td>

                            <strong>
                                {{ $warehouse->name }}
                            </strong>

                        </td>


                        {{-- Location --}}
                        <td>

                            {{ $warehouse->location ?: '-' }}

                        </td>


                        {{-- Phone --}}
                        <td>

                            {{ $warehouse->phone ?: '-' }}

                        </td>


                        {{-- Manager --}}
                        <td>

                            @if($warehouse->manager)

                                {{ $warehouse->manager->name }}

                            @else

                                <span style="
                                    color:#9ca3af;
                                ">
                                    Not Assigned
                                </span>

                            @endif

                        </td>


                        {{-- Status --}}
                        <td>

                            @if($warehouse->status === 'Active')

                                <span class="badge badge-success">
                                    Active
                                </span>

                            @else

                                <span class="badge badge-secondary">
                                    Inactive
                                </span>

                            @endif

                        </td>


                        {{-- Actions --}}
                        <td style="
                            text-align:center;
                            white-space:nowrap;
                        ">

                            {{-- View --}}
                            <a href="{{ route(
                                'backend.warehouses.show',
                                $warehouse->id
                            ) }}"
                               class="btn btn-sm btn-outline">
                                View
                            </a>


                            {{-- Edit --}}
                            <a href="{{ route(
                                'backend.warehouses.edit',
                                $warehouse->id
                            ) }}"
                               class="edit-btn">
                                Edit
                            </a>


                            {{-- Delete --}}
                            <button
                                type="button"
                                class="delete-btn delete"
                                data-id="{{ $warehouse->id }}"
                                data-name="{{ $warehouse->name }}">
                                Delete
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            style="
                                text-align:center;
                                padding:50px 20px;
                                color:#6b7280;
                            ">

                            <div style="
                                font-size:40px;
                                margin-bottom:10px;
                            ">
                                🏭
                            </div>

                            <strong style="
                                font-size:16px;
                            ">
                                No warehouses found
                            </strong>

                            <p style="
                                margin:8px 0 15px;
                            ">
                                Create your first relief warehouse.
                            </p>

                            <a href="{{ route(
                                'backend.warehouses.create'
                            ) }}"
                               class="btn btn-sm btn-primary">
                                + Add Warehouse
                            </a>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($warehouses->hasPages())

        <div class="pagination" style="
            margin-top:20px;
        ">
            {{ $warehouses->links() }}
        </div>

    @endif

</div>


{{-- Delete Modal --}}
<div class="modal fade"
     id="deleteModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Delete Warehouse
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body">

                <p>
                    Are you sure you want to delete
                    <strong id="warehouseName"></strong>?
                </p>

                <p style="
                    color:#dc2626;
                    font-size:14px;
                    margin-bottom:0;
                ">
                    This action cannot be undone.
                </p>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="cancel-btn"
                    data-bs-dismiss="modal">
                    Cancel
                </button>


                <form
                    action=""
                    method="POST"
                    id="deleteForm">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="delete-btn">
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

    $('tbody').on('click', '.delete', function () {

        let id = $(this).data('id');

        let name = $(this).data('name');

        $('#warehouseName').text(name);

        $('#deleteForm').attr(
            'action',
            '{{ url("backend/warehouses") }}/' + id
        );

        $('#deleteModal').modal('show');

    });

});

</script>

@endsection
