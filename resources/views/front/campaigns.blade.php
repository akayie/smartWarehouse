@extends('layouts.front') {{-- သင့် Project ၏ Layout ဖိုင်အမည်ပြောင်းပါ --}}

@section('content')
<div id="pub-events" class="sub-page">
    <h2 style="margin-bottom: 20px;">
        <i class="fa-solid fa-triangle-exclamation icon-red"></i> Active Disaster Relief Campaigns
    </h2>

    <div class="grid-2">
        @forelse($campaigns as $campaign)
            <div class="card">
                {{-- Status Badge --}}
                @if($campaign->status === 'Active')
                    <span class="badge badge-danger">Emergency Active</span>
                @else
                    <span class="badge badge-warning">{{ $campaign->status }}</span>
                @endif

                {{-- Campaign Title & Details --}}
                <h3 class="event-title">{{ $campaign->name }}</h3>
                <p class="event-desc">
                    <strong>Type:</strong> {{ $campaign->type }} |
                    <strong>Location:</strong> {{ $campaign->location }}<br>
                    <strong>Duration:</strong>
                    {{ $campaign->start_date ? $campaign->start_date->format('M d, Y') : 'N/A' }}
                    -
                    {{ $campaign->end_date ? $campaign->end_date->format('M d, Y') : 'Ongoing' }}
                </p>

                {{-- Donate Button --}}
                <a href="{{ route('public.donate.store') }}" class="btn btn-primary btn-sm">
                    Donate To Campaign
                </a>
            </div>
        @empty
            <div class="col-span-2 text-center">
                <p>No active disaster relief campaigns found at the moment.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    <div style="margin-top: 20px;">
        {{ $campaigns->links() }}
    </div>
</div>
@endsection
