@extends('layouts.admin')

@section('title', 'QR / Barcode Scanner')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
        <div class="card-body p-4 text-center">
            <h4 class="fw-bold text-primary mb-1">
                <i class="fa-solid fa-qrcode me-2"></i>Bar Code / QR Code စနစ်
            </h4>
            <p class="text-muted small mb-3">သိုလှောင်ရုံအလိုက် ကုန်ပစ္စည်း အဝင်/အထွက် စာရင်းသွင်းခြင်း</p>

            <!-- Warehouse Dropdown -->
            <div class="mb-3 text-start">
                <label for="warehouse_id" class="form-label fw-semibold">
                    <i class="fas fa-warehouse me-1"></i> သိုလှောင်ရုံ (Warehouse)
                </label>
                <select id="warehouse_id" class="form-select border-primary fw-bold">
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Manual Barcode Input -->
            <div class="mb-3 text-start">
                <label for="manual_barcode_input" class="form-label fw-semibold">
                    <i class="fas fa-barcode me-1"></i> Barcode / ID ရိုက်ထည့်ရန်
                </label>
                <input type="text" id="manual_barcode_input" class="form-control" placeholder="Barcode စကင်ဖတ်ပါ သို့မဟုတ် ရိုက်ထည့်ပါ..." autofocus>
            </div>

            <div class="text-muted small mb-2">- သို့မဟုတ် Camera ဖြင့် Scan ဖတ်ပါ -</div>

            <!-- Camera Scanner Feed -->
            <div id="qr-reader" class="rounded border border-primary p-2 mb-3 bg-light" style="width: 100%;"></div>

            <!-- Dynamic Scanned Item Info -->
            <div id="scanned-result" class="alert alert-info d-none text-start mb-3">
                <div class="fw-bold mb-1">
                    <i class="fas fa-check-circle text-success me-1"></i> ဖတ်ယူရရှိသော ကုဒ် :
                    <span id="scanned-code-text" class="text-dark font-monospace fs-5"></span>
                </div>
                <div class="fw-bold text-primary fs-5 mt-2">
                    <i class="fas fa-box me-1"></i> ပစ္စည်းအမည် : <span id="scanned-item-name" class="text-dark">ရှာဖွေနေသည်...</span>
                </div>
                <input type="hidden" id="item_id_or_code">
            </div>

            <!-- Quantity Field -->
            <div class="mb-3 text-start">
                <label for="scan_qty" class="form-label fw-semibold">အရေအတွက် (Quantity)</label>
                <input type="number" id="scan_qty" class="form-control" value="1" min="1" oninput="calculateAmount()">
            </div>

            <!-- Price Field -->
            <div class="mb-3 text-start">
                <label for="scan_price" class="form-label fw-semibold">တစ်ခုချင်း ဈေးနှုန်း (Price)</label>
                <input type="number" id="scan_price" class="form-control" value="0" min="0" step="0.01" placeholder="ဈေးနှုန်းထည့်ပါ..." oninput="calculateAmount()">
            </div>

            <!-- Total Amount Display -->
            <div class="mb-3 text-start">
                <label class="form-label fw-semibold text-success">စုစုပေါင်း ကျသင့်ငွေ (Amount)</label>
                <input type="text" id="scan_amount" class="form-control fw-bold text-success bg-light" value="0.00" readonly>
            </div>

            <!-- Expiry Date Field -->
            <div class="mb-3 text-start" id="expiry_date_container">
                <label for="expiry_date" class="form-label fw-semibold">
                    သက်တမ်းကုန်ဆုံးရက် <small class="text-muted">(Stock-In အတွက်)</small>
                </label>
                <input type="date" id="expiry_date" class="form-control" value="{{ date('Y-m-d', strtotime('+1 year')) }}">
            </div>

            <!-- Actions -->
            <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-success fw-bold w-50 py-2" onclick="processStock('IN')">
                    <i class="fa-solid fa-arrow-down me-1"></i> Stock IN (အဝင်)
                </button>
                <button class="btn btn-danger fw-bold w-50 py-2" onclick="processStock('OUT')">
                    <i class="fa-solid fa-arrow-up me-1"></i> Stock OUT (အထွက်)
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let lastScannedCode = null;
    let lastScannedTime = 0;

    // Amount = Price * Qty တွက်ချက်ပေးသည့် function
    function calculateAmount() {
        const qty = parseFloat(document.getElementById('scan_qty').value) || 0;
        const price = parseFloat(document.getElementById('scan_price').value) || 0;
        const amount = qty * price;
        document.getElementById('scan_amount').value = amount.toFixed(2);
    }

    function fetchItemDetails(code) {
        document.getElementById('scanned-item-name').innerText = 'ရှာဖွေနေသည်...';

        const baseUrl = "{{ route('backend.items.getByBarcode', ':code') }}";
        const fetchUrl = baseUrl.replace(':code', encodeURIComponent(code));

        fetch(fetchUrl)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const item = data.item || data.data;
                    document.getElementById('scanned-item-name').innerText = item ? item.name : 'မသိရှိသော ပစ္စည်း';
                } else {
                    document.getElementById('scanned-item-name').innerText = 'မသိရှိသော ပစ္စည်း';
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                document.getElementById('scanned-item-name').innerText = 'မသိရှိသော ပစ္စည်း';
            });
    }

    function setScannedCode(code) {
        const cleanedCode = code.trim();
        document.getElementById('scanned-code-text').innerText = cleanedCode;
        document.getElementById('item_id_or_code').value = cleanedCode;
        document.getElementById('scanned-result').classList.remove('d-none');
        fetchItemDetails(cleanedCode);
    }

    function onScanSuccess(decodedText) {
        const currentTime = new Date().getTime();
        if (decodedText === lastScannedCode && (currentTime - lastScannedTime) < 1500) {
            return;
        }
        lastScannedCode = decodedText;
        lastScannedTime = currentTime;
        setScannedCode(decodedText);
    }

    document.addEventListener("DOMContentLoaded", function () {
        const manualInput = document.getElementById('manual_barcode_input');

        manualInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.value.trim() !== '') {
                    setScannedCode(this.value);
                    this.value = '';
                }
            }
        });

        const config = {
            fps: 15,
            qrbox: { width: 280, height: 180 },
            experimentalFeatures: { useBarCodeDetectorIfSupported: true },
            formatsToSupport: [
                Html5QrcodeSupportedFormats.QR_CODE,
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.UPC_A,
                Html5QrcodeSupportedFormats.UPC_E,
                Html5QrcodeSupportedFormats.CODE_39
            ]
        };

        const html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", config, false);
        html5QrcodeScanner.render(onScanSuccess);
    });

    function processStock(type) {
        const itemCode = document.getElementById('item_id_or_code').value;
        const warehouseId = document.getElementById('warehouse_id').value;
        const quantity = document.getElementById('scan_qty').value;
        const price = document.getElementById('scan_price').value;
        const expiryDate = document.getElementById('expiry_date').value;

        if (!itemCode) {
            alert('ကျေးဇူးပြု၍ QR/Barcode ဖတ်ပါ။');
            return;
        }

        fetch("{{ route('backend.qr.process') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                item_code: itemCode,
                warehouse_id: warehouseId,
                quantity: quantity,
                price: price,
                expiry_date: expiryDate,
                type: type
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(`အောင်မြင်ပါသည်: ${data.message}\nပစ္စည်းအမည်: ${data.item_name}\nလက်ကျန်အသစ်: ${data.new_balance}`);
                document.getElementById('scanned-result').classList.add('d-none');
                document.getElementById('item_id_or_code').value = '';
                document.getElementById('scan_qty').value = 1;
                document.getElementById('scan_price').value = 0;
                document.getElementById('scan_amount').value = '0.00';
                document.getElementById('manual_barcode_input').focus();
            } else {
                alert(`မအောင်မြင်ပါ: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('စနစ်ချို့ယွင်းချက် ရှိနေပါသည်။');
        });
    }
</script>
@endsection
