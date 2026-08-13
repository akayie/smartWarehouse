@extends('layouts.admin')

@section('title')
    အသုံးပြုသူများနှင့် တာဝန်သတ်မှတ်ချက်များ
@endsection

@section('button')
    <a href="{{ route('backend.users.create') }}" class="btn btn-primary">
        + အသုံးပြုသူအသစ်ထည့်ရန်
    </a>
@endsection

@section('content')

{{-- Subtitle / Tagline Banner Section --}}
<div class="alert alert-light border shadow-sm mb-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-heart-pulse-fill text-danger fs-3 me-3"></i>
        <div>
            <h6 class="fw-bold mb-1">အရေးပေါ် ကူညီကယ်ဆယ်ရေး ကွန်ရက်</h6>
            <p class="text-muted mb-0 small">
                လှူဒါန်းသူများ၊ ကုန်လှောင်ရုံများနှင့် သဘာဝဘေးအန္တရာယ် ကူညီကယ်ဆယ်ရေးစခန်းများကို ချိတ်ဆက်ပေးပြီး အရေးပေါ်အကူအညီများကို အလိုအပ်ဆုံးနေရာများသို့ အချိန်မီ ပေးပို့ပေးပါသည်။
            </p>
        </div>
    </div>
</div>

<div class="card shadow-sm">

    {{-- Card Header --}}
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1 fw-bold">
                အသုံးပြုသူအကောင့်များနှင့် လုပ်ပိုင်ခွင့်အဆင့်များ
            </h4>
            <p class="text-muted mb-0 small">
                စနစ်အတွင်းရှိ အသုံးပြုသူများ၊ တာဝန်များနှင့် အကောင့်အခြေအနေများကို စီမံခန့်ခွဲရန်။
            </p>
        </div>

        <a href="{{ route('backend.users.create') }}" class="btn btn-sm btn-primary">
            + အသုံးပြုသူအသစ်ထည့်ရန်
        </a>
    </div>

    <div class="card-body">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60" class="text-center">#</th>
                        <th>အသုံးပြုသူ</th>
                        <th>အီးမေးလ်</th>
                        <th>ဖုန်းနံပါတ်</th>
                        <th>တာဝန် / Role</th>
                        <th>တာဝန်ကျစခန်း (Hub)</th>
                        <th>အခြေအနေ</th>
                        <th width="160" class="text-center">လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            {{-- Row Number --}}
                            <td class="text-center fw-bold">
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            {{-- Avatar & Name --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($user->profile)
                                        <img src="{{ asset($user->profile) }}"
                                             width="40"
                                             height="40"
                                             class="rounded-circle me-2"
                                             style="object-fit: cover;"
                                             alt="{{ $user->name }}">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2 fw-bold"
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div>
                                        <strong class="d-block">{{ $user->name }}</strong>
                                    </div>
                                </div>
                            </td>

                            {{-- Email --}}
                            <td>{{ $user->email }}</td>

                            {{-- Phone --}}
                            <td>{{ $user->phone ?? '-' }}</td>

                            {{-- Role --}}
                            <td>
                                @if($user->role === 'Super Admin')
                                    <span class="badge bg-danger">Super Admin</span>
                                @elseif($user->role === 'Admin')
                                    <span class="badge bg-primary">Admin</span>
                                @elseif($user->role === 'Warehouse Manager')
                                    <span class="badge bg-info text-dark">Warehouse Manager</span>
                                @else
                                    <span class="badge bg-secondary">{{ $user->role ?? 'User' }}</span>
                                @endif
                            </td>

                            {{-- Assigned Hub / Warehouse --}}
                            <td>
                                @if(isset($user->warehouse))
                                    <span class="fw-semibold">{{ $user->warehouse->name }}</span>
                                @else
                                    <span class="text-muted small">သတ်မှတ်ထားခြင်းမရှိပါ</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td>
                                @if(isset($user->status))
                                    @if($user->status === 'Active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($user->status === 'Inactive')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ $user->status }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>

                            {{-- Action Buttons --}}
                            <td class="text-center">
                                <a href="{{ route('backend.users.edit', $user->id) }}"
                                   class="btn btn-sm btn-outline-warning me-1">
                                    ပြင်ဆင်မည်
                                </a>

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger delete-user"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}">
                                    ဖျက်မည်
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <h5 class="fw-bold">အသုံးပြုသူ စာရင်းမရှိပါ</h5>
                                    <p class="small mb-3">လက်ရှိတွင် မည်သည့် အသုံးပြုသူအကောင့်မှ မရှိသေးပါ။</p>
                                    <a href="{{ route('backend.users.create') }}" class="btn btn-primary btn-sm">
                                        + အသုံးပြုသူအသစ် ဖန်တီးမည်
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3 d-flex justify-content-end">
            {{ $users->links() }}
        </div>

    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">အသုံးပြုသူအား စာရင်းမှဖျက်ရန်</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    အသုံးပြုသူ <strong id="deleteUserName" class="text-danger"></strong> အား စာရင်းမှ ဖျက်ရန် သေချာပါသလား?
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    မဖျက်တော့ပါ
                </button>

                <form action="" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        သေချာသည်၊ ဖျက်မည်
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
            $('#deleteForm').attr('action', '{{ url("admin/users") }}/' + id);
            $('#deleteModal').modal('show');
        });
    });
</script>
@endsection
