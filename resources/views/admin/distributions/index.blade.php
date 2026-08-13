@extends('layouts.admin')

@section('title', 'Distributions List')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Distributions Record</h2>
        <a href="{{ route('backend.distributions.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> New Distribution
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Warehouse</th>
                        <th>Target Request</th>
                        <th>Items Count</th>
                        <th>Handled By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distributions as $distribution)
                        <tr>
                            <td>#{{ $distribution->id }}</td>
                            <td>{{ $distribution->distribution_date->format('Y-m-d') }}</td>
                            <td>{{ $distribution->warehouse->name ?? 'N/A' }}</td>
                            <td>{{ $distribution->request ? 'Req #'.$distribution->request->id.' ('.$distribution->request->location.')' : 'Direct Distribution' }}</td>
                            <td><span class="badge bg-info">{{ $distribution->distributionItems->count() }} Items</span></td>
                            <td>{{ $distribution->handledBy->name ?? 'System' }}</td>
                            <td>
                                <a href="{{ route('admin.distributions.show', $distribution->id) }}" class="btn btn-sm btn-info">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No distribution records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $distributions->links() }}
        </div>
    </div>
</div>
@endsection
