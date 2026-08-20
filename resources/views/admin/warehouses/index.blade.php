@extends('layouts.admin')

@section('title')
    ကုန်လှောင်ရုံများ
@endsection

@section('button')
    <a href="{{ route('backend.warehouses.create') }}" class="btn btn-sm btn-primary">
        + ကုန်လှောင်ရုံ အသစ်ထည့်မည်
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
            <h3 style="margin:0 0 5px 0;">
                ကုန်လှောင်ရုံများ စီမံခန့်ခွဲမှု
            </h3>
            <p style="margin:0; color:#6b7280; font-size:14px;">
                ကူညီကယ်ဆယ်ရေး ကုန်လှောင်ရုံများ၊ တည်နေရာများနှင့် တာဝန်ခံ မန်နေဂျာများကို စီမံခန့်ခွဲပါ။
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
            စုစုပေါင်း - {{ $warehouses->total() }}
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
            <strong>ကျေးဇူးပြု၍ အောက်ပါအချက်များကို စစ်ဆေးပါ -</strong>
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
                    <th>ကုဒ်နံပါတ်</th>
                    <th>အမည်</th>
                    <th>တည်နေရာ</th>
                    <th>ဖုန်းနံပါတ်</th>
                    <th>မန်နေဂျာ / တာဝန်ခံ</th>
                    <th>အခြေအနေ</th>
                    <th style="text-align:center;">လုပ်ဆောင်ချက်</th>
                </tr>
            </thead>

            <tbody>
                @forelse($warehouses as $warehouse)
                    <tr>
                        {{-- Code --}}
                        <td>
                            <strong>
                                WH-{{ str_pad($warehouse->id, 3, '0', STR_PAD_LEFT) }}
                            </strong>
                        </td>

                        {{-- Name --}}
                        <td>
                            <strong>{{ $warehouse->name }}</strong>
                        </td>

                        {{-- Location --}}
                        <td>
                            {{ $warehouse->location ?: '-' }}
                        </td>

                        {{-- Phone --}}
                        <td>
                            {{ $warehouse->phone ?: '-' }}
                        </td>

                        {{-- Manager / Responsible Person --}}
                        <td>
                            @if($warehouse->user)
                                {{ $warehouse->user->name }}
                            @else
                                <span style="color:#9ca3af;">
                                    တာဝန်ပေးထားခြင်း မရှိပါ
                                </span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td>
                            @if(strtolower($warehouse->status) === 'active')
                                <span class="badge badge-success">
                                    အသုံးပြုနေဆဲ
                                </span>
                            @else
                                <span class="badge badge-secondary">
                                    ပိတ်ထားသည်
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td style="text-align:center; white-space:nowrap;">
                            {{-- View --}}
                            <a href="{{ route('backend.warehouses.show', $warehouse->id) }}" class="btn btn-sm btn-outline">
                                ကြည့်မည်
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('backend.warehouses.edit', $warehouse->id) }}" class="edit-btn">
                                ပြင်ဆင်မည်
                            </a>

                            {{-- Delete --}}
                            <button type="button"
                                    class="delete-btn delete"
                                    data-id="{{ $warehouse->id }}"
                                    data-name="{{ $warehouse->name }}">
                                ဖျက်မည်
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:50px 20px; color:#6b7280;">
                            <div style="font-size:40px; margin-bottom:10px;">🏭</div>
                            <strong style="font-size:16px;">ကုန်လှောင်ရုံ မရှိသေးပါ</strong>
                            <p style="margin:8px 0 15px;">
                                ပထမဦးဆုံး ကူညီကယ်ဆယ်ရေး ကုန်လှောင်ရုံကို စတင်ဖန်တီးပါ။
                            </p>
                            <a href="{{ route('backend.warehouses.create') }}" class="btn btn-sm btn-primary">
                                + ကုန်လှောင်ရုံ အသစ်ထည့်မည်
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($warehouses->hasPages())
        <div class="pagination" style="margin-top:20px;">
            {{ $warehouses->links() }}
        </div>
    @endif

</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ကုန်လှောင်ရုံကို ဖျက်မည်</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>
                    သင်သည် <strong id="warehouseName"></strong> ကို ဖျက်ရန် သေချာပါသလား။
                </p>
                <p style="color:#dc2626; font-size:14px; margin-bottom:0;">
                    ဤလုပ်ဆောင်ချက်ကို ပြန်လည်ပြင်ဆင်၍ ရနိုင်မည်မဟုတ်ပါ။
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="cancel-btn" data-bs-dismiss="modal">
                    မလုပ်တော့ပါ
                </button>

                <form action="" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">
                        ဟုတ်ကဲ့၊ ဖျက်မည်
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
        $('#deleteForm').attr('action', '{{ url("backend/warehouses") }}/' + id);
        $('#deleteModal').modal('show');
    });
});
</script>
@endsection
