@extends('layouts.front')

@section('title', 'ကယ်ဆယ်ရေး လှုပ်ရှားမှုများ - Smart Relief')

@section('content')

<div id="pub-events" class="sub-page">

    <h2 style="margin-bottom: 20px;">
        <i class="fa-solid fa-triangle-exclamation icon-red"></i>
        လက်ရှိ ဆောင်ရွက်နေသော သဘာဝဘေး ကူညီကယ်ဆယ်ရေး လှုပ်ရှားမှုများ
    </h2>

    <div class="grid-2">

        @forelse($campaigns as $campaign)

            <div class="card">

                {{-- Status --}}
                @if($campaign->status === 'Active')

                    <span class="badge badge-danger">
                        အရေးပေါ် ကယ်ဆယ်ရေး လုပ်ဆောင်နေဆဲ
                    </span>

                @elseif($campaign->status === 'Completed')

                    <span class="badge badge-success">
                        ပြီးဆုံးပြီ
                    </span>

                @else

                    <span class="badge badge-warning">
                        {{ $campaign->status }}
                    </span>

                @endif

                {{-- Campaign Name --}}
                <h3 class="event-title">
                    {{ $campaign->name }}
                </h3>

                {{-- Details --}}
                <p class="event-desc">

                    <strong>အမျိုးအစား -</strong>
                    {{ $campaign->type ?? 'N/A' }}

                    <br>

                    <strong>တည်နေရာ -</strong>
                    {{ $campaign->location ?? 'N/A' }}

                    <br>

                    <strong>စတင်ရက် -</strong>
                    {{
                        $campaign->start_date
                        ? \Carbon\Carbon::parse($campaign->start_date)->format('Y-m-d')
                        : 'N/A'
                    }}

                    <br>

                    <strong>ပြီးဆုံးရက် -</strong>

                    {{
                        $campaign->end_date
                        ? \Carbon\Carbon::parse($campaign->end_date)->format('Y-m-d')
                        : 'ဆက်လက်ဆောင်ရွက်ဆဲ'
                    }}

                </p>

                {{-- Donate --}}
                @if($campaign->status === 'Active')

                    <a href="{{ route('public.donate.create') }}"
                       class="btn btn-primary btn-sm">

                        <i class="fa-solid fa-heart me-1"></i>

                        ဤကယ်ဆယ်ရေးလှုပ်ရှားမှုတွင်
                        လှူဒါန်းရန်

                    </a>

                @endif

            </div>

        @empty

            <div class="col-span-2 text-center">

                <p>
                    လက်ရှိအချိန်တွင် လှုပ်ရှားဆောင်ရွက်နေသော
                    သဘာဝဘေး ကူညီကယ်ဆယ်ရေး စီမံချက်များ
                    မရှိသေးပါ။
                </p>

            </div>

        @endforelse

    </div>

    {{-- Pagination --}}
    <div style="margin-top: 20px;">

        {{ $campaigns->links() }}

    </div>

</div>

@endsection
