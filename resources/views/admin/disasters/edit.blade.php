@extends('layouts.admin')

@section('title', 'Edit Disaster Event')

@section('button')
<a href="{{ route('backend.disasters.index') }}" class="btn btn-outline-secondary btn-sm">
    <i class="fa-solid fa-arrow-left me-1"></i> Back to Disasters
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-danger">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Disaster Campaign
                </h5>
                <span class="badge bg-light text-dark border">ID: #{{ $disaster->id }}</span>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('backend.disasters.update', $disaster->id) }}" method="POST" id="editDisasterForm">
                    @csrf
                    @method('PUT')

                    <!-- Disaster Name -->
                    <div class="mb-3">
                        <label for="disasterName" class="form-label font-weight-bold text-dark">
                            Disaster Event Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-bullhorn"></i></span>
                            <input type="text"
                                   id="disasterName"
                                   name="name"
                                   value="{{ old('name', $disaster->name) }}"
                                   placeholder="e.g., Sagaing Flood Relief 2026"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Disaster Type -->
                        <div class="col-md-6 mb-3">
                            <label for="disasterType" class="form-label font-weight-bold text-dark">
                                Disaster Type <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-list"></i></span>
                                <select id="disasterType"
                                        name="type"
                                        class="form-select @error('type') is-invalid @enderror"
                                        required>
                                    <option value="">-- Select Type --</option>
                                    @php
                                        $selectedType = old('type', $disaster->type);
                                        $types = ['Flood', 'Earthquake', 'Cyclone', 'Landslide', 'Fire', 'Drought', 'Other'];
                                    @endphp
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ $selectedType == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Zone -->
                        <div class="col-md-6 mb-3">
                            <label for="disasterLocation" class="form-label font-weight-bold text-dark">
                                Location Zone <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-location-dot"></i></span>
                                <input type="text"
                                       id="disasterLocation"
                                       name="location"
                                       value="{{ old('location', $disaster->location) }}"
                                       placeholder="e.g., Monywa, Sagaing Region"
                                       class="form-control @error('location') is-invalid @enderror"
                                       required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Start Date -->
                        <div class="col-md-6 mb-3">
                            <label for="startDate" class="form-label font-weight-bold text-dark">
                                Start Date <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-calendar-day"></i></span>
                                <input type="date"
                                       id="startDate"
                                       name="start_date"
                                       value="{{ old('start_date', is_string($disaster->start_date) ? $disaster->start_date : ($disaster->start_date ? $disaster->start_date->format('Y-m-d') : '')) }}"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="col-md-6 mb-3">
                            <label for="endDate" class="form-label font-weight-bold text-dark">
                                End Date <span class="text-muted font-weight-normal">(Optional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-calendar-check"></i></span>
                                <input type="date"
                                       id="endDate"
                                       name="end_date"
                                       value="{{ old('end_date', is_string($disaster->end_date) ? $disaster->end_date : ($disaster->end_date ? $disaster->end_date->format('Y-m-d') : '')) }}"
                                       class="form-control @error('end_date') is-invalid @enderror">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status Selection -->
                    <div class="mb-4">
                        <label for="disasterStatus" class="form-label font-weight-bold text-dark">
                            Campaign Status <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-flag"></i></span>
                            @php
                                $selectedStatus = old('status', $disaster->status);
                            @endphp
                            <select id="disasterStatus"
                                    name="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    required>
                                <option value="Active" {{ strtolower($selectedStatus) === 'active' ? 'selected' : '' }}>
                                    Active (Ongoing Emergency Response)
                                </option>
                                <option value="Completed" {{ strtolower($selectedStatus) === 'completed' ? 'selected' : '' }}>
                                    Completed (Relief Completed)
                                </option>
                                <option value="Cancelled" {{ strtolower($selectedStatus) === 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <!-- Actions -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('backend.disasters.index') }}" class="btn btn-light border px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-warning px-4 text-dark font-weight-bold">
                            <i class="fa-solid fa-rotate me-1"></i> Update Campaign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
