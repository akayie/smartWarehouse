@extends('layouts.admin')

@section('title', 'ဘေးအန္တရာယ်ဖြစ်ရပ် အသစ်ထည့်သွင်းခြင်း')

@section('button')
<a href="{{ route('backend.disasters.index') }}" class="btn btn-outline-secondary btn-sm">
    <i class="fa-solid fa-arrow-left me-1"></i> ဘေးအန္တရာယ်စာရင်းသို့ ပြန်သွားရန်
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white py-3 d-flex align-items-center">
                <h5 class="m-0 font-weight-bold text-danger">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    ဘေးအန္တရာယ်ဖြစ်ရပ် အသစ်ထည့်သွင်းခြင်း
                </h5>
            </div>

            <div class="card-body p-4">

                <form action="{{ route('backend.disasters.store') }}"
                      method="POST"
                      id="createDisasterForm">

                    @csrf

                    {{-- Disaster Name --}}
                    <div class="mb-3">
                        <label for="disasterName" class="form-label font-weight-bold text-dark">
                            ဘေးအန္တရာယ်ဖြစ်ရပ် အမည် <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">
                                <i class="fa-solid fa-bullhorn"></i>
                            </span>
                            <input type="text"
                                   id="disasterName"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="ဥပမာ - စစ်ကိုင်းရေဘေး ကယ်ဆယ်ရေးလုပ်ငန်း ၂၀၂၆"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Disaster Type --}}
                        <div class="col-md-6 mb-3">
                            <label for="disasterType" class="form-label font-weight-bold text-dark">
                                ဘေးအန္တရာယ် အမျိုးအစား <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">
                                    <i class="fa-solid fa-list"></i>
                                </span>
                                <select id="disasterType"
                                        name="type"
                                        class="form-select @error('type') is-invalid @enderror"
                                        required>
                                    <option value="">-- အမျိုးအစား ရွေးချယ်ပါ --</option>
                                    <option value="Flood" {{ old('type') == 'Flood' ? 'selected' : '' }}>ရေကြီးရေလျှံမှု</option>
                                    <option value="Earthquake" {{ old('type') == 'Earthquake' ? 'selected' : '' }}>ငလျင်</option>
                                    <option value="Cyclone" {{ old('type') == 'Cyclone' ? 'selected' : '' }}>မုန်တိုင်း</option>
                                    <option value="Landslide" {{ old('type') == 'Landslide' ? 'selected' : '' }}>မြေပြိုမှု</option>
                                    <option value="Fire" {{ old('type') == 'Fire' ? 'selected' : '' }}>မီးလောင်မှု</option>
                                    <option value="Drought" {{ old('type') == 'Drought' ? 'selected' : '' }}>မိုးခေါင်မှု</option>
                                    <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>အခြား</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="col-md-6 mb-3">
                            <label for="disasterLocation" class="form-label font-weight-bold text-dark">
                                ဘေးအန္တရာယ်ဖြစ်ပွားရာ နေရာ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">
                                    <i class="fa-solid fa-location-dot"></i>
                                </span>
                                <input type="text"
                                       id="disasterLocation"
                                       name="location"
                                       value="{{ old('location') }}"
                                       placeholder="ဥပမာ - မုံရွာမြို့၊ စစ်ကိုင်းတိုင်း"
                                       class="form-control @error('location') is-invalid @enderror"
                                       required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Start Date --}}
                        <div class="col-md-6 mb-3">
                            <label for="startDate" class="form-label font-weight-bold text-dark">
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
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- End Date --}}
                        <div class="col-md-6 mb-3">
                            <label for="endDate" class="form-label font-weight-bold text-dark">
                                ပြီးဆုံးသည့်ရက် <span class="text-muted font-weight-normal">(မဖြည့်လည်းရပါသည်)</span>
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

                    {{-- Status --}}
                    <div class="mb-4">
                        <label for="disasterStatus" class="form-label font-weight-bold text-dark">
                            ဘေးအန္တရာယ်ဖြစ်ရပ် အခြေအနေ <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">
                                <i class="fa-solid fa-flag"></i>
                            </span>
                            <select id="disasterStatus"
                                    name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>
                                <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>
                                    လက်ရှိဆောင်ရွက်နေဆဲ
                                </option>
                                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>
                                    ကယ်ဆယ်ရေးလုပ်ငန်း ပြီးစီး
                                </option>
                                <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>
                                    ပယ်ဖျက်ပြီး
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('backend.disasters.index') }}" class="btn btn-light border px-4">
                            ပယ်ဖျက်ရန်
                        </a>
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> ဘေးအန္တရာယ်ဖြစ်ရပ် သိမ်းဆည်းရန်
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
