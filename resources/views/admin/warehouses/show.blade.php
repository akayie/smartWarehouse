@extends('layouts.admin')

@section('title')
    Warehouse Details
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
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:20px;
        margin-bottom:25px;
        padding-bottom:15px;
        border-bottom:1px solid #e5e7eb;
        flex-wrap:wrap;
    ">

        <div>

            <h3 style="
                margin:0 0 5px;
            ">
                Warehouse Details
            </h3>

            <p style="
                margin:0;
                color:#6b7280;
                font-size:14px;
            ">
                View complete warehouse information.
            </p>

        </div>


        <div style="
            display:flex;
            align-items:center;
            gap:10px;
        ">

            <span style="
                padding:7px 12px;
                background:#f3f4f6;
                border-radius:7px;
                font-size:13px;
                font-weight:600;
                color:#374151;
            ">

                WH-{{ str_pad(
                    $warehouse->id,
                    3,
                    '0',
                    STR_PAD_LEFT
                ) }}

            </span>


            <a
                href="{{ route(
                    'backend.warehouses.edit',
                    $warehouse->id
                ) }}"
                class="edit-btn">

                Edit Warehouse

            </a>

        </div>

    </div>


    {{-- Summary Cards --}}
    <div style="
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:15px;
        margin-bottom:25px;
    ">


        {{-- Warehouse --}}
        <div style="
            padding:20px;
            border:1px solid #e5e7eb;
            border-radius:10px;
            background:#f8fafc;
        ">

            <div style="
                font-size:13px;
                color:#6b7280;
                margin-bottom:8px;
            ">
                Warehouse
            </div>

            <strong style="
                font-size:18px;
            ">
                {{ $warehouse->name }}
            </strong>

        </div>


        {{-- Manager --}}
        <div style="
            padding:20px;
            border:1px solid #e5e7eb;
            border-radius:10px;
            background:#f8fafc;
        ">

            <div style="
                font-size:13px;
                color:#6b7280;
                margin-bottom:8px;
            ">
                Manager
            </div>

            <strong style="
                font-size:18px;
            ">
                {{ $warehouse->manager->name ?? 'No Manager' }}
            </strong>

        </div>


        {{-- Status --}}
        <div style="
            padding:20px;
            border:1px solid #e5e7eb;
            border-radius:10px;
            background:#f8fafc;
        ">

            <div style="
                font-size:13px;
                color:#6b7280;
                margin-bottom:8px;
            ">
                Status
            </div>


            @if($warehouse->status === 'Active')

                <span class="badge badge-success">
                    Active
                </span>

            @else

                <span class="badge badge-danger">
                    Inactive
                </span>

            @endif

        </div>

    </div>


    {{-- Warehouse Information --}}
    <div style="
        margin-bottom:25px;
    ">

        <h3 style="
            margin:0 0 15px;
            font-size:18px;
        ">
            Warehouse Information
        </h3>


        <div style="
            overflow-x:auto;
        ">

            <table class="data-table">

                <tbody>

                    {{-- ID --}}
                    <tr>

                        <th style="
                            width:220px;
                        ">
                            Warehouse ID
                        </th>

                        <td>
                            #{{ $warehouse->id }}
                        </td>

                    </tr>


                    {{-- Code --}}
                    <tr>

                        <th>
                            Warehouse Code
                        </th>

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

                    </tr>


                    {{-- Name --}}
                    <tr>

                        <th>
                            Warehouse Name
                        </th>

                        <td>

                            <strong>
                                {{ $warehouse->name }}
                            </strong>

                        </td>

                    </tr>


                    {{-- Location --}}
                    <tr>

                        <th>
                            Location
                        </th>

                        <td>
                            {{ $warehouse->location ?: 'N/A' }}
                        </td>

                    </tr>


                    {{-- Phone --}}
                    <tr>

                        <th>
                            Contact Phone
                        </th>

                        <td>
                            {{ $warehouse->phone ?: 'N/A' }}
                        </td>

                    </tr>


                    {{-- Manager --}}
                    <tr>

                        <th>
                            Warehouse Manager
                        </th>

                        <td>

                            @if($warehouse->manager)

                                {{ $warehouse->manager->name }}

                            @else

                                <span style="
                                    color:#6b7280;
                                ">
                                    No Manager Assigned
                                </span>

                            @endif

                        </td>

                    </tr>


                    {{-- Manager Email --}}
                    <tr>

                        <th>
                            Manager Email
                        </th>

                        <td>

                            @if($warehouse->manager)

                                {{ $warehouse->manager->email ?: '-' }}

                            @else

                                -

                            @endif

                        </td>

                    </tr>


                    {{-- Status --}}
                    <tr>

                        <th>
                            Status
                        </th>

                        <td>

                            @if($warehouse->status === 'Active')

                                <span class="badge badge-success">
                                    Active
                                </span>

                            @else

                                <span class="badge badge-danger">
                                    Inactive
                                </span>

                            @endif

                        </td>

                    </tr>


                    {{-- Created --}}
                    <tr>

                        <th>
                            Created At
                        </th>

                        <td>

                            {{ $warehouse->created_at
                                ? $warehouse->created_at->format(
                                    'd-m-Y H:i:s'
                                )
                                : '-'
                            }}

                        </td>

                    </tr>


                    {{-- Updated --}}
                    <tr>

                        <th>
                            Last Updated
                        </th>

                        <td>

                            {{ $warehouse->updated_at
                                ? $warehouse->updated_at->format(
                                    'd-m-Y H:i:s'
                                )
                                : '-'
                            }}

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>


    {{-- Bottom Actions --}}
    <div style="
        display:flex;
        gap:10px;
        padding-top:20px;
        border-top:1px solid #e5e7eb;
    ">

        <a
            href="{{ route(
                'backend.warehouses.edit',
                $warehouse->id
            ) }}"
            class="edit-btn">

            Edit Warehouse

        </a>


        <a
            href="{{ route(
                'backend.warehouses.index'
            ) }}"
            class="cancel-btn">

            Back to Warehouses

        </a>

    </div>

</div>

@endsection
