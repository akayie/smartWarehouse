@extends('layouts.front') {{-- သင့် Project ၏ Layout ဖိုင်အမည် ပေါင်းစပ်ပါ --}}

@section('content')
<!-- PAGE 3: REQUEST RELIEF AID -->
<div id="pub-request" class="sub-page">
    <div class="card form-card">
        <h2><i class="fa-solid fa-hand-holding-medical icon-red"></i> သဘာဝဘေး ကူညီကယ်ဆယ်ရေး ပစ္စည်းများ တောင်းခံရန်</h2>
        <p class="section-desc">စိစစ်အတည်ပြုခြင်းနှင့် ကယ်ဆယ်ရေးပစ္စည်းများ မြန်ဆန်စွာ ထုတ်ယူဖြန့်ဝေနိုင်ရေးအတွက် တိကျမှန်ကန်သော အချက်အလက်များကို ဖြည့်သွင်းပေးပါရန်။</p>

        @if(session('success'))
            <div class="alert alert-success" style="color: green; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('public.request.store') }}" method="POST">
            @csrf

            <!-- Disaster Event Select -->
            <div class="form-group">
                <label for="disaster-event">သဘာဝဘေးအန္တရာယ် ဖြစ်စဉ်</label>
                <select name="disaster_id" id="disaster-event" class="form-control" required>
                    <option value="">-- သဘာဝဘေးအန္တရာယ် ဖြစ်စဉ် ရွေးချယ်ပါ --</option>
                    @foreach($disasters as $disaster)
                        <option value="{{ $disaster->id }}">{{ $disaster->name }} ({{ $disaster->location }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Target Location / Camp Name -->
            <div class="form-group">
                <label for="target-location">ရောက်ရှိရမည့် တည်နေရာ / ကယ်ဆယ်ရေးစခန်း အမည်</label>
                <input type="text" name="location" id="target-location" class="form-control" placeholder="ဥပမာ - အမှတ် (၄) ရပ်ကွက် အထက ကယ်ဆယ်ရေးစခန်း၊ မုံရွာမြို့" required>
            </div>

            <!-- Additional Note -->
            <div class="form-group">
                <label for="note">အခြား အသေးစိတ် အချက်အလက်များ / မှတ်ချက်</label>
                <textarea name="note" id="note" class="form-control" rows="3" placeholder="အရေးပေါ် လိုအပ်ချက်များ၊ ခန့်မှန်း ဘေးသင့်လူဦးရေ၊ ပစ္စည်းအမျိုးအစား စသည်တို့ကို ဖော်ပြပေးပါ။"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-danger btn-full">
                <i class="fa-solid fa-paper-plane"></i> အကူအညီတောင်းခံလွှာ ပေးပို့မည်
            </button>
        </form>
    </div>
</div>
@endsection
