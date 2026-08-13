@extends('layouts.admin')

@section('title')
    Inventory Details
@endsection

@section('button')
    <a href="{{ route('backend.inventories.index') }}" class="add-btn">
        ← Back
    </a>
@endsection

@section('content')

<div class="card">

    <div style="margin-bottom:20px; padding-bottom:12px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h3 style="margin:0;">Inventory Record Details</h3>
            <p style="margin:4px 0 0; color:#6b7280; font-size:14px;">
                Detailed breakdown of warehouse stock and allocation status.
            </p>
        </div>
        <div style="display:flex; gap:10px; align-items:center;">
            <span style="background:#f3f4f6; padding:6px 12px; border-radius:6px; font-size:13px; font-weight:600; color:#374151;">
                INV-{{ str_pad($inventory->id, 4, '0', STR_PAD_LEFT) }}
            </span>
            <a href="{{ route('backend.inventories.edit', $inventory->id) }}" class="edit-btn">Edit Record</a>
        </div>
    </div>

    @php
        $quantity = $inventory->quantity;
        $allocated = $inventory->allocated_quantity ?? 0;
        $available = max(0, $quantity - $allocated);
        $minimumStock = $inventory->item->minimum_stock ?? 0;
    @endphp

    <!-- Metric Summary Cards -->
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:15px; margin-bottom:25px;">
        <div style="padding:16px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">
            <div style="color:#6b7280; font-size:13px; margin-bottom:6px;">On Hand Quantity</div>
            <div style="font-size:22px; font-weight:700; color:#111827;">
                {{ number_format($quantity) }} <span style="font-size:14px; font-weight:normal; color:#6b7280;">{{ $inventory->item->unit ?? 'Units' }}</span>
            </div>
        </div>

        <div style="padding:16px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">
            <div style="color:#6b7280; font-size:13px; margin-bottom:6px;">Allocated Quantity</div>
            <div style="font-size:22px; font-weight:700; color:#111827;">
                {{ number_format($allocated) }} <span style="font-size:14px; font-weight:normal; color:#6b7280;">{{ $inventory->item->unit ?? 'Units' }}</span>
            </div>
        </div>

        <div style="padding:16px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">
            <div style="color:#6b7280; font-size:13px; margin-bottom:6px;">Available Quantity</div>
            <div style="font-size:22px; font-weight:700; color:#047857;">
                {{ number_format($available) }} <span style="font-size:14px; font-weight:normal; color:#6b7280;">{{ $inventory->item->unit ?? 'Units' }}</span>
            </div>
        </div>

        <div style="padding:16px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;">
            <div style="color:#6b7280; font-size:13px; margin-bottom:6px;">Stock Status</div>
            <div style="margin-top:4px;">
                @if($available <= $minimumStock)
                    <span class="badge badge-danger" style="background:#fef2f2; color:#b91c1c; padding:6px 12px; border-radius:6px; font-size:13px; font-weight:600; display:inline-block;">
                        Low Stock
                    </span>
                @else
                    <span class="badge badge-success" style="background:#ecfdf5; color:#047857; padding:6px 12px; border-radius:6px; font-size:13px; font-weight:600; display:inline-block;">
                        Available
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Information Grid -->
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:25px;">

        <!-- Relief Item Information -->
        <div style="border:1px solid #e5e7eb; border-radius:8px; padding:18px; background:#ffffff;">
            <h4 style="margin:0 0 14px; padding-bottom:8px; border-bottom:1px solid #f3f4f6; color:#374151;">
                Relief Item Details
            </h4>
            <table style="width:100%; font-size:14px; border-collapse:collapse;">
                <tr>
                    <td style="padding:8px 0; color:#6b7280; width:40%;">Item Name:</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">{{ $inventory->item->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#6b7280;">Unit of Measure:</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">{{ $inventory->item->unit ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#6b7280;">Minimum Stock Threshold:</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">
                        {{ number_format($inventory->item->minimum_stock ?? 0) }} {{ $inventory->item->unit ?? '' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Warehouse Information -->
        <div style="border:1px solid #e5e7eb; border-radius:8px; padding:18px; background:#ffffff;">
            <h4 style="margin:0 0 14px; padding-bottom:8px; border-bottom:1px solid #f3f4f6; color:#374151;">
                Warehouse Details
            </h4>
            <table style="width:100%; font-size:14px; border-collapse:collapse;">
                <tr>
                    <td style="padding:8px 0; color:#6b7280; width:40%;">Warehouse Name:</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">{{ $inventory->warehouse->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#6b7280;">Location:</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">{{ $inventory->warehouse->location ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0; color:#6b7280;">Contact Person:</td>
                    <td style="padding:8px 0; font-weight:600; color:#111827;">{{ $inventory->warehouse->contact_person ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

    </div>

    <!-- Timestamp Information -->
    <div style="display:flex; gap:20px; font-size:13px; color:#6b7280; padding-top:15px; border-top:1px solid #e5e7eb;">
        <div><strong>Created At:</strong> {{ $inventory->created_at ? $inventory->created_at->format('M d, Y H:i A') : 'N/A' }}</div>
        <div><strong>Last Updated:</strong> {{ $inventory->updated_at ? $inventory->updated_at->format('M d, Y H:i A') : 'N/A' }}</div>
    </div>

    <!-- Action Buttons -->
    <div style="display:flex; gap:10px; margin-top:25px; padding-top:20px; border-top:1px solid #e5e7eb;">
        <a href="{{ route('backend.inventories.edit', $inventory->id) }}" class="save-btn" style="text-decoration:none; text-align:center;">Edit Inventory</a>
        <a href="{{ route('backend.inventories.index') }}" class="cancel-btn" style="text-decoration:none; text-align:center;">Back to List</a>
    </div>

</div>

@endsection
