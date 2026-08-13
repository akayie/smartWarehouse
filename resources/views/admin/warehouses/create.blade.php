@extends('layouts.admin')

@section('title')
    ကုန်လှောင်ရုံ အသစ်ထည့်မည်
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
        margin-bottom:25px;
        padding-bottom:15px;
        border-bottom:1px solid #e5e7eb;
    ">

        <h3 style="
            margin:0;
        ">
            ကုန်လှောင်ရုံ အသစ်ထည့်ရန်
        </h3>

        <p style="
            margin:5px 0 0;
            color:#6b7280;
            font-size:14px;
        ">
            သဘာဝဘေးအန္တရာယ် ကူညီကယ်ဆယ်ရေး ကုန်လှောင်ရုံအသစ် စာရင်းသွင်းပါ။
        </p>

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
        action="{{ route('backend.warehouses.store') }}"
        method="POST">

        @csrf


        {{-- Warehouse Name --}}
        <div class="form-group">

            <label for="name">
                ကုန်လှောင်ရုံ အမည်
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
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
                value="{{ old('location') }}"
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
                value="{{ old('phone') }}"
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
                        {{ old('manager_id') == $user->id
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
                    {{ old('status', 'Active') === 'Active'
                        ? 'selected'
                        : '' }}>
                    အသုံးပြုနေဆဲ
                </option>

                <option
                    value="Inactive"
                    {{ old('status') === 'Inactive'
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

                ကုန်လှောင်ရုံ သိမ်းဆည်းမည်

            </button>


            <a
                href="{{ route('backend.warehouses.index') }}"
                class="btn btn-sm btn-outline">

                မလုပ်တော့ပါ

            </a>

        </div>

    </form>

</div>

@endsection
