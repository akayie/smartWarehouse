@extends('layouts.front')

@section('title', 'Smart Relief - ပင်မစာမျက်နှာ')

@section('content')
    <div class="hero-section">
        <h1 class="hero-title">လျှင်မြန်ပြီး ပွင့်လင်းမြင်သာမှုရှိသော သဘာဝဘေးအန္တရာယ် ကူညီကယ်ဆယ်ရေး ပစ္စည်းများ ဖြန့်ဝေခြင်း</h1>
        <p class="hero-subtitle">အရေးပေါ် ကူညီကယ်ဆယ်ရေးပစ္စည်းများကို အလိုအပ်ဆုံးနေရာများသို့ အမြန်ဆုံးရောက်ရှိစေရန် အလှူရှင်များ၊ စာရင်းဝင်ဂိုဒေါင်များနှင့် ကယ်ဆယ်ရေးစခန်းများကို စနစ်တကျ ချိတ်ဆက်ပေးလျက်ရှိပါသည်။</p>
        <div class="hero-cta-group">
            <a href="{{ route('public.request.create') }}" class="btn btn-danger">
                <i class="fa-solid fa-hand-holding-medical"></i> ကယ်ဆယ်ရေးပစ္စည်း တောင်းခံရန်
            </a>
            <a href="{{ route('public.donate.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-heart"></i> လှူဒါန်းမှုများ ပြုလုပ်ရန်
            </a>
        </div>
    </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card card-stat h-100">
                    <i class="fa-solid fa-boxes-stacked icon-blue"></i>
                    <h3>{{ number_format($totalItems ?? 0) }}</h3>
                    <p class="stat-label">လက်ဝယ်ရှိ ကယ်ဆယ်ရေးပစ္စည်းများ</p>
                </div>
            </div>
            <div class="col">
                <div class="card card-stat h-100">
                    <i class="fa-solid fa-warehouse icon-orange"></i>
                    <h3>{{ number_format($totalWarehouses ?? 0) }}</h3>
                    <p class="stat-label">လက်ရှိလည်ပတ်နေသော ဂိုဒေါင်များ</p>
                </div>
            </div>
            <div class="col">
                <div class="card card-stat h-100">
                    <i class="fa-solid fa-triangle-exclamation icon-red"></i>
                    <h3>{{ number_format($activeDisastersCount ?? 0) }}</h3>
                    <p class="stat-label">ဘေးအန္တရာယ်ကျရောက်နေသော ဒေသများ</p>
                </div>
            </div>
        </div>
    {{-- <div class="grid-4">
        <div class="card card-stat">
            <i class="fa-solid fa-boxes-stacked icon-blue"></i>
            <h3>{{ number_format($totalItems ?? 0) }}</h3>
            <p class="stat-label">လက်ဝယ်ရှိ ကယ်ဆယ်ရေးပစ္စည်းများ</p>
        </div>
        <div class="card card-stat">
            <i class="fa-solid fa-warehouse icon-orange"></i>
            <h3>{{ number_format($totalWarehouses ?? 0) }}</h3>
            <p class="stat-label">လက်ရှိလည်ပတ်နေသော ဂိုဒေါင်များ</p>
        </div>
        <div class="card card-stat">
            <i class="fa-solid fa-triangle-exclamation icon-red"></i>
            <h3>{{ number_format($activeDisastersCount ?? 0) }}</h3>
            <p class="stat-label">ဘေးအန္တရာယ်ကျရောက်နေသော ဒေသများ</p>
        </div>
         <div class="card card-stat">
            <i class="fa-solid fa-circle-check icon-green"></i>
            <h3>{{ number_format($familiesHelped ?? 0) }}</h3>
            <p class="stat-label">ကူညီထောက်ပံ့နိုင်ခဲ့သော မိသားစုများ</p>
        </div>
    </div> --}}
@endsection
