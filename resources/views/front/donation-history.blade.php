@extends('layouts.front') {{-- သင့် Project ၏ Layout ဖိုင်အမည် ပါဝင်ပါစေ --}}

@section('content')
<!-- PAGE 6: DONATION HISTORY -->
<div id="pub-don-history" class="sub-page">
    <div class="card">
        <div class="card-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div>
                <h2><i class="fa-solid fa-clock-rotate-left icon-blue"></i> လှူဒါန်းမှု မှတ်တမ်း</h2>
                <p class="section-desc">သင်၏ လှူဒါန်းမှုများနှင့် ယင်းတို့၏ ခွဲဝေဆောင်ရွက်မှု အခြေအနေ စိစစ်ပြီး မှတ်တမ်း။</p>
            </div>
            <a href="{{ route('public.donate.create') }}" class="btn btn-sm btn-primary">+ လှူဒါန်းမှု ပြုလုပ်ရန်</a>
        </div>

        <div class="table-responsive">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>လှူဒါန်းမှု အမှတ်</th>
                        <th>ရက်စွဲ</th>
                        <th>အမျိုးအစား</th>
                        <th>အသေးစိတ်</th>
                        <th>လက်ခံသိမ်းဆည်းသည့် ဂိုဒေါင်</th>
                        <th>အခြေအနေ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                        <tr>
                            <td><strong>#DON-{{ str_pad($donation->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($donation->donation_date)->format('Y-m-d') }}</td>
                            <td>{{ $donation->donation_type }}</td>
                            <td>
                                @if($donation->donation_type === 'Cash')
                                    {{ number_format($donation->payment->amount ?? 0) }} ကျပ်
                                @else
                                    @foreach($donation->items as $donatedItem)
                                        <div>{{ $donatedItem->item->name ?? 'ပစ္စည်း' }} ({{ $donatedItem->quantity }})</div>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $donation->warehouse->name ?? 'ပင်မဂိုဒေါင်' }}</td>
                            <td>
                                @if($donation->status === 'Verified' || $donation->status === 'Completed' || $donation->status === 'Distributed')
                                    <span class="badge badge-success">{{ $donation->status }}</span>
                                @elseif($donation->status === 'Pending')
                                    <span class="badge badge-warning">စိစစ်ဆဲ</span>
                                @else
                                    <span class="badge badge-secondary">{{ $donation->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 20px;">
                                လှူဒါန်းမှု မှတ်တမ်းများ မရှိသေးပါ။ <a href="{{ route('public.donate.create') }}">ပထမဆုံး လှူဒါန်းမှု စတင်ပြုလုပ်ရန်</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 15px;">
            {{ $donations->links() }}
        </div>
    </div>
</div>
@endsection
