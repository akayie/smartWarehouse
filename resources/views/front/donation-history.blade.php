@extends('layouts.front') {{-- သင့် Project ၏ Layout ဖိုင်အမည် ပါဝင်ပါစေ --}}

@section('content')
<!-- PAGE 6: DONATION HISTORY -->
<div id="pub-don-history" class="sub-page">
    <div class="card">
        <div class="card-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div>
                <h2><i class="fa-solid fa-clock-rotate-left icon-blue"></i> Donation History</h2>
                <p class="section-desc">A verified record of your contributions and their allocation status.</p>
            </div>
            <a href="{{ route('public.donate.create') }}" class="btn btn-sm btn-primary">+ Make Donation</a>
        </div>

        <div class="table-responsive">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Donation ID</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Details</th>
                        <th>Allocated Warehouse</th>
                        <th>Status</th>
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
                                    {{ number_format($donation->payment->amount ?? 0) }} MMK
                                @else
                                    @foreach($donation->items as $donatedItem)
                                        <div>{{ $donatedItem->item->name ?? 'Item' }} ({{ $donatedItem->quantity }})</div>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $donation->warehouse->name ?? 'Default Warehouse' }}</td>
                            <td>
                                @if($donation->status === 'Verified' || $donation->status === 'Completed' || $donation->status === 'Distributed')
                                    <span class="badge badge-success">{{ $donation->status }}</span>
                                @elseif($donation->status === 'Pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-secondary">{{ $donation->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 20px;">
                                No donation records found. <a href="{{ route('public.donate.create') }}">Make your first donation</a>
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
