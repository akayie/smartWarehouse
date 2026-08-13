@extends('layouts.front')

@section('title', 'ကျွန်ုပ်တို့အကြောင်း - Smart Disaster Relief Management System')

@section('content')
<div class="about-container my-5">
    <!-- Hero Header -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">ကျွန်ုပ်တို့၏ ရည်မှန်းချက်</h1>
        <p class="lead text-muted mx-auto" style="max-width: 700px;">
            အကူအညီလိုအပ်နေသော လူမှုအသိုက်အဝန်းများထံ ကူညီကယ်ဆယ်ရေးပစ္စည်းများ လျှင်မြန်၊ ပွင့်လင်းမြင်သာပြီး အဆင့်လိုက် စနစ်တကျ ရောက်ရှိစေရန် အလှူရှင်များ၊ တုံ့ပြန်ကူညီရေးအဖွဲ့များနှင့် စာရင်းဝင်ဂိုဒေါင်များကို ချိတ်ဆက်ပေးလျက်ရှိပါသည်။
        </p>
    </div>

    <!-- Features / Core Values Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="icon-box mb-3 text-primary fs-1">
                        <i class="fa-solid fa-bolt-lightning"></i>
                    </div>
                    <h5 class="card-title fw-bold">လျှင်မြန်သော တုံ့ပြန်ဆောင်ရွက်မှု</h5>
                    <p class="card-text text-muted">
                        အကူအညီတောင်းခံမှုများနှင့် ပစ္စည်းလက်ကျန် အခြေအနေများကို စာရင်းအချိန်နဲ့တပြေးညီ ကြည့်ရှုနိုင်သဖြင့် ဘေးသင့်ပြည်သူများထံ ကြန့်ကြာမှုမရှိဘဲ အမြန်ဆုံး အကူအညီပေးနိုင်ပါသည်။
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="icon-box mb-3 text-success fs-1">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h5 class="card-title fw-bold">အပြည့်အဝ ပွင့်လင်းမြင်သာမှု</h5>
                    <p class="card-text text-muted">
                        လှူဒါန်းမှုလက်ခံရရှိချိန်မှစ၍ ဂိုဒေါင်မှ ပစ္စည်းထုတ်ယူဖြန့်ဝေချိန်အထိ ပစ္စည်းတိုင်းကို QR Scan ဖြင့် စစ်ဆေးအတည်ပြုပြီး စာရင်းအချိန်နဲ့တပြေးညီ တိကျစွာ မှတ်တမ်းတင်ထားပါသည်။
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="icon-box mb-3 text-warning fs-1">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <h5 class="card-title fw-bold">လူမှုအသိုက်အဝန်း ပံ့ပိုးကူညီမှု</h5>
                    <p class="card-text text-muted">
                        စိစစ်အတည်ပြုထားသော ထိရောက်သည့် ဖြန့်ဝေမှုလုပ်ငန်းစဉ်များဖြင့် စေတနာရှင်အလှူရှင်များနှင့် ဘေးအန္တရာယ်ကျရောက်ရာ ဒေသများကို တိုက်ရိုက်ချိတ်ဆက်ပေးပါသည်။
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="bg-light p-5 rounded-3 shadow-sm mb-5">
        <h2 class="fw-bold text-center mb-4">ကျွန်ုပ်တို့၏ ကယ်ဆယ်ရေးစနစ် လုပ်ဆောင်ပုံ</h2>
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="p-3">
                    <span class="badge bg-primary rounded-circle p-3 fs-4 mb-3">1</span>
                    <h6 class="fw-bold">အကူအညီ တောင်းခံခြင်း</h6>
                    <p class="small text-muted">ဘေးသင့်ပြည်သူများ သို့မဟုတ် ဒေသခံ ကယ်ဆယ်ရေးအဖွဲ့များမှ အရေးပေါ် ကူညီကယ်ဆယ်ရေး တောင်းခံလွှာ ပေးပို့ခြင်း။</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <span class="badge bg-primary rounded-circle p-3 fs-4 mb-3">2</span>
                    <h6 class="fw-bold">ပစ္စည်းလက်ကျန် စိစစ်ခြင်း</h6>
                    <p class="small text-muted">စနစ်မှ တောင်းခံလွှာများကို လက်ရှိ ဂိုဒေါင်များရှိ ပစ္စည်းလက်ကျန်များနှင့် တိုက်ဆိုင်စစ်ဆေးခြင်း။</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <span class="badge bg-primary rounded-circle p-3 fs-4 mb-3">3</span>
                    <h6 class="fw-bold">QR Code ဖြင့် ထုတ်ယူခြင်း</h6>
                    <p class="small text-muted">ပစ္စည်းများကို QR code ဖြင့် စကန်ဖတ်စစ်ဆေးပြီး စနစ်တကျ ထုတ်ယူဖြန့်ဝေခြင်း။</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <span class="badge bg-primary rounded-circle p-3 fs-4 mb-3">4</span>
                    <h6 class="fw-bold">အကူအညီ ရောက်ရှိခြင်း</h6>
                    <p class="small text-muted">ဖြန့်ဝေမှု မှတ်တမ်းအထောက်အထားများဖြင့် ဘေးသင့်ပြည်သူများထံ ကယ်ဆယ်ရေးပစ္စည်းများ အမှန်တကယ် ရောက်ရှိခြင်း။</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call To Action -->
    <div class="text-center p-5 bg-primary text-white rounded-3 shadow">
        <h3 class="fw-bold mb-3">ကူညီလက်တွဲ ပါဝင်ချင်ပါသလား။</h3>
        <p class="mb-4">သင့်၏ ကူညီပံ့ပိုးမှုနှင့် လှူဒါန်းမှုများသည် ဘေးအန္တရာယ်ကျရောက်နေသော ဒေသများရှိ ပြည်သူများ၏ အသက်ကို ကယ်တင်နိုင်ပါသည်။</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('public.request.create') }}" class="btn btn-light text-primary fw-bold px-4">
                <i class="fa-solid fa-hand-holding-medical me-1"></i> အကူအညီ တောင်းခံရန်
            </a>
            <a href="{{ route('public.donate.create') }}" class="btn btn-outline-light fw-bold px-4">
                <i class="fa-solid fa-heart me-1"></i> ယခုပင် လှူဒါန်းရန်
            </a>
        </div>
    </div>
</div>
@endsection
