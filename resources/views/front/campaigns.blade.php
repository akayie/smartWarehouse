@extends('layouts.front') {{-- သင့် Project ၏ Layout ဖိုင်အမည်ပြောင်းပါ --}}

@section('content')
<div id="pub-events" class="sub-page">
    <h2 style="margin-bottom: 20px;">
        <i class="fa-solid fa-triangle-exclamation icon-red"></i> လက်ရှိ ဆောင်ရွက်နေသော သဘာဝဘေး ကူညီကယ်ဆယ်ရေး လှုပ်ရှားမှုများ
    </h2>

    <div class="grid-2">
        @forelse($campaigns as $campaign)
            <div class="card">
                {{-- Status Badge --}}
                @if($campaign->status === 'Active')
                    <span class="badge badge-danger">အရေးပေါ် ကယ်ဆယ်ရေး လုပ်ဆောင်နေဆဲ</span>
                @else
                    <span class="badge badge-warning">{{ $campaign->status }}</span>
                @endif

                {{-- Campaign Title & Details --}}
                <h3 class="event-title">{{ $campaign->name }}</h3>
                <p class="event-desc">
                    <strong>အမျိုးအစား -</strong> {{ $campaign->type }} |
                    <strong>တည်နေရာ -</strong> {{ $campaign->location }}<br>
                    <strong>ကြာမြင့်ချိန် -</strong>
                    {{ $campaign->start_date ? $campaign->start_date->format('M d, Y') : 'N/A' }}
                    -
                    {{ $campaign->end_date ? $campaign->end_date->format('M d, Y') : 'ဆက်လက်ဆောင်ရွက်ဆဲ' }}
                </p>

                {{-- Donate Button --}}
                <a href="{{ route('public.donate.store') }}" class="btn btn-primary btn-sm">
                    ဤကယ်ဆယ်ရေးလှုပ်ရှားမှုတွင် လှူဒါန်းရန်
                </a>
            </div>
        @empty
            <div class="col-span-2 text-center">
                <p>လက်ရှိအချိန်တွင် လှုပ်ရှားဆောင်ရွက်နေသော သဘာဝဘေး ကူညီကယ်ဆယ်ရေး စီမံချက်များ မရှိသေးပါ။</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    <div style="margin-top: 20px;">
        {{ $campaigns->links() }}
    </div>
</div>
@endsection
