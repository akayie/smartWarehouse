@extends('layouts.admin')

@section('title')
    လက်ကျန်ပစ္စည်း အသေးစိတ်
@endsection

@section('button')
    <a href="{{ route('backend.inventories.index') }}" class="add-btn">
        ← စာရင်းသို့ ပြန်သွားရန်
    </a>
@endsection

@section('content')

<div class="card">

    <div style="margin-bottom:20px; padding-bottom:12px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">

        <div>
            <h3 style="margin:0;">
                လက်ကျန်ပစ္စည်း အသေးစိတ်
            </h3>

            <p style="margin:4px 0 0; color:#6b7280; font-size:14px;">
                သိုလှောင်ရုံအတွင်းရှိ ပစ္စည်းလက်ကျန်နှင့် ခွဲဝေပေးထားမှု အခြေအနေများကို အသေးစိတ်ကြည့်ရှုနိုင်ပါသည်။
            </p>
        </div>

        <div style="display:flex; gap:10px; align-items:center;">

            <span style="background:#f3f4f6; padding:6px 12px; border-radius:6px; font-size:13px; font-weight:600; color:#374151;">
                INV-{{ str_pad($inventory->id, 4, '0', STR_PAD_LEFT) }}
            </span>

            {{-- Policy Authorization ဖြင့် စစ်ဆေး၍ ခွင့်ပြုချက်ရှိသူများသာ Edit Button ကို မြင်ရမည် --}}
            @can('update', $inventory)
                <a
                    href="{{ route('backend.inventories.edit', $inventory->id) }}"
                    class="edit-btn"
                >
                    စာရင်းပြင်ဆင်ရန်
                </a>
            @endcan

        </div>

    </div>

    @php
        $quantity = $inventory->quantity;
        $allocated = $inventory->allocated_quantity ?? 0;
        $available = max(0, $quantity - $allocated);
        $minimumStock = $inventory->item->minimum_stock ?? 0;
    @endphp

    <!-- လက်ကျန်အချက်အလက် အကျဉ်းချုပ် -->
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:15px; margin-bottom:25px;">

        <!-- On Hand Quantity -->
        <div style="padding:16px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">
            <div style="color:#6b7280; font-size:13px; margin-bottom:6px;">
                လက်ရှိလက်ကျန်
            </div>
            <div style="font-size:22px; font-weight:700; color:#111827;">
                {{ number_format($quantity) }}
                <span style="font-size:14px; font-weight:normal; color:#6b7280;">
                    {{ $inventory->item->unit ?? 'ယူနစ်' }}
                </span>
            </div>
        </div>

        <!-- Allocated Quantity -->
        <div style="padding:16px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">
            <div style="color:#6b7280; font-size:13px; margin-bottom:6px;">
                ခွဲဝေပေးထားသော အရေအတွက်
            </div>
            <div style="font-size:22px; font-weight:700; color:#111827;">
                {{ number_format($allocated) }}
                <span style="font-size:14px; font-weight:normal; color:#6b7280;">
                    {{ $inventory->item->unit ?? 'ယူနစ်' }}
                </span>
            </div>
        </div>

        <!-- Available Quantity -->
        <div style="padding:16px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">
            <div style="color:#6b7280; font-size:13px; margin-bottom:6px;">
                အသုံးပြုနိုင်သော လက်ကျန်
            </div>
            <div style="font-size:22px; font-weight:700; color:#047857;">
                {{ number_format($available) }}
                <span style="font-size:14px; font-weight:normal; color:#6b7280;">
                    {{ $inventory->item->unit ?? 'ယူနစ်' }}
                </span>
            </div>
        </div>

        <!-- Stock Status -->
        <div style="padding:16px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">
            <div style="color:#6b7280; font-size:13px; margin-bottom:6px;">
                လက်ကျန်အခြေအနေ
            </div>
            <div style="margin-top:4px;">
                @if($available <= $minimumStock)
                    <span
                        class="badge badge-danger"
                        style="background:#fef2f2; color:#b91c1c; padding:6px 12px; border-radius:6px; font-size:13px; font-weight:600; display:inline-block;"
                    >
                        လက်ကျန်နည်းနေသည်
                    </span>
                @else
                    <span
                        class="badge badge-success"
                        style="background:#ecfdf5; color:#047857; padding:6px 12px; border-radius:6px; font-size:13px; font-weight:600; display:inline-block;"
                    >
                        လုံလောက်သည်
                    </span>
                @endif
            </div>
        </div>

    </div>

    <!-- အသေးစိတ်အချက်အလက်များ -->
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:25px;">

        <!-- Relief Item Information -->
        <div style="border:1px solid #e5e7eb; border-radius:8px; padding:18px; background:#ffffff;">
            <h4 style="margin:0 0 14px; padding-bottom:8px; border-bottom:1px solid #f3f4f6; color:#374151;">
                ကယ်ဆယ်ရေးပစ္စည်း အချက်အလက်
            </h4>

            <table style="width:100%; font-size:14px; border-collapse:collapse;">
                <tr>
                    <td style="padding:8px 0; color:#6b7280; width:40%;">ပစ္စည်းအမည်</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">
                        {{ $inventory->item->name ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#6b7280;">ယူနစ်</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">
                        {{ $inventory->item->unit ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#6b7280;">အနည်းဆုံးလက်ကျန် သတ်မှတ်ချက်</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">
                        {{ number_format($inventory->item->minimum_stock ?? 0) }}
                        {{ $inventory->item->unit ?? '' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Warehouse Information -->
        <div style="border:1px solid #e5e7eb; border-radius:8px; padding:18px; background:#ffffff;">
            <h4 style="margin:0 0 14px; padding-bottom:8px; border-bottom:1px solid #f3f4f6; color:#374151;">
                သိုလှောင်ရုံ အချက်အလက်
            </h4>

            <table style="width:100%; font-size:14px; border-collapse:collapse;">
                <tr>
                    <td style="padding:8px 0; color:#6b7280; width:40%;">သိုလှောင်ရုံအမည်</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">
                        {{ $inventory->warehouse->name ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#6b7280;">တည်နေရာ</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">
                        {{ $inventory->warehouse->location ?? 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#6b7280;">ဆက်သွယ်ရန်ပုဂ္ဂိုလ်</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">
                        {{ $inventory->warehouse->contact_person ?? 'N/A' }}
                    </td>
                </tr>
            </table>
        </div>

    </div>

    <!-- မှတ်တမ်းအချိန်အချက်အလက် -->
    <div style="display:flex; gap:20px; font-size:13px; color:#6b7280; padding-top:15px; border-top:1px solid #e5e7eb;">
        <div>
            <strong>ဖန်တီးသည့်အချိန်:</strong>
            {{ $inventory->created_at ? $inventory->created_at->format('M d, Y H:i A') : 'N/A' }}
        </div>

        <div>
            <strong>နောက်ဆုံးပြင်ဆင်သည့်အချိန်:</strong>
            {{ $inventory->updated_at ? $inventory->updated_at->format('M d, Y H:i A') : 'N/A' }}
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="display:flex; gap:10px; margin-top:25px; padding-top:20px; border-top:1px solid #e5e7eb;">

        @can('update', $inventory)
            <a
                href="{{ route('backend.inventories.edit', $inventory->id) }}"
                class="save-btn"
                style="text-decoration:none; text-align:center;"
            >
                <i class="fas fa-edit me-1"></i>
                လက်ကျန်စာရင်း ပြင်ဆင်ရန်
            </a>
        @endcan

        <a
            href="{{ route('backend.inventories.index') }}"
            class="cancel-btn"
            style="text-decoration:none; text-align:center;"
        >
            <i class="fas fa-arrow-left me-1"></i>
            စာရင်းသို့ ပြန်သွားရန်
        </a>

    </div>

</div>

@endsection
