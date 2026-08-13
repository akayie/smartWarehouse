@extends('layouts.front')

@section('title', 'About Us - Smart Disaster Relief Management System')

@section('content')
<div class="about-container my-5">
    <!-- Hero Header -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">About Our Mission</h1>
        <p class="lead text-muted mx-auto" style="max-width: 700px;">
            Connecting donors, response teams, and warehouses to deliver rapid, transparent, and trackable disaster relief aid to communities in need.
        </p>
    </div>

    <!-- Features / Core Values Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="icon-box mb-3 text-primary fs-1">
                        <i class="fa-solid fa-bolt-lightning"></i>
                    </div>
                    <h5 class="card-title fw-bold">Rapid Response</h5>
                    <p class="card-text text-muted">
                        Real-time tracking of requests and stock levels ensures disaster victims receive immediate assistance without delays.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="icon-box mb-3 text-success fs-1">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h5 class="card-title fw-bold">Full Transparency</h5>
                    <p class="card-text text-muted">
                        Every single item, from donation receipt to warehouse dispatch, is logged with QR scan validation and real-time inventory updates.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="icon-box mb-3 text-warning fs-1">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <h5 class="card-title fw-bold">Community Support</h5>
                    <p class="card-text text-muted">
                        Bridging the gap between generous donors and impacted disaster zones through verified, efficient distribution workflows.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="bg-light p-5 rounded-3 shadow-sm mb-5">
        <h2 class="fw-bold text-center mb-4">How Our Relief System Works</h2>
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="p-3">
                    <span class="badge bg-primary rounded-circle p-3 fs-4 mb-3">1</span>
                    <h6 class="fw-bold">Report / Request Aid</h6>
                    <p class="small text-muted">Victims or local teams submit urgent relief requests.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <span class="badge bg-primary rounded-circle p-3 fs-4 mb-3">2</span>
                    <h6 class="fw-bold">Stock Matching</h6>
                    <p class="small text-muted">System matches requests with real-time warehouse inventory.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <span class="badge bg-primary rounded-circle p-3 fs-4 mb-3">3</span>
                    <h6 class="fw-bold">QR Dispatch</h6>
                    <p class="small text-muted">Items are scanned and dispatched via QR code movements.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3">
                    <span class="badge bg-primary rounded-circle p-3 fs-4 mb-3">4</span>
                    <h6 class="fw-bold">Aid Delivered</h6>
                    <p class="small text-muted">Relief supplies reach victims with tracked proof of distribution.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call To Action -->
    <div class="text-center p-5 bg-primary text-white rounded-3 shadow">
        <h3 class="fw-bold mb-3">Want to make an impact?</h3>
        <p class="mb-4">Your support and contributions can save lives in disaster-affected communities.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('public.request.create') }}" class="btn btn-light text-primary fw-bold px-4">
                <i class="fa-solid fa-hand-holding-medical me-1"></i> Request Aid
            </a>
            <a href="{{ route('public.donate.create') }}" class="btn btn-outline-light fw-bold px-4">
                <i class="fa-solid fa-heart me-1"></i> Donate Now
            </a>
        </div>
    </div>
</div>
@endsection
