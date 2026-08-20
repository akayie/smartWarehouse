@extends('layouts.admin')

@section('title', 'ပစ္စည်းဖြန့်ဝေမှုအသစ် ပြုလုပ်ရန်')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-truck-fast text-primary me-2"></i>
                ကယ်ဆယ်ရေးပစ္စည်း ဖြန့်ဝေမှုအသစ်
            </h3>

            <p class="text-muted mb-0">
                အတည်ပြုထားသော ကယ်ဆယ်ရေးတောင်းဆိုချက်အတွက်
                ပစ္စည်းများ ဖြန့်ဝေပေးရန်
            </p>
        </div>

        <a href="{{ route('backend.distributions.index') }}"
           class="btn btn-outline-secondary">

            <i class="fa-solid fa-arrow-left me-1"></i>
            စာရင်းသို့ ပြန်သွားရန်

        </a>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="fa-solid fa-circle-check me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="fa-solid fa-circle-exclamation me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                <i class="fa-solid fa-triangle-exclamation me-1"></i>
                ကျေးဇူးပြု၍ အောက်ပါအချက်များကို ပြင်ဆင်ပါ။
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        MAIN FORM
    ========================================================== --}}

    <form action="{{ route('backend.distributions.store') }}"
          method="POST"
          id="distributionForm">

        @csrf

        <div class="row g-4">


            {{-- =====================================================
                SECTION 1
                DISTRIBUTION INFORMATION
            ====================================================== --}}

            <div class="col-12">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold text-primary mb-0">

                            <i class="fa-solid fa-circle-info me-2"></i>

                            ၁။ ဖြန့်ဝေမှု အချက်အလက်များ

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- =================================================
                                REQUEST
                            ================================================== --}}

                            <div class="col-md-6">

                                <label for="request_id"
                                       class="form-label fw-bold">

                                    အတည်ပြုထားသော
                                    ကယ်ဆယ်ရေးတောင်းဆိုမှု

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="request_id"
                                        id="request_id"
                                        class="form-select @error('request_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- တောင်းဆိုမှု ရွေးချယ်ပါ --
                                    </option>


                                    @foreach($requests as $req)

                                        @php

                                            $requestItemsData = [];

                                            foreach ($req->requestItems as $requestItem) {

                                                $requestItemsData[] = [
                                                    'item_id' => (int) $requestItem->item_id,
                                                    'item_name' => optional($requestItem->item)->name ?? 'ပစ္စည်းမရှိ',
                                                    'unit' => optional($requestItem->item)->unit ?? '',
                                                    'quantity' => (int) $requestItem->quantity,
                                                ];

                                            }

                                            $requestItemsJson = json_encode(
                                                $requestItemsData,
                                                JSON_HEX_TAG |
                                                JSON_HEX_APOS |
                                                JSON_HEX_AMP |
                                                JSON_HEX_QUOT
                                            );

                                        @endphp


                                        <option value="{{ $req->id }}"

                                                data-warehouse="{{ $req->warehouse_id }}"

                                                data-requester="{{ optional($req->requestedBy)->name ?? ($req->name ?? 'အမည်မရှိ') }}"

                                                data-phone="{{ optional($req->requestedBy)->phone ?? ($req->phone_number ?? '') }}"

                                                data-items="{{ $requestItemsJson }}"

                                                {{ old('request_id') == $req->id ? 'selected' : '' }}>

                                            တောင်းဆိုမှု #{{ $req->id }}

                                            -

                                            {{ optional($req->disaster)->name
                                                ?? optional($req->disaster)->title
                                                ?? 'အထွေထွေ ဘေးအန္တရာယ်' }}

                                            -

                                            {{ $req->location }}

                                        </option>

                                    @endforeach

                                </select>


                                <small class="text-muted">

                                    တောင်းဆိုမှုကို ရွေးချယ်ပါက
                                    တောင်းထားသော ပစ္စည်းများနှင့်
                                    Inventory လက်ကျန်ကို
                                    အောက်တွင် အလိုအလျောက် ပြပေးပါမည်။

                                </small>


                                @error('request_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                WAREHOUSE
                            ================================================== --}}

                            <div class="col-md-6">

                                <label for="warehouse_id"
                                       class="form-label fw-bold">

                                    ပစ္စည်းထုတ်ပေးမည့် ဂိုဒေါင်

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="warehouse_id"
                                        id="warehouse_id"
                                        class="form-select @error('warehouse_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- ဂိုဒေါင် ရွေးချယ်ပါ --
                                    </option>


                                    @foreach($warehouses as $warehouse)

                                        <option value="{{ $warehouse->id }}"
                                                {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>

                                            {{ $warehouse->name }}

                                            @if($warehouse->location)

                                                ({{ $warehouse->location }})

                                            @endif

                                        </option>

                                    @endforeach

                                </select>


                                @error('warehouse_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                REQUESTER NAME
                            ================================================== --}}

                            <div class="col-md-6">

                                <label for="requester_name"
                                       class="form-label fw-bold">

                                    တောင်းခံသူအမည်

                                </label>

                                <input type="text"
                                       id="requester_name"
                                       class="form-control"
                                       readonly
                                       placeholder="တောင်းဆိုမှု ရွေးချယ်ပါက ပြပါမည်">

                            </div>


                            {{-- =================================================
                                PHONE
                            ================================================== --}}

                            <div class="col-md-6">

                                <label for="requester_phone"
                                       class="form-label fw-bold">

                                    ဖုန်းနံပါတ်

                                </label>

                                <input type="text"
                                       id="requester_phone"
                                       class="form-control"
                                       readonly
                                       placeholder="တောင်းဆိုမှု ရွေးချယ်ပါက ပြပါမည်">

                            </div>


                            {{-- =================================================
                                HANDLED BY
                            ================================================== --}}

                            <div class="col-md-4">

                                <label for="handled_by"
                                       class="form-label fw-bold">

                                    တာဝန်ယူဆောင်ရွက်သူ

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="handled_by"
                                        id="handled_by"
                                        class="form-select @error('handled_by') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        -- တာဝန်ယူသူ ရွေးချယ်ပါ --
                                    </option>


                                    @foreach($users as $handledUser)

                                        <option value="{{ $handledUser->id }}"
                                                {{ old('handled_by', auth()->id()) == $handledUser->id ? 'selected' : '' }}>

                                            {{ $handledUser->name }}

                                            @if($handledUser->role)

                                                -
                                                {{ ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $handledUser->role
                                                    )
                                                ) }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>


                                @error('handled_by')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                DATE
                            ================================================== --}}

                            <div class="col-md-4">

                                <label for="distribution_date"
                                       class="form-label fw-bold">

                                    ဖြန့်ဝေသည့် ရက်စွဲ

                                    <span class="text-danger">*</span>

                                </label>


                                <input type="date"
                                       name="distribution_date"
                                       id="distribution_date"
                                       class="form-control @error('distribution_date') is-invalid @enderror"
                                       value="{{ old('distribution_date', date('Y-m-d')) }}"
                                       required>


                                @error('distribution_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- =================================================
                                STATUS
                            ================================================== --}}

                            <div class="col-md-4">

                                <label for="status"
                                       class="form-label fw-bold">

                                    အခြေအနေ

                                    <span class="text-danger">*</span>

                                </label>


                                <select name="status"
                                        id="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required>

                                    <option value="Completed"
                                            {{ old('status', 'Completed') === 'Completed' ? 'selected' : '' }}>

                                        ပြီးစီးပြီး

                                    </option>

                                    <option value="Processing"
                                            {{ old('status') === 'Processing' ? 'selected' : '' }}>

                                        ဆောင်ရွက်နေဆဲ

                                    </option>

                                </select>


                                @error('status')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>
                            {{-- =================================================
                                FUNDING INFORMATION
                            ================================================== --}}

                            <div class="col-12">

                                <div class="card border-0 bg-light">

                                    <div class="card-body">

                                        <div class="row g-3">

                                            {{-- AVAILABLE DONATION --}}

                                            <div class="col-md-4">

                                                <label class="form-label fw-bold">
                                                    <i class="fa-solid fa-wallet text-success me-1"></i>
                                                    ရရှိနိုင်သော Donation ငွေ
                                                </label>

                                                <div class="input-group">

                                                    <span class="input-group-text">
                                                        {{ config('app.currency', 'MMK') }}
                                                    </span>

                                                    <input type="text"
                                                        class="form-control fw-bold text-success"
                                                        value="{{ number_format($availableFundingAmount, 2) }}"
                                                        readonly>

                                                </div>

                                                <small class="text-muted">
                                                    Completed Donation Payment များမှ
                                                    အသုံးပြုနိုင်သေးသော လက်ကျန်ငွေ
                                                </small>

                                            </div>


                                            {{-- TOTAL DONATION --}}

                                            <div class="col-md-4">

                                                <label class="form-label fw-bold">
                                                    စုစုပေါင်း Donation Payment
                                                </label>

                                                <div class="input-group">

                                                    <span class="input-group-text">
                                                        {{ config('app.currency', 'MMK') }}
                                                    </span>

                                                    <input type="text"
                                                        class="form-control"
                                                        value="{{ number_format($totalDonationAmount, 2) }}"
                                                        readonly>

                                                </div>

                                            </div>


                                            {{-- FUNDING AMOUNT --}}

                                            <div class="col-md-4">

                                                <label for="funding_amount"
                                                    class="form-label fw-bold">

                                                    ငွေကြေးထောက်ပံ့မည့်ပမာဏ

                                                    <span class="text-muted">
                                                        (မထည့်လည်းရ)
                                                    </span>

                                                </label>

                                                <div class="input-group">

                                                    <span class="input-group-text">
                                                        {{ config('app.currency', 'MMK') }}
                                                    </span>

                                                    <input type="number"
                                                        name="funding_amount"
                                                        id="funding_amount"
                                                        class="form-control @error('funding_amount') is-invalid @enderror"
                                                        value="{{ old('funding_amount', 0) }}"
                                                        min="0"
                                                        max="{{ $availableFundingAmount }}"
                                                        step="0.01"
                                                        placeholder="0">

                                                    @error('funding_amount')

                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>

                                                    @enderror

                                                </div>

                                                <small id="funding-help"
                                                    class="text-muted">

                                                    အများဆုံး
                                                    {{ number_format($availableFundingAmount, 2) }}
                                                    {{ config('app.currency', 'MMK') }}
                                                    ထည့်နိုင်ပါသည်။

                                                </small>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                NOTE
                            ================================================== --}}

                            <div class="col-12">

                                <label for="note"
                                       class="form-label fw-bold">

                                    မှတ်ချက်

                                </label>


                                <textarea name="note"
                                          id="note"
                                          rows="3"
                                          class="form-control @error('note') is-invalid @enderror"
                                          placeholder="ဖြန့်ဝေမှုနှင့် ပတ်သက်သော မှတ်ချက်များ ထည့်သွင်းပါ">{{ old('note') }}</textarea>


                                @error('note')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =====================================================
                SECTION 2
                ITEMS
            ====================================================== --}}

            <div class="col-12">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white py-3
                                d-flex justify-content-between
                                align-items-center">

                        <h5 class="fw-bold text-primary mb-0">

                            <i class="fa-solid fa-boxes-stacked me-2"></i>

                            ၂။ ဖြန့်ဝေမည့် ကယ်ဆယ်ရေးပစ္စည်းများ

                        </h5>


                        <button type="button"
                                id="add-item-row"
                                class="btn btn-sm btn-success">

                            <i class="fa-solid fa-plus me-1"></i>

                            ပစ္စည်းထပ်ထည့်ရန်

                        </button>

                    </div>


                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered
                                          align-middle mb-0"
                                   id="items-table">

                                <thead class="table-light">

                                    <tr>

                                        <th style="width:28%;">
                                            ပစ္စည်းအမည်
                                        </th>

                                        <th style="width:15%;">
                                            တောင်းခံအရေအတွက်
                                        </th>

                                        <th style="width:15%;">
                                            Inventory လက်ကျန်
                                        </th>

                                        <th style="width:17%;">
                                            Expiry Date
                                        </th>

                                        <th style="width:15%;">
                                            ဖြန့်ဝေမည့်အရေအတွက်
                                        </th>

                                        <th style="width:10%;"
                                            class="text-center">

                                            လုပ်ဆောင်ချက်

                                        </th>

                                    </tr>

                                </thead>


                                <tbody id="items-tbody">

                                    <tr class="item-row">

                                        {{-- ITEM --}}

                                        <td>

                                            <select name="items[0][item_id]"
                                                    class="form-select item-select"
                                                    required>

                                                <option value="">
                                                    -- ပစ္စည်းရွေးချယ်ပါ --
                                                </option>

                                                @foreach($items as $item)

                                                    <option value="{{ $item->id }}">

                                                        {{ $item->name }}

                                                        @if($item->unit)

                                                            ({{ $item->unit }})

                                                        @endif

                                                    </option>

                                                @endforeach

                                            </select>

                                        </td>


                                        {{-- REQUESTED QUANTITY --}}

                                        <td>

                                            <input type="number"
                                                   class="form-control requested-quantity"
                                                   value="0"
                                                   readonly>

                                        </td>


                                        {{-- AVAILABLE STOCK --}}

                                        <td>

                                            <input type="number"
                                                   class="form-control available-stock"
                                                   value="0"
                                                   readonly>

                                            <small class="stock-status d-block mt-1">
                                            </small>

                                        </td>


                                        {{-- EXPIRY DATE --}}

                                        <td>

                                            <input type="date"
                                                   name="items[0][expiry_date]"
                                                   class="form-control expiry-date"
                                                   readonly>

                                            <small class="expiry-status d-block mt-1">
                                            </small>

                                        </td>


                                        {{-- DISTRIBUTION QUANTITY --}}

                                        <td>

                                            <input type="number"
                                                   name="items[0][quantity]"
                                                   class="form-control quantity-input"
                                                   min="1"
                                                   value="1"
                                                   required>

                                        </td>


                                        {{-- REMOVE --}}

                                        <td class="text-center">

                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm remove-row">

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>


                        {{-- INVENTORY ALERT --}}

                        <div id="inventory-alert"
                             class="alert alert-info mt-3 mb-0 d-none">

                            <i class="fa-solid fa-circle-info me-2"></i>

                            <span id="inventory-alert-text"></span>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =====================================================
                SUBMIT
            ====================================================== --}}

            <div class="col-12">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('backend.distributions.index') }}"
                       class="btn btn-light">

                        <i class="fa-solid fa-xmark me-1"></i>

                        ပယ်ဖျက်ရန်

                    </a>


                    <button type="submit"
                            id="submitBtn"
                            class="btn btn-primary px-5">

                        <i class="fa-solid fa-check-circle me-1"></i>

                        ဖြန့်ဝေမှု အတည်ပြုရန်

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

