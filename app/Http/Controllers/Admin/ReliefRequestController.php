<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReliefRequest;
use Illuminate\Http\Request;

class ReliefRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Relief Request List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $reliefRequests = ReliefRequest::with([
            'disaster',
            'warehouse',
            'requestedBy',
        ])
            ->latest('id')
            ->paginate(15);

        return view(
            'admin.relief_requests.index',
            compact('reliefRequests')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Show Relief Request
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $reliefRequest = ReliefRequest::with([
            'disaster',
            'warehouse',
            'requestedBy',
            'requestItems.item',
        ])->findOrFail($id);

        return view(
            'admin.relief_requests.show',
            compact('reliefRequest')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve Relief Request
    |--------------------------------------------------------------------------
    */

    public function approve($id)
    {
        $reliefRequest = ReliefRequest::findOrFail($id);

        if (strtolower($reliefRequest->status) === 'approved') {
            return redirect()
                ->back()
                ->with('error', 'ဤတောင်းခံမှုကို အတည်ပြုပြီးသားဖြစ်ပါသည်။');
        }

        if (strtolower($reliefRequest->status) === 'rejected') {
            return redirect()
                ->back()
                ->with('error', 'ပယ်ဖျက်ပြီးသား တောင်းခံမှုကို ပြန်လည်အတည်ပြု၍ မရပါ။');
        }

        if (strtolower($reliefRequest->status) !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'လက်ရှိအခြေအနေမှ အတည်ပြု၍ မရပါ။');
        }

        $reliefRequest->update([
            'status' => 'Approved',
        ]);

        return redirect()
            ->route(
                'backend.relief_requests.show',
                $reliefRequest->id
            )
            ->with('success', 'ကယ်ဆယ်ရေးအကူအညီ တောင်းခံမှုကို အတည်ပြုပြီးပါပြီ။');
    }


    /*
    |--------------------------------------------------------------------------
    | Reject Relief Request
    |--------------------------------------------------------------------------
    */

    public function reject($id)
    {
        $reliefRequest = ReliefRequest::findOrFail($id);

        if (strtolower($reliefRequest->status) === 'rejected') {
            return redirect()
                ->back()
                ->with('error', 'ဤတောင်းခံမှုကို ပယ်ဖျက်ပြီးသားဖြစ်ပါသည်။');
        }

        if (strtolower($reliefRequest->status) === 'completed') {
            return redirect()
                ->back()
                ->with('error', 'ပြီးစီးပြီးသား တောင်းခံမှုကို ပယ်ဖျက်၍ မရပါ။');
        }

        $reliefRequest->update([
            'status' => 'Rejected',
        ]);

        return redirect()
            ->route(
                'backend.relief_requests.show',
                $reliefRequest->id
            )
            ->with('success', 'ကယ်ဆယ်ရေးအကူအညီ တောင်းခံမှုကို ပယ်ဖျက်ပြီးပါပြီ။');
    }
}
