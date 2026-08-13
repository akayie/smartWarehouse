@extends('layouts.front')

@section('title', 'Make a Donation - Smart Relief')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0 fw-bold"><i class="fa-solid fa-heart me-2"></i> Donate to Relief Efforts</h4>
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
                    <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-user me-1"></i> 1. Donor Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="donor_name" class="form-label">Full Name / Organization <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="donor_name" name="donor_name" required placeholder="John Doe">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" required placeholder="09xxxxxxxxx">
                        </div>
                        <div class="col-md-12">
                            <label for="email" class="form-label">Email Address (Optional)</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- 2. Donation Category Selection -->
                    <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-layer-group me-1"></i> 2. Donation Type</h5>
                    <div class="mb-4">
                        <label for="donation_type" class="form-label">Select Donation Category <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg" id="donation_type" name="donation_type" required onchange="toggleDonationFields()">
                            <option value="Cash">Cash (Financial Contribution)</option>
                            <option value="Food">Food</option>
                            <option value="Water">Water</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Medical">Medical Supplies</option>
                            <option value="Shelter">Shelter Supplies</option>
                            <option value="Hygiene">Hygiene Kits</option>
                            <option value="Rescue Equipment">Rescue Equipment</option>
                            <option value="Other">Other Items</option>
                        </select>
                    </div>

                    <!-- 3. Dynamic Section: CASH (DonationPayment Table) -->
                    <div id="cash_section">
                        <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-money-bill-transfer me-1"></i> 3. Payment Details</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select" id="payment_method" name="payment_method">
                                    <option value="KBZPay">KBZPay</option>
                                    <option value="WaveMoney">WaveMoney</option>
                                    <option value="CBPay">CBPay</option>
                                    <option value="AYA Pay">AYA Pay</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cash In Hand">Cash In Hand</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Amount (MMK) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" placeholder="e.g. 50000">
                            </div>
                            <div class="col-md-6">
                                <label for="transaction_reference" class="form-label">Transaction Reference / Txn ID</label>
                                <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" placeholder="e.g. 2024091200123">
                            </div>
                            <div class="col-md-6">
                                <label for="proof" class="form-label">Payment Slip / Proof Image</label>
                                <input type="file" class="form-control" id="proof" name="proof" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Dynamic Section: PHYSICAL ITEMS (DonationItems Table & Items Table) -->
                    <!-- 3. Dynamic Section: PHYSICAL ITEMS (DonationItems Table & Items Table) -->
<div id="item_section" style="display: none;">
    <h5 class="fw-bold mb-3 text-secondary"><i class="fa-solid fa-boxes-stacked me-1"></i> 3. Item Details</h5>
    <div class="row g-3 mb-4">

        <!-- Existing Catalog Select -->
        <div class="col-md-6">
            <label for="item_id" class="form-label">Choose Existing Item (Optional)</label>
            <select class="form-select" id="item_id" name="item_id">
                <option value="">-- Select from Catalog --</option>
                @foreach($items ?? [] as $item)
                    <option value="{{ $item->id }}" data-unit="{{ $item->unit }}">
                        {{ $item->name }} (Unit: {{ $item->unit ?? 'Pcs' }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- New Item Name (If not in catalog) -->
        <div class="col-md-6">
            <label for="new_item_name" class="form-label">Or Enter New Item Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="new_item_name" name="new_item_name" placeholder="e.g. Instant Noodles, Bottled Water">
            <small class="text-muted">Fill this if the item is not listed in the catalog above.</small>
        </div>

        <!-- Quantity & Unit -->
        <div class="col-md-6">
            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" placeholder="e.g. 50">
        </div>

        <div class="col-md-6">
            <label for="unit" class="form-label">Unit</label>
            <input type="text" class="form-control" id="unit" name="unit" placeholder="e.g. Pcs, Boxes, Packs, KG">
        </div>

    </div>
</div>

                    <!-- Common Note Section -->
                    <div class="mb-4">
                        <label for="note" class="form-label">Additional Note / Remarks</label>
                        <textarea class="form-control" id="note" name="note" rows="2" placeholder="Write any specific details or description here..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="fa-solid fa-paper-plane me-1"></i> Submit Donation
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
