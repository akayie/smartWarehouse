@extends('layouts.front')

@section('title', 'Smart Relief - Home')

@section('content')
    <div class="hero-section">
        <h1 class="hero-title">Rapid & Transparent Disaster Relief Distribution</h1>
        <p class="hero-subtitle">Connecting donors, warehouses, and disaster relief centers to deliver emergency aid right where it is needed most.</p>
        <div class="hero-cta-group">
            <a href="{{ route('public.request.create') }}" class="btn btn-danger">
                <i class="fa-solid fa-hand-holding-medical"></i> Request Relief Aid
            </a>
            <a href="{{ route('public.donate.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-heart"></i> Donate Resources
            </a>
        </div>
    </div>

    <div class="grid-4">
        <div class="card card-stat">
            <i class="fa-solid fa-boxes-stacked icon-blue"></i>
            <h3>{{ number_format($totalItems ?? 0) }}</h3>
            <p class="stat-label">Items Stocked</p>
        </div>
        <div class="card card-stat">
            <i class="fa-solid fa-warehouse icon-orange"></i>
            <h3>{{ number_format($totalWarehouses ?? 0) }}</h3>
            <p class="stat-label">Active Warehouses</p>
        </div>
        <div class="card card-stat">
            <i class="fa-solid fa-triangle-exclamation icon-red"></i>
            <h3>{{ number_format($activeDisastersCount ?? 0) }}</h3>
            <p class="stat-label">Disaster Zones</p>
        </div>
        <div class="card card-stat">
            <i class="fa-solid fa-circle-check icon-green"></i>
            <h3>{{ number_format($familiesHelped ?? 0) }}</h3>
            <p class="stat-label">Families Helped</p>
        </div>
    </div>
@endsection
