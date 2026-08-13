@extends('layouts.admin')

@section('title')
    Donors
@endsection

@section('button')
<a href="{{ route('backend.donors.create') }}" class="btn btn-primary">
    + Add Donor
</a>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h4>Donor Management</h4>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search Form --}}
        <form method="GET" action="{{ route('backend.donors.index') }}" class="row g-3 mb-4">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by Name, Phone, Email or Address..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Search</button>
            </div>
            @if(request('search'))
                <div class="col-md-2">
                    <a href="{{ route('backend.donors.index') }}" class="btn btn-outline-danger w-100">Clear</a>
                </div>
            @endif
        </form>

        {{-- Donors Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Total Donations</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($donors as $donor)
                        <tr>
                            <td>
                                {{ $loop->iteration + ($donors->currentPage() - 1) * $donors->perPage() }}
                            </td>

                            <td>
                                {{ $donor->name }}
                            </td>

                            <td>
                                {{ $donor->phone ?? '-' }}
                            </td>

                            <td>
                                {{ $donor->email ?? '-' }}
                            </td>

                            <td>
                                {{ $donor->address ?? '-' }}
                            </td>

                            <td>
                                <span class="badge bg-info">{{ $donor->donations_count ?? 0 }} Times</span>
                            </td>

                            <td>
                                <a href="{{ route('backend.donors.show', $donor->id) }}" class="btn btn-sm btn-info">
                                    View
                                </a>

                                <a href="{{ route('backend.donors.edit', $donor->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('backend.donors.destroy', $donor->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this donor?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No donors found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $donors->links() }}
        </div>
    </div>
</div>
@endsection
