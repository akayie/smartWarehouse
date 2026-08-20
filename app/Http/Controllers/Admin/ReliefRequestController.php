<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReliefRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReliefRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Relief Request List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ReliefRequest::with([
            'disaster',
            'warehouse',
            'requestedBy',
        ]);

        // Warehouse Manager / Manager / Inventory Staff
        // မိမိ assign ဖြစ်ထားသော warehouse ကိုသာ ကြည့်နိုင်မည်
        $this->applyWarehouseFilter($query, $user);

        $reliefRequests = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

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
        $user = Auth::user();

        $query = ReliefRequest::with([
            'disaster',
            'warehouse',
            'requestedBy',
            'requestItems.item',
        ]);

        // Warehouse permission
        $this->applyWarehouseFilter($query, $user);

        $reliefRequest = $query->findOrFail($id);

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
        $user = Auth::user();

        $query = ReliefRequest::query();

        // Warehouse permission
        $this->applyWarehouseFilter($query, $user);

        $reliefRequest = $query->findOrFail($id);

        $status = strtolower(
            trim($reliefRequest->status ?? '')
        );

        /*
        |--------------------------------------------------------------------------
        | Already Approved
        |--------------------------------------------------------------------------
        */

        if ($status === 'approved') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'ဤတောင်းခံမှုကို အတည်ပြုပြီးသားဖြစ်ပါသည်။'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Already Rejected
        |--------------------------------------------------------------------------
        */

        if ($status === 'rejected') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'ပယ်ဖျက်ပြီးသား တောင်းခံမှုကို ပြန်လည်အတည်ပြု၍ မရပါ။'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Only Pending Can Be Approved
        |--------------------------------------------------------------------------
        */

        if ($status !== 'pending') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'လက်ရှိအခြေအနေမှ အတည်ပြု၍ မရပါ။'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Status
        |--------------------------------------------------------------------------
        */

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
        $user = Auth::user();

        $query = ReliefRequest::query();

        // Warehouse permission
        $this->applyWarehouseFilter($query, $user);

        $reliefRequest = $query->findOrFail($id);

        $status = strtolower(
            trim($reliefRequest->status ?? '')
        );

        /*
        |--------------------------------------------------------------------------
        | Already Rejected
        |--------------------------------------------------------------------------
        */

        if ($status === 'rejected') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'ဤတောင်းခံမှုကို ပယ်ဖျက်ပြီးသားဖြစ်ပါသည်။'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Completed Cannot Be Rejected
        |--------------------------------------------------------------------------
        */

        if ($status === 'completed') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'ပြီးစီးပြီးသား တောင်းခံမှုကို ပယ်ဖျက်၍ မရပါ။'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Approved Cannot Be Rejected
        |--------------------------------------------------------------------------
        |
        | Approved ဖြစ်ပြီးသား request ကို Reject မလုပ်နိုင်အောင်
        | ဒီနေရာမှာ တားထားပါတယ်။
        |
        */

        if ($status === 'approved') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'အတည်ပြုပြီးသား တောင်းခံမှုကို ပယ်ဖျက်၍ မရပါ။'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Only Pending Can Be Rejected
        |--------------------------------------------------------------------------
        */

        if ($status !== 'pending') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'လက်ရှိအခြေအနေမှ ပယ်ဖျက်၍ မရပါ။'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Status
        |--------------------------------------------------------------------------
        */

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


    /*
    |--------------------------------------------------------------------------
    | Warehouse Filter
    |--------------------------------------------------------------------------
    |
    | Admin က warehouse အားလုံးကို ကြည့်နိုင်သည်။
    |
    | warehouse_manager / manager / inventory_staff
    | တို့က မိမိ warehouse ကိုသာ ကြည့်နိုင်သည်။
    |
    */

    private function applyWarehouseFilter($query, $user)
    {
        if (
            in_array($user->role, [
                'warehouse_manager',
                'manager',
                'inventory_staff',
            ])
            && $user->warehouse_id
        ) {
            $query->where(
                'warehouse_id',
                $user->warehouse_id
            );
        }

        return $query;
    }
}
