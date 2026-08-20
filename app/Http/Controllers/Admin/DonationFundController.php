<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationPayment;
use App\Models\Distribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationFundController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware([
            'auth',
            'role:admin,warehouse_manager,manager'
        ]);
    }

    /**
     * Donation Fund Index
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | DONATION PAYMENTS
        |--------------------------------------------------------------------------
        */

        $paymentQuery = DonationPayment::with([
            'donation.donor'
        ])
        ->where('status', 'Completed');

        /*
        |--------------------------------------------------------------------------
        | WAREHOUSE ACCESS
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $user->role,
                ['warehouse_manager', 'manager']
            )
        ) {
            $paymentQuery->whereHas(
                'donation',
                function ($query) use ($user) {

                    $query->where(
                        'warehouse_id',
                        $user->warehouse_id
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $paymentQuery->where(function ($query) use ($search) {

                $query
                    ->where(
                        'transaction_id',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas(
                        'donation',
                        function ($q) use ($search) {

                            $q->where(
                                'donation_no',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT RECORDS
        |--------------------------------------------------------------------------
        */

        $payments = $paymentQuery
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | TOTAL DONATION FUND
        |--------------------------------------------------------------------------
        */

        $totalDonationAmount = (clone $paymentQuery)
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | USED FUNDING
        |--------------------------------------------------------------------------
        */

        $distributionQuery = Distribution::query()
            ->whereNotNull('funding_amount')
            ->where('funding_amount', '>', 0);

        if (
            in_array(
                $user->role,
                ['warehouse_manager', 'manager']
            )
        ) {
            $distributionQuery->where(
                'warehouse_id',
                $user->warehouse_id
            );
        }

        $usedFundingAmount = (clone $distributionQuery)
            ->sum('funding_amount');

        /*
        |--------------------------------------------------------------------------
        | REMAINING FUNDING
        |--------------------------------------------------------------------------
        */

        $remainingFundingAmount = max(
            0,
            (float) $totalDonationAmount
            -
            (float) $usedFundingAmount
        );

        /*
        |--------------------------------------------------------------------------
        | DISTRIBUTION FUNDING RECORDS
        |--------------------------------------------------------------------------
        */

        $distributionFundings = $distributionQuery
            ->with([
                'reliefRequest',
                'warehouse',
                'handledBy'
            ])
            ->latest()
            ->paginate(
                15,
                ['*'],
                'distribution_page'
            )
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.donation-funds.index',
            compact(
                'payments',
                'distributionFundings',
                'totalDonationAmount',
                'usedFundingAmount',
                'remainingFundingAmount'
            )
        );
    }
}
