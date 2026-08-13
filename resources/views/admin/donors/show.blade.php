@extends('layouts.admin')

@section('title')
    Donor Details
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

        <h4>Donor Details</h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>

                <th width="200">
                    Donor Name
                </th>

                <td>
                    {{ $donor->name }}
                </td>

            </tr>

            <tr>

                <th>
                    Phone
                </th>

                <td>
                    {{ $donor->phone ?? '-' }}
                </td>

            </tr>

            <tr>

                <th>
                    Email
                </th>

                <td>
                    {{ $donor->email ?? '-' }}
                </td>

            </tr>

            <tr>

                <th>
                    Address
                </th>

                <td>
                    {{ $donor->address ?? '-' }}
                </td>

            </tr>

            <tr>

                <th>
                    Created At
                </th>

                <td>
                    {{ $donor->created_at->format(
                        'd-m-Y H:i:s'
                    ) }}
                </td>

            </tr>

            <tr>

                <th>
                    Updated At
                </th>

                <td>
                    {{ $donor->updated_at->format(
                        'd-m-Y H:i:s'
                    ) }}
                </td>

            </tr>

        </table>

    </div>

</div>

@endsection
