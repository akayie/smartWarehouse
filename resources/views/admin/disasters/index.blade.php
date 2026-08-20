@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">ဘေးအန္တရာယ် စာရင်း</h3>
        <a href="{{ route('backend.disasters.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> အသစ်ထည့်ရန်
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>အမည်</th>
                            <th>အမျိုးအစား</th>
                            <th>တည်နေရာ</th>
                            <th>အကူအညီတောင်းခံမှု</th>
                            <th>အခြေအနေ</th>
                            <th class="text-end pe-3">လုပ်ဆောင်ချက်</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disasters as $index => $disaster)
                            <tr>
                                {{-- Pagination အလိုက် စဉ် အမှန်ပေါ်စေရန် --}}
                                <td class="ps-3">{{ $disasters->firstItem() + $index }}</td>
                                <td class="fw-bold">{{ $disaster->name }}</td>
                                <td>{{ $disaster->type ?? '-' }}</td>
                                <td>{{ $disaster->location }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $disaster->relief_requests_count }} ခု
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $disaster->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($disaster->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    {{-- Show Details --}}
                                    <a href="{{ route('backend.disasters.show', $disaster->id) }}" class="btn btn-sm btn-outline-info me-1">
                                        ကြည့်ရန်
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('backend.disasters.edit', $disaster->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                        ပြင်ရန်
                                    </a>

                                    {{-- Delete Form --}}
                                    <form action="{{ route('backend.disasters.destroy', $disaster->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('ဖျက်ရန် သေချာပါသလား?')">
                                            ဖျက်ရန်
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    ဒေတာ မရှိသေးပါ။
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination Links --}}
    @if($disasters->hasPages())
        <div class="mt-3">
            {{ $disasters->links() }}
        </div>
    @endif
</div>
@endsection
