@extends('layouts.admin')

@section('title', 'Main Dashboard')

@section('content')
<!-- 1. DASHBOARD -->
<div id="adm-dashboard" class="sub-page active">
    <!-- Stat Cards Section -->
    <div class="grid-4">
        <!-- Total Inventory Card -->
        <div class="card stat-card">
            <div>
                <p style="font-size: 0.8rem; color: var(--text-muted);">စုစုပေါင်း လက်ကျန်ပစ္စည်း</p>
                <div class="val">{{ number_format($totalInventory) }} ခု</div>
                <span class="badge badge-success" style="margin-top: 5px;">တိုက်ရိုက် ပြင်ဆင်ထားသည်</span>
            </div>
            <div class="icon-box" style="background: #e0f2fe; color: #0284c7;">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- Pending Requests Card -->
        <div class="card stat-card">
            <div>
                <p style="font-size: 0.8rem; color: var(--text-muted);">စောင့်ဆိုင်းဆဲ တောင်းခံလွှာများ</p>
                <div class="val" style="color: var(--warning-orange);">{{ $pendingRequests }} စာစောင်</div>
                <span class="badge badge-warning" style="margin-top: 5px;">ဆောင်ရွက်ရန် လိုအပ်သည်</span>
            </div>
            <div class="icon-box" style="background: #ffedd5; color: #ea580c;">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <!-- Active Dispatches Card -->
        {{-- <div class="card stat-card">
            <div>
                <p style="font-size: 0.8rem; color: var(--text-muted);">လက်ရှိ ထောက်ပံ့ပို့ဆောင်မှုများ</p>
                <div class="val" style="color: var(--primary-blue);">{{ $activeDispatches }} လမ်းခရီးတွင်</div>
                <span class="badge badge-info" style="margin-top: 5px;">ပို့ဆောင်ဆဲ</span>
            </div>
            <div class="icon-box" style="background: #dbeafe; color: #2563eb;">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
        </div> --}}

        <!-- Low Stock Alerts Card -->
        <div class="card stat-card">
            <div>
                <p style="font-size: 0.8rem; color: var(--text-muted);">လက်ကျန်နည်း အသိပေးချက်များ</p>
                <div class="val" style="color: var(--danger-red);">{{ $lowStockCount }} မျိုး</div>
                <span class="badge badge-danger" style="margin-top: 5px;">စိုးရိမ်ရအဆင့်</span>
            </div>
            <div class="icon-box" style="background: #fee2e2; color: #dc2626;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
    </div>

    <!-- Recent Activities Section -->
    <div class="grid-2 mt-4">
        <div class="card">
            <h3>လတ်တလော ကူညီကယ်ဆယ်ရေး လှုပ်ရှားမှုများ</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>အချိန်</th>
                        <th>လုပ်ဆောင်ချက်</th>
                        <th>တည်နေရာ</th>
                        <th>အခြေအနေ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $activity)
                        <tr>
                            <td>{{ $activity->created_at->diffForHumans() }}</td>
                            <td>{{ $activity->title }}</td>
                            <td>{{ $activity->location }}</td>
                            <td>
                                @if($activity->status == 'In Transit')
                                    <span class="badge badge-info">ပို့ဆောင်ဆဲ</span>
                                @elseif($activity->status == 'Verified')
                                    <span class="badge badge-success">စစ်ဆေးပြီး</span>
                                @elseif($activity->status == 'Completed')
                                    <span class="badge badge-success">ပြီးစီး</span>
                                @elseif($activity->status == 'Pending')
                                    <span class="badge badge-warning">စောင့်ဆိုင်းဆဲ</span>
                                @else
                                    <span class="badge badge-secondary">{{ $activity->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">လတ်တလော လှုပ်ရှားမှု မှတ်တမ်းမရှိပါ။</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
