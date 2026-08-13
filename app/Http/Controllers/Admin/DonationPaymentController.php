<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationPayment;
use Illuminate\Http\Request;

class DonationPaymentController extends Controller
{
    /**
     * Display a listing of donation payments.
     */
    public function index(Request $request)
    {
        // 1. Relationship များပါဝင်သော Query ကို စတင်တည်ဆောက်ပါ
        $query = DonationPayment::with(['donation.donor']);

        // 2. Search Filter (Transaction Ref, Account Name, Donor Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%")
                  ->orWhereHas('donation.donor', function ($donorQuery) use ($search) {
                      $donorQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Payment Method Filter (e.g. KBZPay, CBPay, Cash, Bank Transfer)
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // 4. Status Filter (Completed, Pending, Failed, Cancelled)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 5. Date Range Filter
        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        // 6. Pagination ပြုလုပ်ပြီး View ထံ ပို့ပါ
        $donationPayments = $query->latest('id')
            ->paginate(15)
            ->appends($request->all());

        return view('admin.donation_payments.index', compact('donationPayments'));
    }
}
