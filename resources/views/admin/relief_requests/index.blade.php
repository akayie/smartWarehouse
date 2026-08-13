@extends('layouts.admin')

@section('title', 'Relief Requests')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Relief Requests Management</h5>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Requester</th>
                        <th>Disaster</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reliefRequests as $request)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $request->requestedBy->name ?? $request->user->name ?? 'N/A' }}</td>
                            <td>{{ $request->disaster->title ?? $request->disaster->name ?? '-' }}</td>
                            <td>
                                @if($request->status === 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($request->status === 'Approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-secondary">{{ $request->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('backend.relief_requests.show', $request->id) }}" class="btn btn-sm btn-info">View</a>

                                @if($request->status === 'Pending')
                                    <form action="{{ route('backend.relief_requests.approve', $request->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Confirm approval?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Approve
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No relief requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $reliefRequests->links() }}
        </div>
    </div>
</div>
@endsection
