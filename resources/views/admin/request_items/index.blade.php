@extends('layouts.admin')

@section('title')
    တောင်းဆိုထားသော ပစ္စည်းများ
@endsection

@section('button')
    <a href="{{ route('backend.request_items.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> ပစ္စည်းတောင်းဆိုမှု အသစ်ထည့်ရန်
    </a>
@endsection

@section('content')

<div class="card shadow border-0 mb-4">

    <div class="card-header bg-white py-3">
        <h4 class="mb-0 fw-bold text-dark">တောင်းဆိုထားသော ပစ္စည်းများ စာရင်း</h4>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>တောင်းဆိုမှု အမှတ်</th>
                        <th>ဘေးအန္တရာယ်</th>
                        <th>ပစ္စည်းအမည်</th>
                        <th>အရေအတွက်</th>
                        <th>တည်နေရာ</th>
                        <th class="text-center">အခြေအနေ</th>
                        <th class="text-center" style="width: 180px;">လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requestItems as $requestItem)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>

                            <td class="fw-bold">
                                #{{ $requestItem->request_id }}
                            </td>

                            <td>
                                {{ $requestItem->request->disaster->name ?? 'မရှိပါ' }}
                            </td>

                            <td>
                                {{ $requestItem->item->name ?? 'မရှိပါ' }}
                            </td>

                            <td>
                                {{ $requestItem->quantity }} {{ $requestItem->item->unit ?? '' }}
                            </td>

                            <td>
                                {{ $requestItem->request->location ?? 'မရှိပါ' }}
                            </td>

                            <td class="text-center">
                                @php
                                    $status = $requestItem->request->status ?? 'မရှိပါ';
                                @endphp

                                @if($status === 'Pending')
                                    <span class="badge bg-warning text-dark">စောင့်ဆိုင်းဆဲ</span>
                                @elseif($status === 'Approved')
                                    <span class="badge bg-primary">ခွင့်ပြုပြီး</span>
                                @elseif($status === 'Rejected')
                                    <span class="badge bg-danger">ငြင်းပယ်ထားသည်</span>
                                @elseif($status === 'Processing')
                                    <span class="badge bg-info text-dark">ဆောင်ရွက်နေဆဲ</span>
                                @elseif($status === 'Completed')
                                    <span class="badge bg-success">ပြီးစီးပါပြီ</span>
                                @else
                                    <span class="badge bg-secondary">{{ $status }}</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('backend.request_items.show', $requestItem->id) }}"
                                   class="btn btn-sm btn-info text-white"
                                   title="ကြည့်မည်">
                                    <i class="fa-solid fa-eye"></i> ကြည့်မည်
                                </a>

                                <a href="{{ route('backend.request_items.edit', $requestItem->id) }}"
                                   class="btn btn-sm btn-warning text-dark"
                                   title="ပြင်မည်">
                                    <i class="fa-solid fa-pen-to-square"></i> ပြင်မည်
                                </a>

                                <form action="{{ route('backend.request_items.destroy', $requestItem->id) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('ဤတောင်းဆိုထားသော ပစ္စည်းကို ဖျက်ရန် သေချာပါသလား?')"
                                            title="ဖျက်မည်">
                                        <i class="fa-solid fa-trash"></i> ဖျက်မည်
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                တောင်းဆိုထားသော ပစ္စည်းများ မရှိသေးပါ။
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $requestItems->links() }}
        </div>

    </div>

</div>

@endsection
