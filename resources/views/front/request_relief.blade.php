@extends('layouts.front')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">ကယ်ဆယ်ရေးအကူအညီ တောင်းခံရန်</h4>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('public.request.store') }}" method="POST">
                        @csrf

                        {{-- Disaster Selection Options --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">ဘေးအန္တရာယ် အမျိုးအစား</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="disaster_option" id="existing_disaster" value="existing" checked>
                                <label class="form-check-label" for="existing_disaster">
                                    ရှိပြီးသား ဘေးအန္တရာယ်ကို ရွေးချယ်ရန်
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="disaster_option" id="new_disaster" value="new">
                                <label class="form-check-label" for="new_disaster">
                                    ဘေးအန္တရာယ်အသစ် ထည့်သွင်းရန်
                                </label>
                            </div>
                        </div>

                        {{-- Existing Disaster Dropdown --}}
                        <div class="mb-3" id="existing_disaster_box">
                            <label for="disaster_id" class="form-label fw-bold">ဘေးအန္တရာယ်ရွေးပါ</label>
                            <select name="disaster_id" id="disaster_id" class="form-select @error('disaster_id') is-invalid @enderror">
                                <option value="">-- ရွေးချယ်ပါ --</option>
                                @foreach($disasters as $disaster)
                                    <option value="{{ $disaster->id }}">{{ $disaster->name }} ({{ $disaster->type }})</option>
                                @endforeach
                            </select>
                            @error('disaster_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- New Disaster Inputs --}}
                        <div id="new_disaster_box" class="d-none border p-3 rounded mb-3 bg-light">
                            <h6 class="fw-bold text-danger mb-3">ဘေးအန္တရာယ်အသစ် အချက်အလက်များ</h6>

                            {{-- Disaster Name --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">ဘေးအန္တရာယ် အမည် <span class="text-danger">*</span></label>
                                <input type="text" name="new_disaster_name" value="{{ old('new_disaster_name') }}" class="form-control @error('new_disaster_name') is-invalid @enderror" placeholder="ဥပမာ - ရေကြီးရေလျှံမှု">
                                @error('new_disaster_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Disaster Type --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">အမျိုးအစား <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted">
                                        <i class="fa-solid fa-list"></i>
                                    </span>
                                    <select id="disasterType"
                                            name="new_disaster_type"
                                            class="form-select @error('new_disaster_type') is-invalid @enderror">
                                        <option value="">-- အမျိုးအစား ရွေးချယ်ပါ --</option>
                                        <option value="Flood" {{ old('new_disaster_type') == 'Flood' ? 'selected' : '' }}>ရေကြီးရေလျှံမှု</option>
                                        <option value="Earthquake" {{ old('new_disaster_type') == 'Earthquake' ? 'selected' : '' }}>ငလျင်</option>
                                        <option value="Cyclone" {{ old('new_disaster_type') == 'Cyclone' ? 'selected' : '' }}>မုန်တိုင်း</option>
                                        <option value="Landslide" {{ old('new_disaster_type') == 'Landslide' ? 'selected' : '' }}>မြေပြိုမှု</option>
                                        <option value="Fire" {{ old('new_disaster_type') == 'Fire' ? 'selected' : '' }}>မီးလောင်မှု</option>
                                        <option value="Drought" {{ old('new_disaster_type') == 'Drought' ? 'selected' : '' }}>မိုးခေါင်မှု</option>
                                        <option value="Other" {{ old('new_disaster_type') == 'Other' ? 'selected' : '' }}>အခြား</option>
                                    </select>
                                    @error('new_disaster_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Start Date & End Date Row --}}
                            <div class="row">
                                {{-- Start Date --}}
                                <div class="col-md-6 mb-3">
                                    <label for="startDate" class="form-label fw-bold text-dark">
                                        စတင်သည့်ရက် <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted">
                                            <i class="fa-solid fa-calendar-day"></i>
                                        </span>
                                        <input type="date"
                                               id="startDate"
                                               name="start_date"
                                               value="{{ old('start_date') }}"
                                               class="form-control @error('start_date') is-invalid @enderror">
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- End Date --}}
                                <div class="col-md-6 mb-3">
                                    <label for="endDate" class="form-label fw-bold text-dark">
                                        ပြီးဆုံးသည့်ရက် <span class="text-muted fw-normal">(မဖြည့်လည်းရပါသည်)</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted">
                                            <i class="fa-solid fa-calendar-check"></i>
                                        </span>
                                        <input type="date"
                                               id="endDate"
                                               name="end_date"
                                               value="{{ old('end_date') }}"
                                               class="form-control @error('end_date') is-invalid @enderror">
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Location & Leaflet Map --}}
                        <div class="mb-3">
                            <label for="location" class="form-label fw-bold">တည်နေရာ / လိပ်စာ</label>
                            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" required placeholder="မြို့နယ်/ကျေးရွာ အမည်" value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">မြေပုံပေါ်တွင် တည်နေရာ အတိအကျညွှန်ပြပါ</label>
                            <div id="map" style="height: 300px;" class="rounded border"></div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                        </div>

                        {{-- Warehouse Selection --}}
                        <div class="mb-3">
                            <label for="warehouse_id" class="form-label fw-bold">ကုန်ပစ္စည်းထုတ်ယူလိုသော ဂိုဒေါင်</label>
                            <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                                <option value="">-- ဂိုဒေါင် ရွေးချယ်ပါ --</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }} ({{ $warehouse->location }})
                                    </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Warehouse Items Container (Loaded via AJAX) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">လိုအပ်သော ပစ္စည်းများနှင့် အရေအတွက်</label>
                            <div id="items_container" class="border p-3 rounded bg-light">
                                <p class="text-muted mb-0">ကျေးဇူးပြု၍ ပထမဦးစွာ ဂိုဒေါင်တစ်ခုကို ရွေးချယ်ပါ။</p>
                            </div>
                        </div>

                        {{-- Note --}}
                        <div class="mb-3">
                            <label for="note" class="form-label fw-bold">ဖြည့်စွက်ချက် / မှတ်ချက်</label>
                            <textarea name="note" id="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">တောင်းခံလွှာ ပေးပို့မည်</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Font Awesome Font Library --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

{{-- Leaflet CSS/JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Toggle New/Existing Disaster Box
    const existingRadio = document.getElementById('existing_disaster');
    const newRadio = document.getElementById('new_disaster');
    const existingBox = document.getElementById('existing_disaster_box');
    const newBox = document.getElementById('new_disaster_box');

    function toggleDisasterType() {
        if (newRadio.checked) {
            existingBox.classList.add('d-none');
            newBox.classList.remove('d-none');
        } else {
            existingBox.classList.remove('d-none');
            newBox.classList.add('d-none');
        }
    }

    existingRadio.addEventListener('change', toggleDisasterType);
    newRadio.addEventListener('change', toggleDisasterType);

    // Initial check on load (in case of validation error reload)
    toggleDisasterType();

    // 2. Leaflet Map Setup
    const defaultLat = 21.9162; // Default Myanmar Center
    const defaultLng = 95.9560;
    const map = L.map('map').setView([defaultLat, defaultLng], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    });

    // 3. AJAX Warehouse Items Fetching
    const warehouseSelect = document.getElementById('warehouse_id');
    const itemsContainer = document.getElementById('items_container');

    function fetchWarehouseItems(warehouseId) {
        if (!warehouseId) {
            itemsContainer.innerHTML = '<p class="text-muted mb-0">ကျေးဇူးပြု၍ ပထမဦးစွာ ဂိုဒေါင်တစ်ခုကို ရွေးချယ်ပါ။</p>';
            return;
        }

        itemsContainer.innerHTML = '<p class="text-muted mb-0"><i class="fa-solid fa-spinner fa-spin me-1"></i> ပစ္စည်းများ ရယူနေပါသည်...</p>';

        fetch(`/get-warehouse-items/${warehouseId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    itemsContainer.innerHTML = '<p class="text-warning mb-0">ဤဂိုဒေါင်တွင် လက်ရှိ ပစ္စည်းများ မရှိပါ။</p>';
                    return;
                }

                let html = '<div class="row">';
                data.forEach((inv, index) => {
                    html += `
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-white">
                                <div>
                                    <strong>${inv.item ? inv.item.name : 'Item'}</strong><br>
                                    <small class="text-muted">လက်ကျန်: ${inv.quantity}</small>
                                </div>
                                <input type="hidden" name="items[${index}][item_id]" value="${inv.item_id}">
                                <input type="number" name="items[${index}][quantity]" class="form-control form-control-sm w-25" min="0" max="${inv.quantity}" value="0">
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                itemsContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                itemsContainer.innerHTML = '<p class="text-danger mb-0">ပစ္စည်းများ ဆွဲယူရာတွင် အမှားအယွင်းရှိပါသည်</p>';
            });
    }

    warehouseSelect.addEventListener('change', function() {
        fetchWarehouseItems(this.value);
    });

    if (warehouseSelect.value) {
        fetchWarehouseItems(warehouseSelect.value);
    }
});
</script>
@endsection
