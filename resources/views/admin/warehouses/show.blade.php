@extends('layouts.admin')

@section('title')
    ကုန်လှောင်ရုံ အသေးစိတ်
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
        align-items:center;
        gap:20px;
        margin-bottom:25px;
        padding-bottom:15px;
        border-bottom:1px solid #e5e7eb;
        flex-wrap:wrap;
    ">

        <div>

            <h3 style="
                margin:0 0 5px;
            ">
                ကုန်လှောင်ရုံ အသေးစိတ်
            </h3>

            <p style="
                margin:0;
                color:#6b7280;
                font-size:14px;
            ">
                ကုန်လှောင်ရုံ၏ အသေးစိတ် အချက်အလက်များကို ကြည့်ရှုပါ။
            </p>

        </div>


        <div style="
            display:flex;
            align-items:center;
            gap:10px;
        ">

            <span style="
                padding:7px 12px;
                background:#f3f4f6;
                border-radius:7px;
                font-size:13px;
                font-weight:600;
                color:#374151;
            ">

                WH-{{ str_pad(
                    $warehouse->id,
                    3,
                    '0',
                    STR_PAD_LEFT
                ) }}

            </span>


            <a
                href="{{ route(
                    'backend.warehouses.edit',
                    $warehouse->id
                ) }}"
                class="edit-btn">

                ကုန်လှောင်ရုံ ပြင်ဆင်ရန်

            </a>

        </div>

    </div>


    {{-- Summary Cards --}}
    <div style="
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:15px;
        margin-bottom:25px;
    ">


        {{-- Warehouse --}}
        <div style="
            padding:20px;
            border:1px solid #e5e7eb;
            border-radius:10px;
            background:#f8fafc;
        ">

            <div style="
                font-size:13px;
                color:#6b7280;
                margin-bottom:8px;
            ">
                ကုန်လှောင်ရုံ
            </div>

            <strong style="
                font-size:18px;
            ">
                {{ $warehouse->name }}
            </strong>

        </div>


        {{-- Manager --}}
        <div style="
            padding:20px;
            border:1px solid #e5e7eb;
            border-radius:10px;
            background:#f8fafc;
        ">

            <div style="
                font-size:13px;
                color:#6b7280;
                margin-bottom:8px;
            ">
                မန်နေဂျာ / တာဝန်ခံ
            </div>

            <strong style="
                font-size:18px;
            ">
                {{ $warehouse->manager->name ?? 'မန်နေဂျာ တာဝန်ပေးထားခြင်း မရှိပါ' }}
            </strong>

        </div>


        {{-- Status --}}
        <div style="
            padding:20px;
            border:1px solid #e5e7eb;
            border-radius:10px;
            background:#f8fafc;
        ">

            <div style="
                font-size:13px;
                color:#6b7280;
                margin-bottom:8px;
            ">
                အခြေအနေ
            </div>


            @if($warehouse->status === 'Active')

                <span class="badge badge-success">
                    အသုံးပြုနေဆဲ
                </span>

            @else

                <span class="badge badge-danger">
                    ပိတ်ထားသည်
                </span>

            @endif

        </div>

    </div>


    {{-- Warehouse Information --}}
    <div style="
        margin-bottom:25px;
    ">

        <h3 style="
            margin:0 0 15px;
            font-size:18px;
        ">
            ကုန်လှောင်ရုံ အချက်အလက်များ
        </h3>


        <div style="
            overflow-x:auto;
        ">

            <table class="data-table">

                <tbody>

                    {{-- ID --}}
                    <tr>

                        <th style="
                            width:220px;
                        ">
                            ကုန်လှောင်ရုံ ID
                        </th>

                        <td>
                            #{{ $warehouse->id }}
                        </td>

                    </tr>


                    {{-- Code --}}
                    <tr>

                        <th>
                            ကုန်လှောင်ရုံ ကုတ်နံပါတ်
                        </th>

                        <td>

                            <strong>
                                WH-{{ str_pad(
                                    $warehouse->id,
                                    3,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}
                            </strong>

                        </td>

                    </tr>


                    {{-- Name --}}
                    <tr>

                        <th>
                            ကုန်လှောင်ရုံ အမည်
                        </th>

                        <td>

                            <strong>
                                {{ $warehouse->name }}
                            </strong>

                        </td>

                    </tr>


                    {{-- Location --}}
                    <tr>

                        <th>
                            တည်နေရာ
                        </th>

                        <td>
                            {{ $warehouse->location ?: 'မရှိပါ' }}
                        </td>

                    </tr>


                    {{-- Phone --}}
                    <tr>

                        <th>
                            ဆက်သွယ်ရန် ဖုန်းနံပါတ်
                        </th>

                        <td>
                            {{ $warehouse->phone ?: 'မရှိပါ' }}
                        </td>

                    </tr>


                    {{-- Manager --}}
                    <tr>

                        <th>
                            ကုန်လှောင်ရုံ မန်နေဂျာ
                        </th>

                        <td>

                            @if($warehouse->manager)

                                {{ $warehouse->manager->name }}

                            @else

                                <span style="
                                    color:#6b7280;
                                ">
                                    မန်နေဂျာ တာဝန်ပေးထားခြင်း မရှိပါ
                                </span>

                            @endif

                        </td>

                    </tr>


                    {{-- Manager Email --}}
                    <tr>

                        <th>
                            မန်နေဂျာ အီးမေးလ်
                        </th>

                        <td>

                            @if($warehouse->manager)

                                {{ $warehouse->manager->email ?: '-' }}

                            @else

                                -

                            @endif

                        </td>

                    </tr>


                    {{-- Status --}}
                    <tr>

                        <th>
                            အခြေအနေ
                        </th>

                        <td>

                            @if($warehouse->status === 'Active')

                                <span class="badge badge-success">
                                    အသုံးပြုနေဆဲ
                                </span>

                            @else

                                <span class="badge badge-danger">
                                    ပိတ်ထားသည်
                                </span>

                            @endif

                        </td>

                    </tr>


                    {{-- Created --}}
                    <tr>

                        <th>
                            စတင်ဖန်တီးခဲ့သည့် အချိန်
                        </th>

                        <td>

                            {{ $warehouse->created_at
                                ? $warehouse->created_at->format(
                                    'd-m-Y H:i:s'
                                )
                                : '-'
                            }}

                        </td>

                    </tr>


                    {{-- Updated --}}
                    <tr>

                        <th>
                            နောက်ဆုံး ပြင်ဆင်ခဲ့သည့် အချိန်
                        </th>

                        <td>

                            {{ $warehouse->updated_at
                                ? $warehouse->updated_at->format(
                                    'd-m-Y H:i:s'
                                )
                                : '-'
                            }}

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>


    {{-- Bottom Actions --}}
    <div style="
        display:flex;
        gap:10px;
        padding-top:20px;
        border-top:1px solid #e5e7eb;
    ">

        <a
            href="{{ route(
                'backend.warehouses.edit',
                $warehouse->id
            ) }}"
            class="edit-btn">

            ကုန်လှောင်ရုံ ပြင်ဆင်ရန်

        </a>


        <a
            href="{{ route(
                'backend.warehouses.index'
            ) }}"
            class="cancel-btn">

            ကုန်လှောင်ရုံ စာရင်းသို့ ပြန်သွားရန်

        </a>

    </div>

</div>

@endsection
