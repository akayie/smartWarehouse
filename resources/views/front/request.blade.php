@extends('layouts.front') {{-- သင့် Project ၏ Layout ဖိုင်အမည် ပေါင်းစပ်ပါ --}}

@section('content')
<!-- PAGE 3: REQUEST RELIEF AID -->
<div id="pub-request" class="sub-page">
    <div class="card form-card">
        <h2><i class="fa-solid fa-hand-holding-medical icon-red"></i> Request Disaster Relief Aid</h2>
        <p class="section-desc">Please fill in accurate information to expedite verification and relief dispatch.</p>

        @if(session('success'))
            <div class="alert alert-success" style="color: green; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('public.request.store') }}" method="POST">
            @csrf

            <!-- Disaster Event Select -->
            <div class="form-group">
                <label for="disaster-event">Disaster Event</label>
                <select name="disaster_id" id="disaster-event" class="form-control" required>
                    <option value="">-- Select Disaster Event --</option>
                    @foreach($disasters as $disaster)
                        <option value="{{ $disaster->id }}">{{ $disaster->name }} ({{ $disaster->location }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Target Location / Camp Name -->
            <div class="form-group">
                <label for="target-location">Target Location / Camp Name</label>
                <input type="text" name="location" id="target-location" class="form-control" placeholder="e.g. Ward 4 High School Relief Camp, Monywa" required>
            </div>

            <!-- Additional Note -->
            <div class="form-group">
                <label for="note">Additional Details / Note</label>
                <textarea name="note" id="note" class="form-control" rows="3" placeholder="Specify urgent needs, estimated affected persons, category etc."></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-danger btn-full">
                <i class="fa-solid fa-paper-plane"></i> Submit Aid Request
            </button>
        </form>
    </div>
</div>
@endsection
