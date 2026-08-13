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

        // Already approved
        if ($reliefRequest->status === 'Approved') {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'ဤတောင်းခံမှုကို အတည်ပြုပြီးသားဖြစ်ပါသည်။'
                );
        }

        // Already rejected
        if ($reliefRequest->status === 'Rejected') {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'ပယ်ဖျက်ပြီးသား တောင်းခံမှုကို ပြန်လည်အတည်ပြု၍ မရပါ။'
                );
        }

        // Only Pending request can be approved
        if ($reliefRequest->status !== 'Pending') {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'လက်ရှိအခြေအနေမှ အတည်ပြု၍ မရပါ။'
                );
        }

        // Update request status
        $reliefRequest->update([
            'status' => 'Approved',
        ]);

        return redirect()
            ->route(
                'backend.relief_requests.show',
                $reliefRequest->id
            )
            ->with(
                'success',
                'ကယ်ဆယ်ရေးအကူအညီ တောင်းခံမှုကို အတည်ပြုပြီးပါပြီ။'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject Relief Request
    |--------------------------------------------------------------------------
    */

    public function reject($id)
    {
        $reliefRequest = ReliefRequest::findOrFail($id);

        // Already rejected
        if ($reliefRequest->status === 'Rejected') {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'ဤတောင်းခံမှုကို ပယ်ဖျက်ပြီးသားဖြစ်ပါသည်။'
                );
        }

        // Cannot reject completed request
        if ($reliefRequest->status === 'Completed') {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'ပြီးစီးပြီးသား တောင်းခံမှုကို ပယ်ဖျက်၍ မရပါ။'
                );
        }

        // Update status
        $reliefRequest->update([
            'status' => 'Rejected',
        ]);

        return redirect()
            ->route(
                'backend.relief_requests.show',
                $reliefRequest->id
            )
            ->with(
                'success',
                'ကယ်ဆယ်ရေးအကူအညီ တောင်းခံမှုကို ပယ်ဖျက်ပြီးပါပြီ။'
            );
    }
}
