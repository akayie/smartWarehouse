@extends('layouts.front')

@section('title', 'လှူဒါန်းမှုပြုလုပ်ရန် - Smart Relief')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 fw-bold">
                        <i class="fa-solid fa-heart me-2"></i> ကယ်ဆယ်ရေးလုပ်ငန်းများတွင် ပါဝင်လှူဒါန်းရန်
                    </h4>
                </div>

                <div class="card-body p-4">
                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>အောက်ပါအချက်များကို ပြန်လည်စစ်ဆေးပါ။</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="donationForm" action="{{ route('public.donate.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- 1. DONOR INFORMATION --}}
                        <h5 class="fw-bold mb-3 text-secondary">
                            <i class="fa-solid fa-user me-1"></i> ၁။ လှူဒါန်းသူ အချက်အလက်
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">အမည် / အဖွဲ့အစည်း အမည် <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="donor_name" value="{{ old('donor_name', Auth::user()->name ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ဖုန်းနံပါတ် <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" placeholder="09xxxxxxxxx" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">အီးမေးလ်လိပ်စာ <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" placeholder="example@email.com" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- 2. WAREHOUSE & TYPE --}}
                        <h5 class="fw-bold mb-3 text-secondary">
                            <i class="fa-solid fa-warehouse me-1"></i> ၂။ လှူဒါန်းမှု အမျိုးအစားနှင့် ဂိုဒေါင် ရွေးချယ်ပါ
                        </h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">လှူဒါန်းလိုသည့် Warehouse <span class="text-danger">*</span></label>
                                <select class="form-select" name="warehouse_id" required>
                                    <option value="">-- ဂိုဒေါင် / မြို့ ရွေးချယ်ပါ --</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }} @if($warehouse->location) ({{ $warehouse->location }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">လှူဒါန်းမှု အမျိုးအစား <span class="text-danger">*</span></label>
                                <select id="donation_type" name="donation_type" class="form-select" required>
                                    <option value="Cash" {{ old('donation_type') == 'Cash' ? 'selected' : '' }}>ငွေသား (Cash)</option>
                                    <option value="Item" {{ old('donation_type') == 'Item' ? 'selected' : '' }}>ပစ္စည်း (Item)</option>
                                    <option value="Both" {{ old('donation_type') == 'Both' ? 'selected' : '' }}>နှစ်မျိုးလုံး (Cash + Item)</option>
                                </select>
                            </div>

                            <input type="hidden" name="donation_date" value="{{ date('Y-m-d') }}">
                        </div>

                        <hr class="my-4">

                        {{-- 3. CASH DONATION SECTION --}}
                        <div id="cash_section" class="mb-4">
                            <h5 class="fw-bold mb-3 text-secondary">
                                <i class="fa-solid fa-money-bill-transfer me-1"></i> ၃။ ငွေကြေးလှူဒါန်းမှု အသေးစိတ်
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">ငွေပေးချေမှု နည်းလမ်း</label>
                                    <select class="form-select" id="payment_method" name="payment_method">
                                        <option value="">-- နည်းလမ်း ရွေးပါ --</option>
                                        <option value="KBZPay" {{ old('payment_method') == 'KBZPay' ? 'selected' : '' }}>KBZPay</option>
                                        <option value="WaveMoney" {{ old('payment_method') == 'WaveMoney' ? 'selected' : '' }}>WaveMoney</option>
                                        <option value="CBPay" {{ old('payment_method') == 'CBPay' ? 'selected' : '' }}>CBPay</option>
                                        <option value="AYA Pay" {{ old('payment_method') == 'AYA Pay' ? 'selected' : '' }}>AYA Pay</option>
                                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="Cash In Hand" {{ old('payment_method') == 'Cash In Hand' ? 'selected' : '' }}>Cash In Hand</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">လှူဒါန်းငွေ ပမာဏ (ကျပ်)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" min="1" step="1" placeholder="50000">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Transaction Reference / Txn ID</label>
                                    <input type="text" class="form-control" name="transaction_reference" value="{{ old('transaction_reference') }}" placeholder="ဥပမာ - TXN123456">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ငွေလွှဲပြေစာ</label>
                                    <input type="file" class="form-control" name="proof" accept="image/*">
                                </div>
                            </div>
                        </div>

                        {{-- 4. MULTIPLE ITEMS SECTION --}}
                        <div id="item_section" class="mb-4">
                            <hr class="my-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-secondary mb-0">
                                    <i class="fa-solid fa-boxes-stacked me-1"></i> ၄။ ပစ္စည်းလှူဒါန်းမှု အသေးစိတ်
                                </h5>
                                <button type="button" class="btn btn-sm btn-outline-success" id="add_item_btn">
                                    <i class="fa-solid fa-plus me-1"></i> ပစ္စည်းထပ်ထည့်မည်
                                </button>
                            </div>

                            <div id="items_container">
                                {{-- Item Row Template --}}
                                <div class="item-row border rounded p-3 mb-3 bg-light position-relative">
                                    <button type="button" class="btn-close remove-item-btn position-absolute top-0 end-0 m-2" style="display:none;"></button>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">အမျိုးအစား (Category)</label>
                                            <select class="form-select category-select" name="items[0][category_id]">
                                                <option value="">-- Category ရွေးပါ --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">စာရင်းရှိ ပစ္စည်းမှ ရွေးချယ်ရန်</label>
                                            <select class="form-select item-select" name="items[0][item_id]">
                                                <option value="">-- ရှေးဦးစွာ Category ရွေးပါ --</option>
                                                @foreach($items as $item)
                                                    <option value="{{ $item->id }}" data-category-id="{{ $item->category_id }}" style="display:none;">
                                                        {{ $item->name }} @if($item->unit) ({{ $item->unit }}) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">သို့မဟုတ် ပစ္စည်းအသစ်ထည့်ရန်</label>
                                            <input type="text" class="form-control new-item-input" name="items[0][new_item_name]" placeholder="ဥပမာ - ခေါက်ဆွဲခြောက်">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">အရေအတွက်</label>
                                            <input type="number" class="form-control qty-input" name="items[0][quantity]" min="1">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">ယူနစ်</label>
                                            <select class="form-select unit-input" name="items[0][unit]">
                                                <option value="">-- ယူနစ်ရွေးပါ --</option>
                                                <option value="ခု">ခု</option>
                                                <option value="ထုပ်">ထုပ်</option>
                                                <option value="ဗူး">ဗူး</option>
                                                <option value="ကတ်">ကတ်</option>
                                                <option value="ဖာ">ဖာ</option>
                                                <option value="အိတ်">အိတ်</option>
                                                <option value="ကီလို">ကီလို</option>
                                                <option value="စုံ">စုံ</option>
                                                <option value="ခွေ">လုံး</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 expiry-group" style="display:none;">
                                            <label class="form-label">သက်တမ်းကုန်ဆုံးရက်</label>
                                            <input type="date" class="form-control expiry-input" name="items[0][expired_date]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- NOTE --}}
                        <div class="mb-4">
                            <label class="form-label">အခြား ဖြည့်စွက်ချက် / မှတ်ချက်</label>
                            <textarea class="form-control" name="note" rows="3" placeholder="အသေးစိတ် အချက်အလက်...">{{ old('note') }}</textarea>
                        </div>

                        {{-- SUBMIT --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                <i class="fa-solid fa-paper-plane me-1"></i> လှူဒါန်းမှု ပေးပို့မည်
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const donationForm = document.getElementById('donationForm');
    const donationTypeSelect = document.getElementById('donation_type');
    const cashSection = document.getElementById('cash_section');
    const itemSection = document.getElementById('item_section');
    const amount = document.getElementById('amount');
    const itemsContainer = document.getElementById('items_container');
    const addItemBtn = document.getElementById('add_item_btn');

    let itemIndex = 1;

    // Toggle Cash / Item section visibility
    function toggleSections() {
        const type = donationTypeSelect.value;
        if (type === 'Cash') {
            cashSection.style.display = 'block';
            itemSection.style.display = 'none';
        } else if (type === 'Item') {
            cashSection.style.display = 'none';
            itemSection.style.display = 'block';
        } else {
            cashSection.style.display = 'block';
            itemSection.style.display = 'block';
        }
    }

    donationTypeSelect.addEventListener('change', toggleSections);
    toggleSections(); // Run on page load

    // Dynamic Category Change Function
    function handleCategoryChange(categorySelect) {
        const row = categorySelect.closest('.item-row');
        const itemSelect = row.querySelector('.item-select');
        const expiryGroup = row.querySelector('.expiry-group');
        const selectedCategoryId = categorySelect.value;

        itemSelect.selectedIndex = 0;

        Array.from(itemSelect.options).forEach(option => {
            if (option.value === "") {
                option.style.display = "block";
                option.textContent = selectedCategoryId ? "-- ပစ္စည်းရွေးချယ်ပါ --" : "-- ရှေးဦးစွာ Category ရွေးပါ --";
            } else {
                const itemCategoryId = option.getAttribute('data-category-id');
                if (selectedCategoryId && itemCategoryId === selectedCategoryId) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            }
        });

        if (expiryGroup) {
            const selectedText = categorySelect.options[categorySelect.selectedIndex]?.text.toLowerCase() || '';
            const expirableKeywords = ['food', 'water', 'medical', 'hygiene', 'ရိက္ခာ', 'ဆေးဝါး', 'သောက်ရေသန့်'];
            const isExpirable = expirableKeywords.some(keyword => selectedText.includes(keyword));

            expiryGroup.style.display = isExpirable ? 'block' : 'none';
        }
    }

    itemsContainer.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('category-select')) {
            handleCategoryChange(e.target);
        }
    });

    // Dynamic Row Addition
    addItemBtn.addEventListener('click', function () {
        const firstRow = itemsContainer.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('input, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${itemIndex}]`));
            }
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });

        const newItemSelect = newRow.querySelector('.item-select');
        Array.from(newItemSelect.options).forEach(opt => {
            if (opt.value !== "") opt.style.display = "none";
            else opt.textContent = "-- ရှေးဦးစွာ Category ရွေးပါ --";
        });

        const newExpiryGroup = newRow.querySelector('.expiry-group');
        if (newExpiryGroup) newExpiryGroup.style.display = 'none';

        const removeBtn = newRow.querySelector('.remove-item-btn');
        removeBtn.style.display = 'block';
        removeBtn.onclick = function () {
            newRow.remove();
        };

        itemsContainer.appendChild(newRow);
        itemIndex++;
    });

    // Form Submission Validation
    donationForm.addEventListener('submit', function (e) {
        const type = donationTypeSelect.value;
        const hasCash = amount.value && parseFloat(amount.value) > 0;

        let hasItem = false;
        document.querySelectorAll('.item-row').forEach(row => {
            const category = row.querySelector('.category-select').value;
            const item = row.querySelector('.item-select').value;
            const newItem = row.querySelector('.new-item-input').value;
            const qty = row.querySelector('.qty-input').value;

            if (category && (item || newItem) && qty > 0) {
                hasItem = true;
            }
        });

        if ((type === 'Cash' || type === 'Both') && !hasCash) {
            e.preventDefault();
            alert('ကျေးဇူးပြု၍ လှူဒါန်းငွေ ပမာဏကို ဖြည့်သွင်းပေးပါ။');
            return;
        }

        if ((type === 'Item' || type === 'Both') && !hasItem) {
            e.preventDefault();
            alert('ကျေးဇူးပြု၍ ပစ္စည်း အချက်အလက် အနည်းဆုံး တစ်ခု ဖြည့်သွင်းပေးပါ။');
            return;
        }
    });
});
</script>
@endsection
