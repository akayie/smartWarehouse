@extends('layouts.admin')

@section('title')
    ကုန်လှောင်ရုံ ပြင်ဆင်ရန်
@endsection


@section('button')

    <a href="{{ route('backend.warehouses.index') }}"
       class="btn btn-sm btn-outline">
        ← နောက်သို့
    </a>

@endsection


@section('content')

<div class="card">

    {{-- Header --}}
    <div style="
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:20px;
        margin-bottom:25px;
        padding-bottom:15px;
        border-bottom:1px solid #e5e7eb;
    ">

        <div>

            <h3 style="
                margin:0 0 5px;
            ">
                ကုန်လှောင်ရုံ ပြင်ဆင်ရန်
            </h3>

            <p style="
                margin:0;
                color:#6b7280;
                font-size:14px;
            ">
                ကုန်လှောင်ရုံ အချက်အလက်များနှင့် တာဝန်ခံ မန်နေဂျာကို ပြင်ဆင်မွမ်းမံပါ။
            </p>

        </div>


        <span style="
            padding:7px 12px;
            background:#f3f4f6;
            border-radius:7px;
            font-size:13px;
            font-weight:600;
            color:#374151;
            white-space:nowrap;
        ">

            WH-{{ str_pad(
                $warehouse->id,
                3,
                '0',
                STR_PAD_LEFT
            ) }}

        </span>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div style="
            background:#fef2f2;
            border:1px solid #fecaca;
            color:#b91c1c;
            padding:15px;
            border-radius:8px;
            margin-bottom:20px;
        ">

            <strong>
                ကျေးဇူးပြု၍ အောက်ပါ အမှားများကို ပြင်ဆင်ပေးပါ -
            </strong>

            <ul style="
                margin:8px 0 0 20px;
            ">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route(
            'backend.warehouses.update',
            $warehouse->id
        ) }}"
        method="POST">

        @csrf

        @method('PUT')


        {{-- Warehouse Name --}}
        <div class="form-group">

            <label for="name">
                ကုန်လှောင်ရုံ အမည်
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old(
                    'name',
                    $warehouse->name
                ) }}"
                placeholder="ဥပမာ - မန္တလေး ဗဟိုကုန်လှောင်ရုံ"
                class="@error('name') error @enderror"
            >

            @error('name')

                <span class="error-text">
                    {{ $message }}
                </span>

            @enderror

        </div>


        {{-- Location --}}
        <div class="form-group">

            <label for="location">
                တည်နေရာ
            </label>

            <input
                type="text"
                id="location"
                name="location"
                value="{{ old(
                    'location',
                    $warehouse->location
                ) }}"
                placeholder="ဥပမာ - မန္တလေး စက်မှုဇုန်"
                class="@error('location') error @enderror"
            >

            @error('location')

                <span class="error-text">
                    {{ $message }}
                </span>

            @enderror

        </div>


        {{-- Phone --}}
        <div class="form-group">

            <label for="phone">
                ဆက်သွယ်ရန် ဖုန်းနံပါတ်
            </label>

            <input
                type="text"
                id="phone"
                name="phone"
                value="{{ old(
                    'phone',
                    $warehouse->phone
                ) }}"
                placeholder="ဥပမာ - ၀၉XXXXXXXXX"
                class="@error('phone') error @enderror"
            >

            @error('phone')

                <span class="error-text">
                    {{ $message }}
                </span>

            @enderror

        </div>


        {{-- Manager --}}
        <div class="form-group">

            <label for="manager_id">
                ကုန်လှောင်ရုံ မန်နေဂျာ / တာဝန်ခံ
            </label>

            <select
                id="manager_id"
                name="manager_id"
                class="@error('manager_id') error @enderror">

                <option value="">
                    မန်နေဂျာ ရွေးချယ်ပါ
                </option>

                @foreach($users as $user)

                    <option
                        value="{{ $user->id }}"
                        {{ old(
                            'manager_id',
                            $warehouse->manager_id
                        ) == $user->id
                            ? 'selected'
                            : '' }}>

                        {{ $user->name }}

                    </option>

                @endforeach

            </select>

            @error('manager_id')

                <span class="error-text">
                    {{ $message }}
                </span>

            @enderror

        </div>


        {{-- Status --}}
        <div class="form-group">

            <label for="status">
                အခြေအနေ
            </label>

            <select
                id="status"
                name="status"
                class="@error('status') error @enderror">

                <option
                    value="Active"
                    {{ old(
                        'status',
                        $warehouse->status
                    ) === 'Active'
                        ? 'selected'
                        : '' }}>
                    အသုံးပြုနေဆဲ
                </option>

                <option
                    value="Inactive"
                    {{ old(
                        'status',
                        $warehouse->status
                    ) === 'Inactive'
                        ? 'selected'
                        : '' }}>
                    ပိတ်ထားသည်
                </option>

            </select>

            @error('status')

                <span class="error-text">
                    {{ $message }}
                </span>

            @enderror

        </div>


        {{-- Buttons --}}
        <div style="
            display:flex;
            gap:10px;
            margin-top:25px;
            padding-top:20px;
            border-top:1px solid #e5e7eb;
        ">

            <button
                type="submit"
                class="btn btn-sm btn-primary">

                ပြင်ဆင်ချက်များ သိမ်းဆည်းမည်

            </button>


            <a
                href="{{ route(
                    'backend.warehouses.index'
                ) }}"
                class="btn btn-sm btn-outline">

                မလုပ်တော့ပါ

            </a>

        </div>

    </form>

</div>

@endsection
