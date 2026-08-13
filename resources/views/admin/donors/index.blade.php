@extends('layouts.admin')

@section('title')
    အလှူရှင်များ
@endsection

@section('button')
<a href="{{ route('backend.donors.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> အလှူရှင်အသစ် ထည့်ရန်
</a>
@endsection

@section('content')

<div class="card mb-4">

    {{-- Page Header --}}
    <div class="card-header">
        <h4 class="mb-0">
            <i class="fas fa-hand-holding-heart me-2"></i>
            အလှူရှင် စီမံခန့်ခွဲမှု
        </h4>
    </div>

    <div class="card-body">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif


        {{-- Search Form --}}
        <form method="GET"
              action="{{ route('backend.donors.index') }}"
              class="row g-3 mb-4">

            <div class="col-md-5">

                <label class="form-label fw-semibold">
                    အလှူရှင် ရှာဖွေရန်
                </label>

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="အမည်၊ ဖုန်းနံပါတ်၊ Email သို့မဟုတ် လိပ်စာဖြင့် ရှာပါ..."
                    value="{{ request('search') }}"
                >

            </div>


            <div class="col-md-2 d-flex align-items-end">

                <button type="submit"
                        class="btn btn-secondary w-100">

                    <i class="fas fa-search me-1"></i>
                    ရှာဖွေရန်

                </button>

            </div>


            @if(request('search'))

                <div class="col-md-2 d-flex align-items-end">

                    <a href="{{ route('backend.donors.index') }}"
                       class="btn btn-outline-danger w-100">

                        <i class="fas fa-times me-1"></i>
                        ရှင်းလင်းရန်

                    </a>

                </div>

            @endif

        </form>


        {{-- Donors Table --}}
        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>
                            အလှူရှင်အမည်
                        </th>

                        <th>
                            ဖုန်းနံပါတ်
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            လိပ်စာ
                        </th>

                        <th>
                            စုစုပေါင်း လှူဒါန်းမှု
                        </th>

                        <th>
                            လုပ်ဆောင်ချက်
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($donors as $donor)

                        <tr>

                            {{-- Number --}}
                            <td>
                                {{ $loop->iteration + ($donors->currentPage() - 1) * $donors->perPage() }}
                            </td>


                            {{-- Name --}}
                            <td>

                                <strong>
                                    {{ $donor->name }}
                                </strong>

                            </td>


                            {{-- Phone --}}
                            <td>

                                {{ $donor->phone ?? '-' }}

                            </td>


                            {{-- Email --}}
                            <td>

                                {{ $donor->email ?? '-' }}

                            </td>


                            {{-- Address --}}
                            <td>

                                {{ $donor->address ?? '-' }}

                            </td>


                            {{-- Total Donations --}}
                            <td>

                                <span class="badge bg-info">

                                    <i class="fas fa-gift me-1"></i>

                                    {{ $donor->donations_count ?? 0 }}

                                    ကြိမ်

                                </span>

                            </td>


                            {{-- Actions --}}
                            <td>

                                {{-- View --}}
                                <a href="{{ route('backend.donors.show', $donor->id) }}"
                                   class="btn btn-sm btn-info">

                                    <i class="fas fa-eye me-1"></i>
                                    ကြည့်ရန်

                                </a>


                                {{-- Edit --}}
                                <a href="{{ route('backend.donors.edit', $donor->id) }}"
                                   class="btn btn-sm btn-warning">

                                    <i class="fas fa-edit me-1"></i>
                                    ပြင်ဆင်ရန်

                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route('backend.donors.destroy', $donor->id) }}"
                                    method="POST"
                                    style="display:inline;"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm(
                                            'ဤအလှူရှင်၏ အချက်အလက်ကို ဖျက်ရန် သေချာပါသလား?'
                                        )"
                                    >

                                        <i class="fas fa-trash me-1"></i>
                                        ဖျက်ရန်

                                    </button>

                                </form>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-4 text-muted">

                                <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>

                                အလှူရှင်စာရင်း မတွေ့ရှိပါ။

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="mt-3">

            {{ $donors->links() }}

        </div>

    </div>

</div>

@endsection
