@extends('layouts.front')

@section('content')

<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-md-9 col-lg-8">

            <div class="card shadow-sm border-0">

                {{-- =====================================================
                     HEADER
                ====================================================== --}}
                <div class="card-header bg-danger text-white">

                    <h4 class="mb-0">
                        <i class="fa-solid fa-hand-holding-heart me-2"></i>
                        ကယ်ဆယ်ရေးအကူအညီ တောင်းခံရန်
                    </h4>

                </div>


                <div class="card-body">

                    {{-- =====================================================
                         SUCCESS MESSAGE
                    ====================================================== --}}
                    @if(session('success'))

                        <div class="alert alert-success alert-dismissible fade show">

                            <i class="fa-solid fa-circle-check me-2"></i>

                            {{ session('success') }}

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert">
                            </button>

                        </div>

                    @endif


                    {{-- =====================================================
                         ERROR MESSAGE
                    ====================================================== --}}
                    @if(session('error'))

                        <div class="alert alert-danger alert-dismissible fade show">

                            <i class="fa-solid fa-circle-exclamation me-2"></i>

                            {{ session('error') }}

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert">
                            </button>

                        </div>

                    @endif


                    {{-- =====================================================
                         VALIDATION ERRORS
                    ====================================================== --}}
                    @if($errors->any())

                        <div class="alert alert-danger">

                            <strong>
                                <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                ကျေးဇူးပြု၍ အောက်ပါအချက်များကို ပြင်ဆင်ပါ။
                            </strong>

                            <ul class="mb-0 mt-2">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- =====================================================
                         RELIEF REQUEST FORM
                    ====================================================== --}}
                    <form
                        action="{{ route('public.request.store') }}"
                        method="POST"     enctype="multipart/form-data"
                        id="reliefRequestForm"
                    >

                        @csrf


                        {{-- =================================================
                             REQUESTER INFORMATION
                        ================================================== --}}
                        <div class="mb-4">

                            <h5 class="border-bottom pb-2 mb-3 text-danger">

                                <i class="fa-solid fa-user me-2"></i>

                                တောင်းခံသူအချက်အလက်

                            </h5>


                            <div class="row">

                                {{-- NAME --}}
                                <div class="col-md-6 mb-3">

                                    <label
                                        for="name"
                                        class="form-label fw-bold"
                                    >

                                        တောင်းခံသူအမည်

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>


                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="ဥပမာ - ဦးအောင်အောင်"
                                        maxlength="255"
                                        required
                                    >


                                    @error('name')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- PHONE --}}
                                <div class="col-md-6 mb-3">

                                    <label
                                        for="phone_number"
                                        class="form-label fw-bold"
                                    >

                                        ဖုန်းနံပါတ်

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>


                                    <input
                                        type="tel"
                                        name="phone_number"
                                        id="phone_number"
                                        value="{{ old('phone_number') }}"
                                        class="form-control @error('phone_number') is-invalid @enderror"
                                        placeholder="ဥပမာ - 09xxxxxxxxx"
                                        maxlength="20"
                                        required
                                    >


                                    @error('phone_number')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>



                        {{-- =================================================
                             DISASTER OPTION
                        ================================================== --}}
                        <div class="mb-4">

                            <h5 class="border-bottom pb-2 mb-3 text-danger">

                                <i class="fa-solid fa-triangle-exclamation me-2"></i>

                                ဘေးအန္တရာယ်အချက်အလက်

                            </h5>


                            <label class="form-label fw-bold">

                                ဘေးအန္တရာယ် အမျိုးအစား

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            {{-- EXISTING --}}
                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="disaster_option"
                                    id="existing_disaster"
                                    value="existing"
                                    {{ old('disaster_option', 'existing') === 'existing' ? 'checked' : '' }}
                                >

                                <label
                                    class="form-check-label"
                                    for="existing_disaster"
                                >

                                    ရှိပြီးသား ဘေးအန္တရာယ်ကို ရွေးချယ်ရန်

                                </label>

                            </div>


                            {{-- NEW --}}
                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="disaster_option"
                                    id="new_disaster"
                                    value="new"
                                    {{ old('disaster_option') === 'new' ? 'checked' : '' }}
                                >

                                <label
                                    class="form-check-label"
                                    for="new_disaster"
                                >

                                    ဘေးအန္တရာယ်အသစ် ထည့်သွင်းရန်

                                </label>

                            </div>

                        </div>



                        {{-- =================================================
                             EXISTING DISASTER
                        ================================================== --}}
                        <div
                            class="mb-4"
                            id="existing_disaster_box"
                        >

                            <label
                                for="disaster_id"
                                class="form-label fw-bold"
                            >

                                ဘေးအန္တရာယ်ရွေးပါ

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                name="disaster_id"
                                id="disaster_id"
                                class="form-select @error('disaster_id') is-invalid @enderror"
                            >

                                <option value="">
                                    -- ဘေးအန္တရာယ် ရွေးချယ်ပါ --
                                </option>


                                @foreach($disasters as $disaster)

                                    <option
                                        value="{{ $disaster->id }}"
                                        {{ old('disaster_id') == $disaster->id ? 'selected' : '' }}
                                    >

                                        {{ $disaster->name }}

                                        @if($disaster->type)
                                            ({{ $disaster->type }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>


                            @error('disaster_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- =================================================
                             NEW DISASTER
                        ================================================== --}}
                        <div
                            id="new_disaster_box"
                            class="d-none border rounded p-3 mb-4 bg-light"
                        >

                            <h6 class="fw-bold text-danger mb-3">

                                <i class="fa-solid fa-plus-circle me-1"></i>

                                ဘေးအန္တရာယ်အသစ် အချက်အလက်များ

                            </h6>


                            {{-- NAME --}}
                            <div class="mb-3">

                                <label
                                    for="new_disaster_name"
                                    class="form-label fw-bold"
                                >

                                    ဘေးအန္တရာယ်အမည်

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <input
                                    type="text"
                                    name="new_disaster_name"
                                    id="new_disaster_name"
                                    value="{{ old('new_disaster_name') }}"
                                    class="form-control @error('new_disaster_name') is-invalid @enderror"
                                    placeholder="ဥပမာ - မိုးသည်းထန်စွာရွာသွန်းမှု"
                                >


                                @error('new_disaster_name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- TYPE --}}
                            <div class="mb-3">

                                <label
                                    for="disasterType"
                                    class="form-label fw-bold"
                                >

                                    အမျိုးအစား

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <select
                                    id="disasterType"
                                    name="new_disaster_type"
                                    class="form-select @error('new_disaster_type') is-invalid @enderror"
                                >

                                    <option value="">
                                        -- အမျိုးအစား ရွေးချယ်ပါ --
                                    </option>

                                    <option
                                        value="Flood"
                                        {{ old('new_disaster_type') === 'Flood' ? 'selected' : '' }}
                                    >
                                        ရေကြီးရေလျှံမှု
                                    </option>

                                    <option
                                        value="Earthquake"
                                        {{ old('new_disaster_type') === 'Earthquake' ? 'selected' : '' }}
                                    >
                                        ငလျင်
                                    </option>

                                    <option
                                        value="Cyclone"
                                        {{ old('new_disaster_type') === 'Cyclone' ? 'selected' : '' }}
                                    >
                                        မုန်တိုင်း
                                    </option>

                                    <option
                                        value="Landslide"
                                        {{ old('new_disaster_type') === 'Landslide' ? 'selected' : '' }}
                                    >
                                        မြေပြိုမှု
                                    </option>

                                    <option
                                        value="Fire"
                                        {{ old('new_disaster_type') === 'Fire' ? 'selected' : '' }}
                                    >
                                        မီးလောင်မှု
                                    </option>

                                    <option
                                        value="Drought"
                                        {{ old('new_disaster_type') === 'Drought' ? 'selected' : '' }}
                                    >
                                        မိုးခေါင်မှု
                                    </option>

                                    <option
                                        value="Other"
                                        {{ old('new_disaster_type') === 'Other' ? 'selected' : '' }}
                                    >
                                        အခြား
                                    </option>

                                </select>


                                @error('new_disaster_type')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- DATES --}}
                            <div class="row">

                                {{-- START DATE --}}
                                <div class="col-md-6 mb-3">

                                    <label
                                        for="startDate"
                                        class="form-label fw-bold"
                                    >

                                        စတင်သည့်ရက်

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>


                                    <input
                                        type="date"
                                        id="startDate"
                                        name="start_date"
                                        value="{{ old('start_date') }}"
                                        class="form-control @error('start_date') is-invalid @enderror"
                                    >


                                    @error('start_date')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>


                                {{-- END DATE --}}
                                <div class="col-md-6 mb-3">

                                    <label
                                        for="endDate"
                                        class="form-label fw-bold"
                                    >

                                        ပြီးဆုံးသည့်ရက်

                                        <span class="text-muted fw-normal">
                                            (မဖြည့်လည်းရပါသည်)
                                        </span>

                                    </label>


                                    <input
                                        type="date"
                                        id="endDate"
                                        name="end_date"
                                        value="{{ old('end_date') }}"
                                        class="form-control @error('end_date') is-invalid @enderror"
                                    >


                                    @error('end_date')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>



                        {{-- =================================================
                             LOCATION
                        ================================================== --}}
                        <div class="mb-4">

                            <label
                                for="location"
                                class="form-label fw-bold"
                            >

                                တည်နေရာ / လိပ်စာ

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                name="location"
                                id="location"
                                class="form-control @error('location') is-invalid @enderror"
                                required
                                maxlength="255"
                                placeholder="မြို့နယ် / ကျေးရွာ / လိပ်စာ"
                                value="{{ old('location') }}"
                            >


                            @error('location')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- =================================================
                             MAP
                        ================================================== --}}
                        <div class="mb-4">

                            <label class="form-label fw-bold">

                                <i class="fa-solid fa-location-dot me-1"></i>

                                မြေပုံပေါ်တွင် တည်နေရာအတိအကျညွှန်ပြပါ

                            </label>


                            <div
                                id="map"
                                style="
                                    height: 350px;
                                    width: 100%;
                                    border-radius: 8px;
                                "
                                class="border"
                            >
                            </div>


                            <small class="text-muted d-block mt-2">

                                <i class="fa-solid fa-info-circle me-1"></i>

                                မြေပုံပေါ်ရှိ သက်ဆိုင်ရာနေရာကို Click လုပ်ပြီး
                                တည်နေရာသတ်မှတ်ပါ။

                            </small>


                            <div class="row mt-2">

                                {{-- LATITUDE --}}
                                <div class="col-md-6">

                                    <label
                                        for="latitude"
                                        class="form-label small"
                                    >
                                        Latitude
                                    </label>

                                    <input
                                        type="text"
                                        name="latitude"
                                        id="latitude"
                                        class="form-control form-control-sm"
                                        value="{{ old('latitude') }}"
                                        readonly
                                    >

                                </div>


                                {{-- LONGITUDE --}}
                                <div class="col-md-6">

                                    <label
                                        for="longitude"
                                        class="form-label small"
                                    >
                                        Longitude
                                    </label>

                                    <input
                                        type="text"
                                        name="longitude"
                                        id="longitude"
                                        class="form-control form-control-sm"
                                        value="{{ old('longitude') }}"
                                        readonly
                                    >

                                </div>

                            </div>

                        </div>

                        {{-- =================================================
                            HEALTH / MEDICAL INFORMATION
                        ================================================== --}}
                        <div class="mb-4">

                            <h5 class="border-bottom pb-2 mb-3 text-danger">
                                <i class="fa-solid fa-notes-medical me-2"></i>
                                ကျန်းမာရေးဆိုင်ရာ အချက်အလက်
                            </h5>

                            <label class="form-label fw-bold">
                                ကျန်းမာရေးဆိုင်ရာ အကူအညီ လိုအပ်ပါသလား?

                                <span class="text-danger">*</span>
                            </label>

                            <div class="form-check mb-2">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="is_health_related"
                                    id="health_no"
                                    value="0"
                                    {{ old('is_health_related', '0') == '0' ? 'checked' : '' }}
                                >

                                <label
                                    class="form-check-label"
                                    for="health_no"
                                >
                                    မဟုတ်ပါ
                                </label>
                            </div>

                            <div class="form-check mb-3">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="is_health_related"
                                    id="health_yes"
                                    value="1"
                                    {{ old('is_health_related') == '1' ? 'checked' : '' }}
                                >

                                <label
                                    class="form-check-label"
                                    for="health_yes"
                                >
                                    ဟုတ်ပါသည်
                                </label>
                            </div>


                            {{-- MEDICAL PROOF --}}
                            <div
                                id="medical_proof_box"
                                class="d-none border rounded p-3 bg-light"
                            >

                                <div class="alert alert-warning mb-3">

                                    <i class="fa-solid fa-circle-exclamation me-2"></i>

                                    <strong>အရေးကြီးပါသည်။</strong>

                                    <br>

                                    ကျန်းမာရေးဆိုင်ရာ အကူအညီ တောင်းခံခြင်းဖြစ်ပါက
                                    သက်ဆိုင်ရာ ဆရာဝန်၏ ထောက်ခံချက်
                                    သို့မဟုတ် ဆေးမှတ်တမ်းအထောက်အထားကို
                                    ပုံအဖြစ် တင်ပြပေးပါ။

                                </div>


                                <label
                                    for="medical_proof"
                                    class="form-label fw-bold"
                                >

                                    <i class="fa-solid fa-file-medical me-1"></i>

                                    ဆရာဝန်ထောက်ခံချက် / ဆေးမှတ်တမ်းပုံ

                                    <span class="text-danger">*</span>

                                </label>


                                <input
                                    type="file"
                                    name="medical_proof"
                                    id="medical_proof"
                                    class="form-control @error('medical_proof') is-invalid @enderror"
                                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                >


                                <small class="text-muted d-block mt-2">

                                    <i class="fa-solid fa-info-circle me-1"></i>

                                    JPG, JPEG, PNG, WEBP ဖိုင်များသာ တင်နိုင်ပါသည်။
                                    အများဆုံး 2MB ဖြစ်ရပါမည်။

                                </small>


                                @error('medical_proof')

                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                        {{-- =================================================
                             WAREHOUSE
                        ================================================== --}}
                        <div class="mb-4">

                            <label
                                for="warehouse_id"
                                class="form-label fw-bold"
                            >

                                <i class="fa-solid fa-warehouse me-1"></i>

                                ကုန်ပစ္စည်းထုတ်ယူလိုသော ဂိုဒေါင်

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select
                                name="warehouse_id"
                                id="warehouse_id"
                                class="form-select @error('warehouse_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    -- ဂိုဒေါင် ရွေးချယ်ပါ --
                                </option>


                                @foreach($warehouses as $warehouse)

                                    <option
                                        value="{{ $warehouse->id }}"
                                        {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}
                                    >

                                        {{ $warehouse->name }}

                                        @if($warehouse->location)
                                            ({{ $warehouse->location }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>


                            @error('warehouse_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- =================================================
                             ITEMS
                        ================================================== --}}
                        <div class="mb-4">

                            <label class="form-label fw-bold">

                                <i class="fa-solid fa-boxes-stacked me-1"></i>

                                လိုအပ်သော ပစ္စည်းများနှင့် အရေအတွက်

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <div
                                id="items_container"
                                class="border rounded p-3 bg-light"
                            >

                                <p class="text-muted mb-0">

                                    <i class="fa-solid fa-arrow-up me-1"></i>

                                    ကျေးဇူးပြု၍ ပထမဦးစွာ ဂိုဒေါင်တစ်ခုကို ရွေးချယ်ပါ။

                                </p>

                            </div>


                            @error('items')

                                <div class="text-danger mt-2">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- =================================================
                             NOTE
                        ================================================== --}}
                        <div class="mb-4">

                            <label
                                for="note"
                                class="form-label fw-bold"
                            >

                                <i class="fa-solid fa-note-sticky me-1"></i>

                                ဖြည့်စွက်ချက် / မှတ်ချက်

                            </label>


                            <textarea
                                name="note"
                                id="note"
                                class="form-control"
                                rows="4"
                                maxlength="1000"
                                placeholder="အခြားလိုအပ်သော အချက်အလက်များကို ရေးသားနိုင်ပါသည်..."
                            >{{ old('note') }}</textarea>

                        </div>



                        {{-- =================================================
                             SUBMIT BUTTON
                        ================================================== --}}
                        <div class="d-grid">

                            <button
                                type="submit"
                                id="submitBtn"
                                class="btn btn-danger btn-lg"
                            >

                                <i class="fa-solid fa-paper-plane me-2"></i>

                                တောင်းခံလွှာ ပေးပို့မည်

                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- =====================================================
     FONT AWESOME