{{-- =========================================================
    JAVASCRIPT
========================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ELEMENTS
    ========================================================== */

    let rowIndex = document.querySelectorAll('#items-tbody .item-row').length;

    const requestSelect   = document.getElementById('request_id');
    const warehouseSelect = document.getElementById('warehouse_id');
    const requesterName   = document.getElementById('requester_name');
    const requesterPhone  = document.getElementById('requester_phone');

    const tbody           = document.getElementById('items-tbody');
    const addButton       = document.getElementById('add-item-row');

    const form            = document.getElementById('distributionForm');
    const submitBtn       = document.getElementById('submitBtn');

    const inventoryAlert  = document.getElementById('inventory-alert');
    const inventoryAlertText =
        document.getElementById('inventory-alert-text');


    /* =========================================================
       INVENTORY DATA
    ========================================================== */

    const inventoryData = @json($inventoryData ?? []);


    /* =========================================================
       ITEM OPTIONS
    ========================================================== */

    const itemOptions = `
        <option value="">
            -- ပစ္စည်းရွေးချယ်ပါ --
        </option>

        @foreach($items ?? [] as $item)
            <option value="{{ $item->id }}">
                {{ $item->name }}
                @if($item->unit)
                    ({{ $item->unit }})
                @endif
            </option>
        @endforeach
    `;


    /* =========================================================
       HELPER
    ========================================================== */

    function toNumber(value) {
        const number = Number(value);
        return Number.isFinite(number) ? number : 0;
    }


    function normalizeDate(value) {

        if (!value) {
            return '';
        }

        /*
        |----------------------------------------------------------
        | Convert:
        | 2026-12-30
        | 2026-12-30 00:00:00
        | 2026-12-30T00:00:00
        |----------------------------------------------------------
        */

        return String(value).substring(0, 10);
    }


    /* =========================================================
       FIND INVENTORY
    ========================================================== */

    function findInventory(warehouseId, itemId) {

        if (!warehouseId || !itemId) {
            return null;
        }


        /*
        |----------------------------------------------------------
        | ARRAY FORMAT
        |
        | [
        |   {
        |      warehouse_id: 1,
        |      item_id: 2,
        |      quantity: 100,
        |      reserved_quantity: 10,
        |      expiry_date: "2026-12-30",
        |      alert_quantity: 10
        |   }
        | ]
        |----------------------------------------------------------
        */

        if (Array.isArray(inventoryData)) {

            return inventoryData.find(function (inventory) {

                return (
                    toNumber(inventory.warehouse_id) ===
                    toNumber(warehouseId)

                    &&

                    toNumber(inventory.item_id) ===
                    toNumber(itemId)
                );

            }) || null;
        }


        /*
        |----------------------------------------------------------
        | OBJECT FORMAT
        |
        | {
        |   1: [
        |      {
        |          item_id: 2,
        |          quantity: 100
        |      }
        |   ]
        | }
        |----------------------------------------------------------
        */

        if (
            typeof inventoryData === 'object'
            &&
            inventoryData !== null
        ) {

            const warehouseInventories =
                inventoryData[warehouseId] || [];

            if (Array.isArray(warehouseInventories)) {

                return warehouseInventories.find(function (inventory) {

                    return (
                        toNumber(inventory.item_id) ===
                        toNumber(itemId)
                    );

                }) || null;
            }
        }


        return null;
    }


    /* =========================================================
       SHOW INVENTORY ALERT
    ========================================================== */

    function showInventoryAlert(type, message) {

        if (!inventoryAlert || !inventoryAlertText) {
            return;
        }

        inventoryAlert.classList.remove(
            'd-none',
            'alert-danger',
            'alert-warning',
            'alert-success',
            'alert-info'
        );

        inventoryAlert.classList.add(
            'alert-' + type
        );

        inventoryAlertText.textContent = message;
    }


    /* =========================================================
       HIDE INVENTORY ALERT
    ========================================================== */

    function hideInventoryAlert() {

        if (!inventoryAlert) {
            return;
        }

        inventoryAlert.classList.add('d-none');
    }


    /* =========================================================
       VALIDATE QUANTITY
    ========================================================== */

    function validateQuantity(row) {

        if (!row) {
            return false;
        }

        const quantityInput =
            row.querySelector('.quantity-input');

        const availableStock =
            row.querySelector('.available-stock');

        const requestedQuantity =
            row.querySelector('.requested-quantity');

        const itemSelect =
            row.querySelector('.item-select');


        if (
            !quantityInput
            ||
            !availableStock
            ||
            !requestedQuantity
        ) {
            return false;
        }


        const quantity =
            toNumber(quantityInput.value);

        const stock =
            toNumber(availableStock.value);

        const requested =
            toNumber(requestedQuantity.value);


        quantityInput.classList.remove(
            'is-invalid',
            'is-valid'
        );


        /*
        |----------------------------------------------------------
        | ITEM NOT SELECTED
        |----------------------------------------------------------
        */

        if (
            itemSelect
            &&
            !itemSelect.value
        ) {

            quantityInput.classList.add('is-invalid');

            return false;
        }


        /*
        |----------------------------------------------------------
        | NO STOCK
        |----------------------------------------------------------
        */

        if (stock <= 0) {

            quantityInput.classList.add('is-invalid');

            return false;
        }


        /*
        |----------------------------------------------------------
        | QUANTITY MUST BE >= 1
        |----------------------------------------------------------
        */

        if (quantity < 1) {

            quantityInput.classList.add('is-invalid');

            return false;
        }


        /*
        |----------------------------------------------------------
        | QUANTITY CANNOT EXCEED STOCK
        |----------------------------------------------------------
        */

        if (quantity > stock) {

            quantityInput.classList.add('is-invalid');

            return false;
        }


        /*
        |----------------------------------------------------------
        | QUANTITY CANNOT EXCEED REQUEST
        |----------------------------------------------------------
        */

        if (
            requested > 0
            &&
            quantity > requested
        ) {

            quantityInput.classList.add('is-invalid');

            return false;
        }


        quantityInput.classList.add('is-valid');

        return true;
    }


    /* =========================================================
       UPDATE ROW INVENTORY
    ========================================================== */

    function updateRowInventory(row) {

        if (!row) {
            return;
        }


        const itemSelect =
            row.querySelector('.item-select');

        const requestedQuantity =
            row.querySelector('.requested-quantity');

        const availableStock =
            row.querySelector('.available-stock');

        const expiryDate =
            row.querySelector('.expiry-date');

        const quantityInput =
            row.querySelector('.quantity-input');

        const stockStatus =
            row.querySelector('.stock-status');

        const expiryStatus =
            row.querySelector('.expiry-status');


        if (
            !itemSelect
            ||
            !requestedQuantity
            ||
            !availableStock
            ||
            !expiryDate
            ||
            !quantityInput
        ) {
            return;
        }


        const warehouseId =
            warehouseSelect
                ? warehouseSelect.value
                : '';

        const itemId =
            itemSelect.value;


        /*
        |----------------------------------------------------------
        | RESET
        |----------------------------------------------------------
        */

        availableStock.value = 0;

        expiryDate.value = '';

        quantityInput.max = '';

        quantityInput.readOnly = false;

        quantityInput.classList.remove(
            'is-invalid',
            'is-valid'
        );

        availableStock.classList.remove(
            'text-danger',
            'text-success',
            'text-warning'
        );


        if (stockStatus) {
            stockStatus.innerHTML = '';
        }

        if (expiryStatus) {
            expiryStatus.innerHTML = '';
        }


        /*
        |----------------------------------------------------------
        | NO WAREHOUSE
        |----------------------------------------------------------
        */

        if (!warehouseId) {

            quantityInput.readOnly = true;
            quantityInput.value = '';

            if (stockStatus) {

                stockStatus.innerHTML = `
                    <span class="text-warning small">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        ကျေးဇူးပြု၍ ဂိုဒေါင်အရင်ရွေးပါ
                    </span>
                `;
            }

            return;
        }


        /*
        |----------------------------------------------------------
        | NO ITEM
        |----------------------------------------------------------
        */

        if (!itemId) {

            quantityInput.readOnly = true;
            quantityInput.value = '';

            return;
        }


        /*
        |----------------------------------------------------------
        | FIND INVENTORY
        |----------------------------------------------------------
        */

        const inventory =
            findInventory(
                warehouseId,
                itemId
            );


        /*
        |----------------------------------------------------------
        | INVENTORY NOT FOUND
        |----------------------------------------------------------
        */

        if (!inventory) {

            availableStock.value = 0;

            quantityInput.max = 0;

            quantityInput.value = '';

            quantityInput.readOnly = true;


            if (stockStatus) {

                stockStatus.innerHTML = `
                    <span class="text-danger small">
                        <i class="fa-solid fa-circle-xmark me-1"></i>
                        Inventory မရှိပါ
                    </span>
                `;
            }


            showInventoryAlert(
                'danger',
                'ရွေးချယ်ထားသော Warehouse တွင် ဤပစ္စည်း၏ Inventory စာရင်း မရှိပါ။'
            );

            return;
        }


        /* =========================================================
           STOCK CALCULATION
        ========================================================== */

        const totalStock =
            toNumber(inventory.quantity);

        const reservedStock =
            toNumber(inventory.reserved_quantity);

        /*
        |----------------------------------------------------------
        | Actual available stock
        |
        | quantity - reserved_quantity
        |----------------------------------------------------------
        */

        const stock =
            Math.max(
                0,
                totalStock - reservedStock
            );


        const alertQuantity =
            toNumber(inventory.alert_quantity);


        availableStock.value = stock;


        /*
        |----------------------------------------------------------
        | EXPIRY DATE
        |----------------------------------------------------------
        */

        const normalizedExpiry =
            normalizeDate(
                inventory.expiry_date
            );


        if (normalizedExpiry) {

            expiryDate.value =
                normalizedExpiry;


            if (expiryStatus) {

                expiryStatus.innerHTML = `
                    <span class="text-success small">
                        <i class="fa-solid fa-calendar-check me-1"></i>
                        Expiry Date ရှိပါသည်
                    </span>
                `;
            }

        } else {

            expiryDate.value = '';

            if (expiryStatus) {

                expiryStatus.innerHTML = `
                    <span class="text-warning small">
                        <i class="fa-solid fa-calendar-xmark me-1"></i>
                        Expiry Date မရှိပါ
                    </span>
                `;
            }
        }


        /* =========================================================
           STOCK STATUS
        ========================================================== */

        if (stock <= 0) {

            quantityInput.readOnly = true;

            quantityInput.value = '';

            quantityInput.max = 0;


            if (stockStatus) {

                stockStatus.innerHTML = `
                    <span class="text-danger fw-bold small">
                        <i class="fa-solid fa-circle-xmark me-1"></i>
                        Stock မရှိပါ
                    </span>
                `;
            }


            availableStock.classList.add(
                'text-danger'
            );


            showInventoryAlert(
                'danger',
                'ဤပစ္စည်း၏ အသုံးပြုနိုင်သော Inventory လက်ကျန် မရှိပါ။'
            );


            return;
        }


        /*
        |----------------------------------------------------------
        | LOW STOCK
        |----------------------------------------------------------
        */

        if (
            alertQuantity > 0
            &&
            stock <= alertQuantity
        ) {

            quantityInput.readOnly = false;

            if (stockStatus) {

                stockStatus.innerHTML = `
                    <span class="text-warning fw-bold small">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        Low Stock
                    </span>
                `;
            }


            availableStock.classList.add(
                'text-warning'
            );


            showInventoryAlert(
                'warning',
                'ဤပစ္စည်း၏ Inventory လက်ကျန် နည်းနေပါသည်။'
            );

        } else {

            quantityInput.readOnly = false;

            if (stockStatus) {

                stockStatus.innerHTML = `
                    <span class="text-success small">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        လက်ကျန်ရှိပါသည်
                    </span>
                `;
            }


            availableStock.classList.add(
                'text-success'
            );
        }


        /* =========================================================
           REQUESTED QUANTITY
        ========================================================== */

        const requested =
            toNumber(
                requestedQuantity.value
            );


        /*
        |----------------------------------------------------------
        | Maximum allowed quantity
        |
        | If request = 20
        | stock = 15
        |
        | max = 15
        |
        | If request = 10
        | stock = 15
        |
        | max = 10
        |----------------------------------------------------------
        */

        let maximumQuantity = stock;

        if (requested > 0) {

            maximumQuantity =
                Math.min(
                    requested,
                    stock
                );
        }


        quantityInput.max =
            maximumQuantity;


        /*
        |----------------------------------------------------------
        | DEFAULT QUANTITY
        |----------------------------------------------------------
        */

        let currentQuantity =
            toNumber(
                quantityInput.value
            );


        if (
            currentQuantity < 1
            ||
            currentQuantity > maximumQuantity
        ) {

            quantityInput.value =
                maximumQuantity > 0
                    ? maximumQuantity
                    : '';
        }


        /*
        |----------------------------------------------------------
        | REQUEST > STOCK
        |----------------------------------------------------------
        */

        if (
            requested > 0
            &&
            requested > stock
        ) {

            showInventoryAlert(
                'warning',
                `တောင်းခံထားသော အရေအတွက် ${requested} ခု ဖြစ်သော်လည်း Inventory လက်ကျန် ${stock} ခုသာ ရှိပါသည်။`
            );
        }


        validateQuantity(row);
    }


    /* =========================================================
       CREATE ITEM ROW
    ========================================================== */

    function createItemRow(
        itemId = '',
        requestedQuantity = 0,
        distributionQuantity = '',
        expiryDate = ''
    ) {

        const currentIndex =
            rowIndex;


        const row =
            document.createElement('tr');

        row.classList.add(
            'item-row'
        );


        /*
        |----------------------------------------------------------
        | Default quantity
        |----------------------------------------------------------
        */

        if (
            distributionQuantity === null
            ||
            distributionQuantity === undefined
        ) {
            distributionQuantity = '';
        }


        row.innerHTML = `

            <td>

                <select
                    name="items[${currentIndex}][item_id]"
                    class="form-select item-select"
                    required
                >

                    ${itemOptions}

                </select>

                <div class="invalid-feedback">
                    ပစ္စည်းရွေးချယ်ပါ။
                </div>

            </td>


            <td>

                <input
                    type="number"
                    class="form-control requested-quantity"
                    value="${requestedQuantity}"
                    min="0"
                    readonly
                >

            </td>


            <td>

                <input
                    type="number"
                    class="form-control available-stock"
                    value="0"
                    readonly
                >

                <small class="stock-status d-block mt-1"></small>

            </td>


            <td>

                <input
                    type="date"
                    name="items[${currentIndex}][expiry_date]"
                    class="form-control expiry-date"
                    value="${normalizeDate(expiryDate)}"
                    readonly
                >

                <small class="expiry-status d-block mt-1"></small>

            </td>


            <td>

                <input
                    type="number"
                    name="items[${currentIndex}][quantity]"
                    class="form-control quantity-input"
                    min="1"
                    value="${distributionQuantity}"
                    required
                >

                <div class="invalid-feedback">
                    ဖြန့်ဝေမည့် အရေအတွက်ကို မှန်ကန်စွာ ထည့်ပါ။
                </div>

            </td>


            <td class="text-center">

                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm remove-row"
                    title="ဖျက်ရန်"
                >

                    <i class="fa-solid fa-trash"></i>

                </button>

            </td>

        `;


        if (tbody) {

            tbody.appendChild(row);
        }


        const select =
            row.querySelector('.item-select');


        if (select) {

            select.value =
                String(itemId || '');
        }


        row.querySelector(
            '.requested-quantity'
        ).value =
            requestedQuantity || 0;


        row.querySelector(
            '.expiry-date'
        ).value =
            normalizeDate(expiryDate);


        row.querySelector(
            '.quantity-input'
        ).value =
            distributionQuantity;


        rowIndex++;


        updateRowInventory(row);


        return row;
    }


    /* =========================================================
       CLEAR ITEM ROWS
    ========================================================== */

    function clearItemRows() {

        if (!tbody) {
            return;
        }

        tbody.innerHTML = '';

        rowIndex = 0;
    }


    /* =========================================================
       ADD ITEM
    ========================================================== */

    if (addButton) {

        addButton.addEventListener(
            'click',
            function () {

                const row =
                    createItemRow();

                /*
                |--------------------------------------------------
                | Focus new row
                |--------------------------------------------------
                */

                const select =
                    row.querySelector('.item-select');

                if (select) {
                    select.focus();
                }
            }
        );
    }


    /* =========================================================
       REMOVE ITEM
    ========================================================== */

    document.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest('.remove-row');


            if (!button) {
                return;
            }


            if (!tbody) {
                return;
            }


            const rows =
                tbody.querySelectorAll('.item-row');


            /*
            |------------------------------------------------------
            | At least one row required
            |------------------------------------------------------
            */

            if (rows.length <= 1) {

                alert(
                    'ဖြန့်ဝေမည့် ပစ္စည်းအနည်းဆုံး တစ်မျိုး ထည့်သွင်းရပါမည်။'
                );

                return;
            }


            const row =
                button.closest('.item-row');


            if (row) {

                row.remove();

                /*
                |--------------------------------------------------
                | Revalidate remaining rows
                |--------------------------------------------------
                */

                const remainingRows =
                    tbody.querySelectorAll('.item-row');

                remainingRows.forEach(function (remainingRow) {

                    validateQuantity(
                        remainingRow
                    );

                });
            }
        }
    );


    /* =========================================================
       REQUEST CHANGE
    ========================================================== */

    if (requestSelect) {

        requestSelect.addEventListener(
            'change',
            function () {

                const selectedOption =
                    requestSelect.options[
                        requestSelect.selectedIndex
                    ];


                /*
                |--------------------------------------------------
                | RESET
                |--------------------------------------------------
                */

                if (
                    !selectedOption
                    ||
                    !selectedOption.value
                ) {

                    if (requesterName) {
                        requesterName.value = '';
                    }

                    if (requesterPhone) {
                        requesterPhone.value = '';
                    }


                    clearItemRows();

                    createItemRow();

                    hideInventoryAlert();

                    return;
                }


                /*
                |--------------------------------------------------
                | REQUESTER
                |--------------------------------------------------
                */

                if (requesterName) {

                    requesterName.value =
                        selectedOption.dataset.requester
                        ||
                        'အမည်မရှိ';
                }


                if (requesterPhone) {

                    requesterPhone.value =
                        selectedOption.dataset.phone
                        ||
                        'ဖုန်းနံပါတ် မရှိပါ';
                }


                /*
                |--------------------------------------------------
                | WAREHOUSE
                |--------------------------------------------------
                */

                const requestWarehouse =
                    selectedOption.dataset.warehouse;


                if (
                    requestWarehouse
                    &&
                    warehouseSelect
                ) {

                    warehouseSelect.value =
                        requestWarehouse;
                }


                /*
                |--------------------------------------------------
                | REQUEST ITEMS
                |--------------------------------------------------
                */

                let requestItems = [];


                try {

                    const json =
                        selectedOption.dataset.items;


                    if (json) {

                        requestItems =
                            JSON.parse(json);
                    }

                } catch (error) {

                    console.error(
                        'Request Items JSON Error:',
                        error
                    );

                    requestItems = [];
                }


                /*
                |--------------------------------------------------
                | CLEAR OLD ROWS
                |--------------------------------------------------
                */

                clearItemRows();


                /*
                |--------------------------------------------------
                | CREATE REQUEST ITEMS
                |--------------------------------------------------
                */

                if (
                    Array.isArray(requestItems)
                    &&
                    requestItems.length > 0
                ) {

                    requestItems.forEach(
                        function (requestItem) {

                            const requested =
                                toNumber(
                                    requestItem.quantity
                                );


                            createItemRow(
                                requestItem.item_id,
                                requested,
                                requested > 0
                                    ? requested
                                    : '',
                                requestItem.expiry_date || ''
                            );

                        }
                    );

                } else {

                    createItemRow();
                }


                /*
                |--------------------------------------------------
                | Update all rows after warehouse changed
                |--------------------------------------------------
                */

                if (tbody) {

                    const rows =
                        tbody.querySelectorAll('.item-row');

                    rows.forEach(function (row) {

                        updateRowInventory(row);

                    });
                }

            }
        );
    }


    /* =========================================================
       ITEM CHANGE
    ========================================================== */

    document.addEventListener(
        'change',
        function (event) {

            if (
                !event.target.classList.contains(
                    'item-select'
                )
            ) {
                return;
            }


            const currentSelect =
                event.target;

            const selectedValue =
                currentSelect.value;


            /*
            |------------------------------------------------------
            | Duplicate check
            |------------------------------------------------------
            */

            if (selectedValue) {

                const allSelects =
                    document.querySelectorAll(
                        '.item-select'
                    );


                let duplicate =
                    false;


                allSelects.forEach(
                    function (select) {

                        if (
                            select !== currentSelect
                            &&
                            select.value === selectedValue
                        ) {

                            duplicate = true;
                        }
                    }
                );


                if (duplicate) {

                    alert(
                        'ဤပစ္စည်းကို ထပ်မံရွေးချယ်ထားပါသည်။ ပစ္စည်းတစ်မျိုးကို တစ်ကြောင်းသာ ရွေးချယ်ပါ။'
                    );


                    currentSelect.value =
                        '';


                    const duplicateRow =
                        currentSelect.closest(
                            '.item-row'
                        );


                    if (duplicateRow) {

                        updateRowInventory(
                            duplicateRow
                        );
                    }


                    return;
                }
            }


            /*
            |------------------------------------------------------
            | Update inventory
            |------------------------------------------------------
            */

            const row =
                currentSelect.closest(
                    '.item-row'
                );


            if (row) {

                updateRowInventory(
                    row
                );
            }

        }
    );


    /* =========================================================
       WAREHOUSE CHANGE
    ========================================================== */

    if (warehouseSelect) {

        warehouseSelect.addEventListener(
            'change',
            function () {

                /*
                |--------------------------------------------------
                | Warehouse changed
                | Reset/update every item row
                |--------------------------------------------------
                */

                if (!tbody) {
                    return;
                }


                const rows =
                    tbody.querySelectorAll(
                        '.item-row'
                    );


                rows.forEach(
                    function (row) {

                        updateRowInventory(
                            row
                        );

                    }
                );


                hideInventoryAlert();

            }
        );
    }


    /* =========================================================
       QUANTITY INPUT
    ========================================================== */

    document.addEventListener(
        'input',
        function (event) {

            if (
                !event.target.classList.contains(
                    'quantity-input'
                )
            ) {
                return;
            }


            const row =
                event.target.closest(
                    '.item-row'
                );


            if (row) {

                validateQuantity(
                    row
                );
            }
        }
    );


    /* =========================================================
       INITIALIZE EXISTING ROWS
    ========================================================== */

    if (tbody) {

        const existingRows =
            tbody.querySelectorAll(
                '.item-row'
            );


        if (existingRows.length > 0) {

            existingRows.forEach(
                function (row) {

                    updateRowInventory(
                        row
                    );

                }
            );

        } else {

            /*
            |------------------------------------------------------
            | Create first empty row
            |------------------------------------------------------
            */

            createItemRow();
        }
    }


    /* =========================================================
       INITIAL REQUEST
    ========================================================== */

    if (
        requestSelect
        &&
        requestSelect.value
    ) {

        requestSelect.dispatchEvent(
            new Event('change')
        );
    }


    /* =========================================================
       FORM SUBMIT VALIDATION
    ========================================================== */

    if (form) {

        form.addEventListener(
            'submit',
            function (event) {

                if (!tbody) {

                    event.preventDefault();

                    alert(
                        'Item list ကို ရှာမတွေ့ပါ။'
                    );

                    return;
                }


                const rows =
                    tbody.querySelectorAll(
                        '.item-row'
                    );


                /*
                |--------------------------------------------------
                | No rows
                |--------------------------------------------------
                */

                if (rows.length === 0) {

                    event.preventDefault();

                    alert(
                        'ဖြန့်ဝေမည့် ပစ္စည်း အနည်းဆုံးတစ်မျိုး ထည့်ပါ။'
                    );

                    return;
                }


                let valid =
                    true;


                const selectedItems =
                    new Set();


                rows.forEach(
                    function (row) {

                        const itemSelect =
                            row.querySelector(
                                '.item-select'
                            );

                        const quantityInput =
                            row.querySelector(
                                '.quantity-input'
                            );

                        const availableStock =
                            row.querySelector(
                                '.available-stock'
                            );

                        const requestedQuantity =
                            row.querySelector(
                                '.requested-quantity'
                            );


                        const itemId =
                            itemSelect
                                ? itemSelect.value
                                : '';


                        const quantity =
                            toNumber(
                                quantityInput
                                    ? quantityInput.value
                                    : 0
                            );


                        const stock =
                            toNumber(
                                availableStock
                                    ? availableStock.value
                                    : 0
                            );


                        const requested =
                            toNumber(
                                requestedQuantity
                                    ? requestedQuantity.value
                                    : 0
                            );


                        /* =========================================
                           ITEM REQUIRED
                        ========================================== */

                        if (!itemId) {

                            valid = false;

                            if (itemSelect) {

                                itemSelect.classList.add(
                                    'is-invalid'
                                );
                            }

                        } else {

                            if (itemSelect) {

                                itemSelect.classList.remove(
                                    'is-invalid'
                                );
                            }
                        }


                        /* =========================================
                           DUPLICATE ITEM
                        ========================================== */

                        if (itemId) {

                            if (
                                selectedItems.has(
                                    itemId
                                )
                            ) {

                                valid = false;

                                if (itemSelect) {

                                    itemSelect.classList.add(
                                        'is-invalid'
                                    );
                                }

                            } else {

                                selectedItems.add(
                                    itemId
                                );
                            }
                        }


                        /* =========================================
                           INVENTORY
                        ========================================== */

                        if (stock <= 0) {

                            valid = false;

                            if (quantityInput) {

                                quantityInput.classList.add(
                                    'is-invalid'
                                );
                            }
                        }


                        /* =========================================
                           QUANTITY >= 1
                        ========================================== */

                        if (quantity < 1) {

                            valid = false;

                            if (quantityInput) {

                                quantityInput.classList.add(
                                    'is-invalid'
                                );
                            }
                        }


                        /* =========================================
                           QUANTITY <= STOCK
                        ========================================== */

                        if (quantity > stock) {

                            valid = false;

                            if (quantityInput) {

                                quantityInput.classList.add(
                                    'is-invalid'
                                );
                            }
                        }


                        /* =========================================
                           QUANTITY <= REQUEST
                        ========================================== */

                        if (
                            requested > 0
                            &&
                            quantity > requested
                        ) {

                            valid = false;

                            if (quantityInput) {

                                quantityInput.classList.add(
                                    'is-invalid'
                                );
                            }
                        }


                        /*
                        |--------------------------------------------------
                        | Use common validator
                        |--------------------------------------------------
                        */

                        if (
                            itemId
                            &&
                            !validateQuantity(row)
                        ) {

                            valid = false;
                        }

                    }
                );


                /* =====================================================
                   INVALID
                ====================================================== */

                if (!valid) {

                    event.preventDefault();


                    showInventoryAlert(
                        'danger',
                        'ကျေးဇူးပြု၍ Item၊ တောင်းခံအရေအတွက်နှင့် Inventory လက်ကျန်ကို မှန်ကန်စွာ စစ်ဆေးပါ။'
                    );


                    alert(
                        'ကျေးဇူးပြု၍ Item၊ တောင်းခံအရေအတွက်နှင့် Inventory လက်ကျန်ကို မှန်ကန်စွာ စစ်ဆေးပါ။'
                    );


                    return;
                }


                /* =====================================================
                   PREVENT DOUBLE SUBMIT
                ====================================================== */

                if (submitBtn) {

                    submitBtn.disabled =
                        true;

                    submitBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        ဖြန့်ဝေမှု ပြုလုပ်နေပါသည်...
                    `;
                }

            }
        );
    }

});

</script>

@endsection
