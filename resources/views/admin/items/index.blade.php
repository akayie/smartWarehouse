@extends('layouts.admin')

@section('title', 'Item Master List')

@section('content')
<div class="container-fluid py-3">

    {{-- Filter & Search Form --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('backend.items.index') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search item name or barcode...">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">-- All Categories --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="stock_status" class="form-select">
                        <option value="">-- All Stock Status --</option>
                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock Alert</option>
                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Items Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fas fa-boxes me-2"></i>Item Master List
            </h5>
            <a href="{{ route('backend.items.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Create New Item
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Barcode</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Total Stock</th>
                            <th>Min Stock</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td class="ps-3">{{ $items->firstItem() + $index }}</td>
                                <td><code>{{ $item->barcode ?? 'N/A' }}</code></td>
                                <td class="fw-bold text-dark">{{ $item->name }}</td>
                                <td>{{ $item->category->name ?? 'N/A' }}</td>
                                <td>
                                    @if($item->total_stock == 0)
                                        <span class="badge bg-secondary fs-6">0</span>
                                    @elseif($item->is_low_stock)
                                        <span class="badge bg-danger fs-6">{{ $item->total_stock }}</span>
                                    @else
                                        <span class="badge bg-success fs-6">{{ $item->total_stock }}</span>
                                    @endif
                                </td>
                                <td><span class="text-muted">{{ $item->minimum_stock }}</span></td>
                                <td>{{ $item->unit }}</td>
                                <td>
                                    @if($item->total_stock == 0)
                                        <span class="badge bg-outline-danger text-danger border border-danger">Out of Stock</span>
                                    @elseif($item->is_low_stock)
                                        <span class="badge bg-warning text-dark">Low Stock Alert</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('backend.items.edit', $item->id) }}" class="btn btn-outline-primary" title="Edit Item">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('backend.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                    Item စာရင်း မရှိသေးပါ။ "Create New Item" ကို နှိပ်၍ အသစ်ဖန်တီးပေးပါ။
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($items->hasPages())
            <div class="card-footer bg-white">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
