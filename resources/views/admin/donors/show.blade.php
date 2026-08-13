@extends('layouts.admin')

@section('title')
    အလှူရှင်အသေးစိတ်
@endsection

@section('button')

<a
    href="{{ route('backend.donors.index') }}"
    class="btn btn-secondary">

    <i class="fas fa-arrow-left me-1"></i>
    အလှူရှင်စာရင်းသို့ ပြန်သွားရန်

</a>

@endsection

@section('content')

<div class="card">

    {{-- Card Header --}}
    <div class="card-header">

        <h4 class="mb-0">
            <i class="fas fa-user-circle me-2"></i>
            အလှူရှင်အသေးစိတ် အချက်အလက်
        </h4>

    </div>


    {{-- Card Body --}}
    <div class="card-body">

        <table class="table table-bordered align-middle">

            {{-- Donor Name --}}
            <tr>

                <th width="200">
                    အလှူရှင်အမည်
                </th>

                <td>

                    <strong>
                        {{ $donor->name }}
                    </strong>

                </td>

            </tr>


            {{-- Phone --}}
            <tr>

                <th>
                    ဖုန်းနံပါတ်
                </th>

                <td>
                    {{ $donor->phone ?? '-' }}
                </td>

            </tr>


            {{-- Email --}}
            <tr>

                <th>
                    Email
                </th>

                <td>
                    {{ $donor->email ?? '-' }}
                </td>

            </tr>


            {{-- Address --}}
            <tr>

                <th>
                    လိပ်စာ
                </th>

                <td>
                    {{ $donor->address ?? '-' }}
                </td>

            </tr>


            {{-- Created At --}}
            <tr>

                <th>
                    စာရင်းသွင်းသည့်ရက်စွဲ
                </th>

                <td>

                    {{ $donor->created_at
                        ? $donor->created_at->format('d-m-Y H:i:s')
                        : '-'
                    }}

                </td>

            </tr>


            {{-- Updated At --}}
            <tr>

                <th>
                    နောက်ဆုံး ပြင်ဆင်သည့်ရက်စွဲ
                </th>

                <td>

                    {{ $donor->updated_at
                        ? $donor->updated_at->format('d-m-Y H:i:s')
                        : '-'
                    }}

                </td>

            </tr>

        </table>

    </div>

</div>

@endsection
