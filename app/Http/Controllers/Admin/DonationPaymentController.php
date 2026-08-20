<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DonationPaymentController extends Controller
{
    /**
     * Display a listing of donation payments.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = DonationPayment::with([
            'donation.donor',
            'donation.warehouse',
            'donation.donationItems.item',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Warehouse Filter
        |--------------------------------------------------------------------------
        */
        if (
            in_array($user->role, ['warehouse_manager', 'manager'])
            && !empty($user->warehouse_id)
        ) {
            $query->whereHas('donation', function ($q) use ($user) {
                $q->where('warehouse_id', $user->warehouse_id);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                // Payment transaction reference
                $q->where(
                    'transaction_reference',
                    'like',
                    "%{$search}%"
                )

                // Account name
                ->orWhere(
                    'account_name',
                    'like',
                    "%{$search}%"
                )

                // Donor name
                ->orWhereHas(
                    'donation.donor',
                    function ($donorQuery) use ($search) {
                        $donorQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Method
        |--------------------------------------------------------------------------
        */
        if ($request->filled('payment_method')) {
            $query->where(
                'payment_method',
                $request->payment_method
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */
        if ($request->filled('from_date')) {
            $query->whereDate(
                'payment_date',
                '>=',
                $request->from_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */
        if ($request->filled('to_date')) {
            $query->whereDate(
                'payment_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Total Amount
        |--------------------------------------------------------------------------
        */
        $totalAmount = (clone $query)
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Completed Amount
        |--------------------------------------------------------------------------
        */
        $totalCompletedAmount = (clone $query)
            ->where('status', 'Completed')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        $donationPayments = $query
            ->latest('id')
            ->paginate(15)
            ->appends($request->query());

        return view(
            'admin.donation_payments.index',
            compact(
                'donationPayments',
                'totalAmount',
                'totalCompletedAmount'
            )
        );
    }


    /**
     * Display the specified donation payment.
     */
    public function show($id)
    {
        $donationPayment = DonationPayment::with([
            'donation.donor',
            'donation.warehouse',
            'donation.donationItems.item',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Donation
        |--------------------------------------------------------------------------
        */
        $donation = $donationPayment->donation;

        /*
        |--------------------------------------------------------------------------
        | Donor
        |--------------------------------------------------------------------------
        | Donor name / phone / email comes from donors table.
        */
        $donor = $donation?->donor;

        /*
        |--------------------------------------------------------------------------
        | Donated Items
        |--------------------------------------------------------------------------
        */
        $donatedItems = $donation?->donationItems ?? collect();

        /*
        |--------------------------------------------------------------------------
        | Warehouse
        |--------------------------------------------------------------------------
        */
        $warehouse = $donation?->warehouse;

        return view(
            'admin.donation_payments.show',
            compact(
                'donationPayment',
                'donation',
                'donor',
                'donatedItems',
                'warehouse'
            )
        );
    }


    /**
     * Generate Donation Payment PDF.
     */
    public function pdf($id)
    {
        $donationPayment = DonationPayment::with([
            'donation.donor',
            'donation.warehouse',
            'donation.donationItems.item',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Related Data
        |--------------------------------------------------------------------------
        */
        $donation = $donationPayment->donation;

        $donor = $donation?->donor;

        $donatedItems = $donation?->donationItems ?? collect();

        $warehouse = $donation?->warehouse;

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */
        $pdf = Pdf::loadView(
            'admin.donation_payments.pdf',
            compact(
                'donationPayment',
                'donation',
                'donor',
                'donatedItems',
                'warehouse'
            )
        );

        return $pdf->stream(
            'donation-payment-' . $donationPayment->id . '.pdf'
        );
    }
}

