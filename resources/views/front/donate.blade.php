@extends('layouts.front')

@section('title', 'လှူဒါန်းမှုပြုလုပ်ရန် - Smart Relief')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0 fw-bold"><i class="fa-solid fa-heart me-2"></i> ကယ်ဆယ်ရေးလုပ်ငန်းများတွင် ပါဝင်လှူဒါန်းရန်</h4>
            </div>
            <div class="card-body p-4">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('public.donate.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- 1. Donor Info -->
                    <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-user me-1"></i> ၁။ လှူဒါန်းသူ အချက်အလက်</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="donor_name" class="form-label">အမည် / အဖွဲ့အစည်း အမည် <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="donor_name" name="donor_name" required placeholder="ဦးမြ / ကုမ္ပဏီအမည်">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">ဖုန်းနံပါတ် <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" required placeholder="09xxxxxxxxx">
                        </div>
                        <div class="col-md-12">
                            <label for="email" class="form-label">အီးမေးလ် လိပ်စာ (မဖြစ်မနေ ထည့်ရန်မလိုပါ)</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- 2. Donation Category Selection -->
                    <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-layer-group me-1"></i> ၂။ လှူဒါန်းမှု အမျိုးအစား</h5>
                    <div class="mb-4">
                        <label for="donation_type" class="form-label">လှူဒါန်းမည့် အမျိုးအစား ရွေးချယ်ပါ <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg" id="donation_type" name="donation_type" required onchange="toggleDonationFields()">
                            <option value="Cash">ငွေသား (ငွေကြေးလှူဒါန်းမှု)</option>
                            <option value="Food">စားနပ်ရိက္ခာ</option>
                            <option value="Water">သောက်ရေသန့်</option>
                            <option value="Clothing">အဝတ်အထည်</option>
                            <option value="Medical">ဆေးဝါးနှင့် ကျန်းမာရေးသုံးပစ္စည်းများ</option>
                            <option value="Shelter">ယာယီတဲ/ခိုလှုံရေးသုံးပစ္စည်းများ</option>
                            <option value="Hygiene">တစ်ကိုယ်ရေသန့်ရှင်းရေးသုံးပစ္စည်းများ</option>
                            <option value="Rescue Equipment">ရှာဖွေကယ်ဆယ်ရေးပစ္စည်းများ</option>
                            <option value="Other">အခြားပစ္စည်းများ</option>
                        </select>
                    </div>

                    <!-- 3. Dynamic Section: CASH -->
                    <div id="cash_section">
                        <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-money-bill-transfer me-1"></i> ၃။ ငွေပေးချေမှု အသေးစိတ်</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">ငွေပေးချေမှု နည်းလမ်း <span class="text-danger">*</span></label>
                                <select class="form-select" id="payment_method" name="payment_method">
                                    <option value="KBZPay">KBZPay</option>
                                    <option value="WaveMoney">WaveMoney</option>
                                    <option value="CBPay">CBPay</option>
                                    <option value="AYA Pay">AYA Pay</option>
                                    <option value="Bank Transfer">Bank Transfer (ဘဏ်အကောင့်မှတဆင့်)</option>
                                    <option value="Cash In Hand">Cash In Hand (ပြင်ပတွင် တိုက်ရိုက်လှူဒါန်းမည်)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">လှူဒါန်းငွေ ပမာဏ (ကျပ်) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" placeholder="ဥပမာ - 50000">
                            </div>
                            <div class="col-md-6">
                                <label for="transaction_reference" class="form-label">ငွေလွှဲပြောင်းမှု အမှတ်စဉ် / Txn ID</label>
                                <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" placeholder="ဥပမာ - 2024091200123">
                            </div>
                            <div class="col-md-6">
                                <label for="proof" class="form-label">ငွေလွှဲပြေစာ / ပြေစာဓာတ်ပုံ</label>
                                <input type="file" class="form-control" id="proof" name="proof" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Dynamic Section: PHYSICAL ITEMS -->
                    <div id="item_section" style="display: none;">
                        <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-boxes-stacked me-1"></i> ၃။ လှူဒါန်းမည့် ပစ္စည်း အသေးစိတ်</h5>
                        <div class="row g-3 mb-4">

                            <!-- Existing Catalog Select -->
                            <div class="col-md-6">
                                <label for="item_id" class="form-label">စာရင်းရှိ ပစ္စည်းအမျိုးအစားများမှ ရွေးချယ်ရန် (မဖြစ်မနေ ရွေးရန်မလိုပါ)</label>
                                <select class="form-select" id="item_id" name="item_id">
                                    <option value="">-- စာရင်းမှ ရွေးချယ်ပါ --</option>
                                    @foreach($items ?? [] as $item)
                                        <option value="{{ $item->id }}" data-unit="{{ $item->unit }}">
                                            {{ $item->name }} (ယူနစ် - {{ $item->unit ?? 'ခု' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- New Item Name -->
                            <div class="col-md-6">
                                <label for="new_item_name" class="form-label">သို့မဟုတ် ပစ္စည်းအမည် အသစ်ရိုက်ထည့်ပါ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="new_item_name" name="new_item_name" placeholder="ဥပမာ - ခေါက်ဆွဲခြောက်၊ သောက်ရေသန့်">
                                <small class="text-muted">အထက်ပါ စာရင်းတွင် မပါရှိပါက ဤနေရာ၌ ရိုက်ထည့်ပါ။</small>
                            </div>

                            <!-- Quantity & Unit -->
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">အရေအတွက် <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1" placeholder="ဥပမာ - 50">
                            </div>

                            <div class="col-md-6">
                                <label for="unit" class="form-label">ရေတွက်ပုံ ယူနစ်</label>
                                <input type="text" class="form-control" id="unit" name="unit" placeholder="ဥပမာ - ထုပ်၊ ဗူး၊ ခု၊ ကီလို">
                            </div>

                        </div>
                    </div>

                    <!-- Common Note Section -->
                    <div class="mb-4">
                        <label for="note" class="form-label">အခြား ဖြည့်စွက်ချက် / မှတ်ချက်</label>
                        <textarea class="form-control" id="note" name="note" rows="2" placeholder="အသေးစိတ် အချက်အလက် သို့မဟုတ် ဖော်ပြလိုသည်များကို ဤနေရာတွင် ရေးသားနိုင်ပါသည်။"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="fa-solid fa-paper-plane me-1"></i> လှူဒါန်းမှု ပေးပို့မည်
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    function toggleDonationFields() {
        var donationType = document.getElementById('donation_type').value;
        var cashSection = document.getElementById('cash_section');
        var itemSection = document.getElementById('item_section');

        if (donationType === 'Cash') {
            cashSection.style.display = 'block';
            itemSection.style.display = 'none';
        } else {
            cashSection.style.display = 'none';
            itemSection.style.display = 'block';
        }
    }
</script>
@endsection
