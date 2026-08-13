@extends('layouts.admin')

@section('title', 'ကယ်ဆယ်ရေးပစ္စည်း စာရင်း')

@section('content')

<div class="container-fluid py-3">

```
{{-- Filter & Search Form --}}
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('backend.items.index') }}" class="row g-2 align-items-center">

            <div class="col-md-4">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="ပစ္စည်းအမည် သို့မဟုတ် ဘားကုဒ်ဖြင့် ရှာဖွေရန်..."
                >
            </div>

            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">-- အမျိုးအစားအားလုံး --</option>

                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="stock_status" class="form-select">
                    <option value="">-- လက်ကျန်အခြေအနေအားလုံး --</option>

                    <option
                        value="low"
                        {{ request('stock_status') == 'low' ? 'selected' : '' }}
                    >
                        လက်ကျန်နည်းနေသောပစ္စည်း
                    </option>

                    <option
                        value="out"
                        {{ request('stock_status') == 'out' ? 'selected' : '' }}
                    >
                        ပစ္စည်းလက်ကျန်ကုန်နေသည်
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>
                    ရှာဖွေရန်
                </button>
            </div>

        </form>
    </div>
</div>


{{-- Items Table --}}
<div class="card shadow-sm border-0">

    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

        <h5 class="mb-0 fw-bold text-primary">
            <i class="fas fa-boxes me-2"></i>
            ကယ်ဆယ်ရေးပစ္စည်း စာရင်း
        </h5>

        <a
            href="{{ route('backend.items.create') }}"
            class="btn btn-primary btn-sm"
        >
            <i class="fas fa-plus me-1"></i>
            ပစ္စည်းအသစ် ထည့်သွင်းရန်
        </a>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="ps-3">
                            စဉ်
                        </th>

                        <th>
                            ဘားကုဒ်
                        </th>

                        <th>
                            ပစ္စည်းအမည်
                        </th>

                        <th>
                            အမျိုးအစား
                        </th>

                        <th>
                            စုစုပေါင်းလက်ကျန်
                        </th>

                        <th>
                            အနည်းဆုံးလက်ကျန်
                        </th>

                        <th>
                            ယူနစ်
                        </th>

                        <th>
                            အခြေအနေ
                        </th>

                        <th class="text-end pe-3">
                            လုပ်ဆောင်ချက်များ
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($items as $index => $item)

                        <tr>

                            <td class="ps-3">
                                {{ $items->firstItem() + $index }}
                            </td>


                            <td>
                                <code>
                                    {{ $item->barcode ?? 'မရှိပါ' }}
                                </code>
                            </td>


                            <td class="fw-bold text-dark">
                                {{ $item->name }}
                            </td>


                            <td>
                                {{ $item->category->name ?? 'မရှိပါ' }}
                            </td>


                            <td>

                                @if($item->total_stock == 0)

                                    <span class="badge bg-secondary fs-6">
                                        0
                                    </span>

                                @elseif($item->is_low_stock)

                                    <span class="badge bg-danger fs-6">
                                        {{ $item->total_stock }}
                                    </span>

                                @else

                                    <span class="badge bg-success fs-6">
                                        {{ $item->total_stock }}
                                    </span>

                                @endif

                            </td>


                            <td>
                                <span class="text-muted">
                                    {{ $item->minimum_stock }}
                                </span>
                            </td>


                            <td>
                                {{ $item->unit }}
                            </td>


                            <td>

                                @if($item->total_stock == 0)

                                    <span class="badge bg-outline-danger text-danger border border-danger">
                                        လက်ကျန်ကုန်နေသည်
                                    </span>

                                @elseif($item->is_low_stock)

                                    <span class="badge bg-warning text-dark">
                                        လက်ကျန်နည်းနေသည်
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        လက်ကျန်ရှိသည်
                                    </span>

                                @endif

                            </td>


                            <td class="text-end pe-3">

                                <div class="btn-group btn-group-sm">

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('backend.items.edit', $item->id) }}"
                                        class="btn btn-outline-primary"
                                        title="ပစ္စည်းအချက်အလက် ပြင်ဆင်ရန်"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('backend.items.destroy', $item->id) }}"
                                        method="POST"
                                        onsubmit="return confirm(
                                            'ဤပစ္စည်းကို ဖျက်ရန် သေချာပါသလား?'
                                        );"
                                        class="d-inline"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger"
                                            title="ပစ္စည်းဖျက်ရန်"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center py-4 text-muted"
                            >

                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>

                                ကယ်ဆယ်ရေးပစ္စည်း စာရင်း မရှိသေးပါ။
                                <br>

                                <small>
                                    “ပစ္စည်းအသစ် ထည့်သွင်းရန်” ကိုနှိပ်ပြီး
                                    ပစ္စည်းအသစ်တစ်ခု ထည့်သွင်းနိုင်ပါသည်။
                                </small>

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
```

</div>
@endsection
