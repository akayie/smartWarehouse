@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>ဘေးအန္တရာယ် စာရင်း</h3>
        <a href="{{ route('backend.disasters.create') }}" class="btn btn-primary btn-sm">+ အသစ်ထည့်ရန်</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>အမည်</th>
                        <th>အမျိုးအစား</th>
                        <th>တည်နေရာ</th>
                        <th>အကူအညီတောင်းခံမှု</th>
                        <th>အခြေအနေ</th>
                        <th>လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disasters as $disaster)
                        <tr>
                            <td>{{ $disaster->id }}</td>
                            <td>{{ $disaster->name }}</td>
                            <td>{{ $disaster->type ?? '-' }}</td>
                            <td>{{ $disaster->location }}</td>
                            <td><span class="badge bg-info">{{ $disaster->relief_requests_count }} ခု</span></td>
                            <td>
                                <span class="badge bg-{{ $disaster->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($disaster->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('backend.disasters.edit', $disaster->id) }}" class="btn btn-sm btn-outline-primary">ပြင်ရန်</a>
                                <form action="{{ route('backend.disasters.destroy', $disaster->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('ဖျက်ရန် သေချာပါသလား?')">ဖျက်ရန်</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">ဒေတာ မရှိသေးပါ။</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">
        {{ $disasters->links() }}
    </div>
</div>
@endsection