====================================================== --}}
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
/>



{{-- =====================================================
     LEAFLET CSS
====================================================== --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>



{{-- =====================================================
     LEAFLET JS
====================================================== --}}
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
</script>



<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | DISASTER TOGGLE
    |--------------------------------------------------------------------------
    */

    const existingRadio =
        document.getElementById('existing_disaster');

    const newRadio =
        document.getElementById('new_disaster');

    const existingBox =
        document.getElementById('existing_disaster_box');

    const newBox =
        document.getElementById('new_disaster_box');

    const disasterSelect =
        document.getElementById('disaster_id');


    function toggleDisasterType() {

        if (newRadio.checked) {

            existingBox.classList.add('d-none');

            newBox.classList.remove('d-none');

            disasterSelect.value = '';

        } else {

            existingBox.classList.remove('d-none');

            newBox.classList.add('d-none');

        }

    }


    existingRadio.addEventListener(
        'change',
        toggleDisasterType
    );


    newRadio.addEventListener(
        'change',
        toggleDisasterType
    );


    toggleDisasterType();

/*
|--------------------------------------------------------------------------
| HEALTH / MEDICAL PROOF TOGGLE
|--------------------------------------------------------------------------
*/

const healthYes =
    document.getElementById('health_yes');

const healthNo =
    document.getElementById('health_no');

const medicalProofBox =
    document.getElementById('medical_proof_box');

const medicalProof =
    document.getElementById('medical_proof');


function toggleMedicalProof() {

    if (healthYes.checked) {

        // Show medical proof upload
        medicalProofBox.classList.remove('d-none');

        // Required
        medicalProof.required = true;

    } else {

        // Hide medical proof upload
        medicalProofBox.classList.add('d-none');

        // Not required
        medicalProof.required = false;

        // Clear selected file
        medicalProof.value = '';
    }
}


        // Radio change events
        healthYes.addEventListener(
            'change',
            toggleMedicalProof
        );

        healthNo.addEventListener(
            'change',
            toggleMedicalProof
        );


        // Check old value when page loads
        toggleMedicalProof();

    /*
    |--------------------------------------------------------------------------
    | LEAFLET MAP
    |--------------------------------------------------------------------------
    */

    const defaultLat = 21.9162;
    const defaultLng = 95.9560;


    const latitudeInput =
        document.getElementById('latitude');

    const longitudeInput =
        document.getElementById('longitude');


    const oldLat =
        latitudeInput.value;

    const oldLng =
        longitudeInput.value;


    const startLat =
        oldLat
            ? parseFloat(oldLat)
            : defaultLat;


    const startLng =
        oldLng
            ? parseFloat(oldLng)
            : defaultLng;


    const map =
        L.map('map').setView(
            [startLat, startLng],
            oldLat && oldLng ? 13 : 6
        );


    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution:
                '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);


    let marker = null;


    /*
    |--------------------------------------------------------------------------
    | OLD LOCATION MARKER
    |--------------------------------------------------------------------------
    */

    if (oldLat && oldLng) {

        marker =
            L.marker([
                startLat,
                startLng
            ]).addTo(map);

    }


    /*
    |--------------------------------------------------------------------------
    | MAP CLICK
    |--------------------------------------------------------------------------
    */

    map.on('click', function (e) {

        const lat =
            e.latlng.lat;

        const lng =
            e.latlng.lng;


        if (marker) {

            marker.setLatLng(e.latlng);

        } else {

            marker =
                L.marker(e.latlng)
                    .addTo(map);

        }


        latitudeInput.value =
            lat.toFixed(8);

        longitudeInput.value =
            lng.toFixed(8);

    });



    /*
    |--------------------------------------------------------------------------
    | WAREHOUSE / INVENTORY
    |--------------------------------------------------------------------------
    */

    const warehouseSelect =
        document.getElementById('warehouse_id');

    const itemsContainer =
        document.getElementById('items_container');


    function fetchWarehouseItems(warehouseId) {

        if (!warehouseId) {

            itemsContainer.innerHTML = `

                <p class="text-muted mb-0">

                    <i class="fa-solid fa-arrow-up me-1"></i>

                    ကျေးဇူးပြု၍ ပထမဦးစွာ
                    ဂိုဒေါင်တစ်ခုကို ရွေးချယ်ပါ။

                </p>

            `;

            return;
        }


        itemsContainer.innerHTML = `

            <div class="text-center py-3">

                <i class="fa-solid fa-spinner fa-spin me-2"></i>

                ပစ္စည်းများ ရယူနေပါသည်...

            </div>

        `;


        fetch(
            `{{ url('/get-warehouse-items') }}/${warehouseId}`,
            {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        )

        .then(response => {

            if (!response.ok) {

                throw new Error(
                    'HTTP Error: ' + response.status
                );

            }

            return response.json();

        })

        .then(data => {

            if (
                !Array.isArray(data) ||
                data.length === 0
            ) {

                itemsContainer.innerHTML = `

                    <div class="alert alert-warning mb-0">

                        <i class="fa-solid fa-box-open me-1"></i>

                        ဤဂိုဒေါင်တွင် လက်ရှိ
                        ပစ္စည်းများ မရှိပါ။

                    </div>

                `;

                return;
            }


            let html = `

                <div class="row">

            `;


            data.forEach(function (inv, index) {

                const itemName =
                    inv.item &&
                    inv.item.name
                        ? inv.item.name
                        : 'ပစ္စည်း';


                const unit =
                    inv.item &&
                    inv.item.unit
                        ? inv.item.unit
                        : '';


                const quantity =
                    parseInt(inv.quantity) || 0;


                html += `

                    <div class="col-md-6 mb-3">

                        <div
                            class="p-3 border rounded bg-white h-100"
                        >

                            <div
                                class="d-flex justify-content-between
                                       align-items-start"
                            >

                                <div>

                                    <strong>
                                        ${itemName}
                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        လက်ကျန်:

                                        <strong class="text-success">
                                            ${quantity}
                                        </strong>

                                        ${unit}

                                    </small>

                                </div>


                                <span class="badge bg-success">

                                    Available

                                </span>

                            </div>


                            <div class="mt-3">

                                <label
                                    class="form-label small fw-bold"
                                >

                                    တောင်းခံမည့်အရေအတွက်

                                </label>


                                <input
                                    type="number"
                                    class="form-control request-quantity"
                                    min="0"
                                    max="${quantity}"
                                    value="0"
                                    data-index="${index}"
                                    data-item-id="${inv.item_id}"
                                    data-max="${quantity}"
                                >


                                <small
                                    class="text-danger quantity-error d-none"
                                >
                                </small>

                            </div>

                        </div>

                    </div>

                `;

            });


            html += `

                </div>

                <div
                    id="selectedItemsSummary"
                    class="mt-2"
                >
                </div>

            `;


            itemsContainer.innerHTML =
                html;


            /*
            |--------------------------------------------------------------------------
            | QUANTITY INPUT EVENTS
            |--------------------------------------------------------------------------
            */

            const quantityInputs =
                itemsContainer.querySelectorAll(
                    '.request-quantity'
                );


            quantityInputs.forEach(function (input) {

                input.addEventListener(
                    'input',
                    function () {

                        const max =
                            parseInt(
                                this.dataset.max
                            ) || 0;


                        let quantity =
                            parseInt(
                                this.value
                            ) || 0;


                        const error =
                            this.parentElement
                                .querySelector(
                                    '.quantity-error'
                                );


                        if (quantity < 0) {

                            quantity = 0;

                            this.value = 0;

                        }


                        if (quantity > max) {

                            quantity = max;

                            this.value = max;


                            error.textContent =
                                `အများဆုံး ${max} သာ တောင်းခံနိုင်ပါသည်။`;

                            error.classList.remove(
                                'd-none'
                            );

                        } else {

                            error.classList.add(
                                'd-none'
                            );

                        }


                        updateSelectedItems();

                    }

                );

            });


            updateSelectedItems();

        })

        .catch(error => {

            console.error(error);


            itemsContainer.innerHTML = `

                <div class="alert alert-danger mb-0">

                    <i class="fa-solid fa-circle-exclamation me-1"></i>

                    ပစ္စည်းများ ရယူရာတွင်
                    အမှားတစ်ခု ဖြစ်ပေါ်ခဲ့ပါသည်။

                    <br>

                    <small>
                        ${error.message}
                    </small>

                </div>

            `;

        });

    }



    /*
    |--------------------------------------------------------------------------
    | UPDATE SELECTED ITEMS
    |--------------------------------------------------------------------------
    */

    function updateSelectedItems() {

        const inputs =
            itemsContainer.querySelectorAll(
                '.request-quantity'
            );


        const summary =
            document.getElementById(
                'selectedItemsSummary'
            );


        if (!summary) {
            return;
        }


        let selectedCount = 0;


        inputs.forEach(function (input) {

            const quantity =
                parseInt(input.value) || 0;


            if (quantity > 0) {

                selectedCount++;

            }

        });


        if (selectedCount > 0) {

            summary.innerHTML = `

                <div class="alert alert-success py-2 mb-0">

                    <i class="fa-solid fa-check-circle me-1"></i>

                    ${selectedCount}
                    မျိုးသော ပစ္စည်းများကို ရွေးချယ်ထားပါသည်။

                </div>

            `;

        } else {

            summary.innerHTML = `

                <div class="alert alert-secondary py-2 mb-0">

                    <i class="fa-solid fa-info-circle me-1"></i>

                    တောင်းခံမည့် ပစ္စည်းများ၏
                    အရေအတွက်ကို ထည့်ပါ။

                </div>

            `;

        }

    }



    /*
    |--------------------------------------------------------------------------
    | WAREHOUSE CHANGE
    |--------------------------------------------------------------------------
    */

    warehouseSelect.addEventListener(
        'change',
        function () {

            fetchWarehouseItems(
                this.value
            );

        }
    );



    /*
    |--------------------------------------------------------------------------
    | LOAD OLD WAREHOUSE AFTER VALIDATION ERROR
    |--------------------------------------------------------------------------
    */

    const oldWarehouse =
        warehouseSelect.value;


    if (oldWarehouse) {

        fetchWarehouseItems(
            oldWarehouse
        );

    }



    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById(
            'reliefRequestForm'
        );


    const submitBtn =
        document.getElementById(
            'submitBtn'
        );


    form.addEventListener(
        'submit',
        function (event) {

            /*
            |--------------------------------------------------------------------------
            | VALIDATE REQUESTER NAME
            |--------------------------------------------------------------------------
            */

            const name =
                document.getElementById(
                    'name'
                ).value.trim();


            if (!name) {

                event.preventDefault();

                alert(
                    'ကျေးဇူးပြု၍ တောင်းခံသူအမည် ထည့်ပါ။'
                );

                document
                    .getElementById('name')
                    .focus();

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | VALIDATE PHONE
            |--------------------------------------------------------------------------
            */

            const phone =
                document.getElementById(
                    'phone_number'
                ).value.trim();


            if (!phone) {

                event.preventDefault();

                alert(
                    'ကျေးဇူးပြု၍ ဖုန်းနံပါတ် ထည့်ပါ။'
                );

                document
                    .getElementById('phone_number')
                    .focus();

                return;

            }

            /*
            |--------------------------------------------------------------------------
            | VALIDATE WAREHOUSE
            |--------------------------------------------------------------------------
            */

            if (!warehouseSelect.value) {

                event.preventDefault();

                alert(
                    'ကျေးဇူးပြု၍ ဂိုဒေါင်တစ်ခုကို ရွေးချယ်ပါ။'
                );

                warehouseSelect.focus();

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | CREATE HIDDEN INPUTS FOR SELECTED ITEMS
            |--------------------------------------------------------------------------
            */

            const oldHiddenInputs =
                form.querySelectorAll(
                    '.generated-item-input'
                );


            oldHiddenInputs.forEach(
                function (input) {

                    input.remove();

                }
            );


            const quantityInputs =
                itemsContainer.querySelectorAll(
                    '.request-quantity'
                );


            let selectedItems = 0;


            quantityInputs.forEach(
                function (input) {

                    const quantity =
                        parseInt(input.value) || 0;


                    const itemId =
                        input.dataset.itemId;


                    if (
                        quantity > 0 &&
                        itemId
                    ) {

                        selectedItems++;


                        /*
                        |--------------------------------------------------------------------------
                        | items[index][item_id]
                        |--------------------------------------------------------------------------
                        */

                        const itemIdInput =
                            document.createElement(
                                'input'
                            );


                        itemIdInput.type =
                            'hidden';


                        itemIdInput.name =
                            `items[${selectedItems - 1}][item_id]`;


                        itemIdInput.value =
                            itemId;


                        itemIdInput.classList.add(
                            'generated-item-input'
                        );


                        form.appendChild(
                            itemIdInput
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | items[index][quantity]
                        |--------------------------------------------------------------------------
                        */

                        const quantityInput =
                            document.createElement(
                                'input'
                            );


                        quantityInput.type =
                            'hidden';


                        quantityInput.name =
                            `items[${selectedItems - 1}][quantity]`;


                        quantityInput.value =
                            quantity;


                        quantityInput.classList.add(
                            'generated-item-input'
                        );


                        form.appendChild(
                            quantityInput
                        );

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | REQUIRE AT LEAST ONE ITEM
            |--------------------------------------------------------------------------
            */

            if (selectedItems === 0) {

                event.preventDefault();

                alert(
                    'ကျေးဇူးပြု၍ တောင်းခံလိုသော ပစ္စည်းနှင့် အရေအတွက်ကို ထည့်ပါ။'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | DISABLE SUBMIT BUTTON
            |--------------------------------------------------------------------------
            */

            submitBtn.disabled = true;


            submitBtn.innerHTML = `

                <i class="fa-solid fa-spinner fa-spin me-2"></i>

                ပေးပို့နေပါသည်...

            `;

        }

    );



    /*
    |--------------------------------------------------------------------------
    | PHONE NUMBER - REMOVE INVALID CHARACTERS
    |--------------------------------------------------------------------------
    */

    const phoneInput =
        document.getElementById(
            'phone_number'
        );


    phoneInput.addEventListener(
        'input',
        function () {

            this.value =
                this.value.replace(
                    /[^0-9+]/g,
                    ''
                );

        }
    );



    /*
    |--------------------------------------------------------------------------
    | START / END DATE VALIDATION
    |--------------------------------------------------------------------------
    */

    const startDate =
        document.getElementById(
            'startDate'
        );


    const endDate =
        document.getElementById(
            'endDate'
        );


    startDate.addEventListener(
        'change',
        function () {

            endDate.min =
                this.value;

        }
    );


    if (startDate.value) {

        endDate.min =
            startDate.value;

    }

});

</script>


@endsection
