@extends('layouts.admin')

@section('title', 'QR / Barcode လုပ်ငန်းစဉ်များ')

@section('content')
<div id="adm-qr" class="container py-4">
    <div class="card shadow-sm border-0" style="max-width: 550px; margin: 0 auto;">
        <div class="card-body p-4 text-center">
            <h4 class="fw-bold text-primary mb-1">
                <i class="fa-solid fa-qrcode me-2"></i>Bar Code/ QR Code လုပ်ငန်းစဉ်များ
            </h4>
            <p class="text-muted small mb-3">Scanner ဖြင့် ကုန်ပစ္စည်း အဝင်/အထွက် စစ်ဆေးခြင်း</p>

            {{-- Manual / USB Hardware Scanner Input --}}
            <div class="mb-3 text-start">
                <label for="manual_barcode_input" class="form-label fw-semibold">
                    <i class="fas fa-barcode me-1"></i> Manual (သို့) USB Scanner ဖြင့် ထည့်သွင်းရန်
                </label>
                <input type="text" id="manual_barcode_input" class="form-control" placeholder="ဘားကုဒ်ကို စကင်ဖတ်ပါ (သို့) ရိုက်ထည့်ပါ..." autofocus>
            </div>

            <div class="text-muted small mb-2">- သို့မဟုတ် အောက်ပါ Camera Scannerကို အသုံးပြုပါ -</div>

            {{-- Camera Feed Container --}}
            <div id="qr-reader" class="rounded border border-primary p-2 mb-3 bg-light" style="width: 100%;"></div>

            {{-- Scanned Result Display --}}
            <div id="scanned-result" class="alert alert-info d-none text-start mb-3">
                <div class="fw-bold">
                    <i class="fas fa-check-circle text-success me-1"></i> ဖတ်ယူရရှိသော ကုဒ် -
                    <span id="scanned-code-text" class="text-dark font-monospace fs-5"></span>
                </div>
                <input type="hidden" id="item_id_or_code">
            </div>

            {{-- Quantity Input --}}
            <div class="mb-3 text-start">
                <label for="scan_qty" class="form-label fw-semibold">အရေအတွက်</label>
                <input type="number" id="scan_qty" class="form-control" value="1" min="1">
            </div>

            {{-- Expiry Date Input (Stock-In အတွက်) --}}
            <div class="mb-3 text-start" id="expiry_date_container">
                <label for="expiry_date" class="form-label fw-semibold">သက်တမ်းကုန်ဆုံးရက်</label>
                <input type="date" id="expiry_date" class="form-control" value="{{ date('Y-m-d', strtotime('+1 year')) }}">
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-success fw-bold w-50 py-2" onclick="processStock('IN')">
                    <i class="fa-solid fa-arrow-down me-1"></i> ကုန်အဝင် Scan ဖတ်မည်
                </button>
                <button class="btn btn-danger fw-bold w-50 py-2" onclick="processStock('OUT')">
                    <i class="fa-solid fa-arrow-up me-1"></i> ကုန်အထွက် Scan ဖတ်မည်
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrcodeScanner;
    let lastScannedCode = null;
    let lastScannedTime = 0;

    function setScannedCode(code) {
        const cleanedCode = code.trim();
        document.getElementById('scanned-code-text').innerText = cleanedCode;
        document.getElementById('item_id_or_code').value = cleanedCode;
        document.getElementById('scanned-result').classList.remove('d-none');
    }

    function onScanSuccess(decodedText, decodedResult) {
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

        html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", config, false);
        html5QrcodeScanner.render(onScanSuccess);
    });

    function processStock(type) {
        const itemCode = document.getElementById('item_id_or_code').value;
        const quantity = document.getElementById('scan_qty').value;
        const expiryDate = document.getElementById('expiry_date').value;

        if (!itemCode) {
            alert('ကျေးဇူးပြု၍ QR Code / Barcode ကို အရင် Scan ဖတ်ပါ။');
            return;
        }

        if (type === 'IN' && !expiryDate) {
            alert('ကျေးဇူးပြု၍ သက်တမ်းကုန်ဆုံးရက် ထည့်သွင်းပေးပါ။');
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
                quantity: quantity,
                expiry_date: expiryDate,
                type: type
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`အောင်မြင်ပါသည်: ${data.message}\nပစ္စည်းအမည်: ${data.item_name}\nလက်ကျန် အရေအတွက်သစ်: ${data.new_balance}`);

                // Reset Scan Field
                document.getElementById('scanned-result').classList.add('d-none');
                document.getElementById('item_id_or_code').value = '';
                document.getElementById('scan_qty').value = 1;
                document.getElementById('manual_barcode_input').focus();
            } else {
                alert(`မအောင်မြင်ပါ: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('တစ်ခုခု မှားယွင်းနေပါသည်။ ပြန်လည် ကြိုးစားပါ။');
        });
    }
</script>
@endsection
