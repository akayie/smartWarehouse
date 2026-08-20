<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of donors with search filter.
     */
   public function index(Request $request)
    {
        // 1. Query စတင်တည်ဆောက်ပါ
        $query = Donor::query();

        // 2. Search Filter (Name, Phone, Email, Address)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // 3. Donations အရေအတွက်ကိုသာ ယူမည် (amount ကော်လံမရှိတော့သဖြင့် ဖြုတ်ထားသည်)
        $query->withCount('donations');

        // 4. Pagination ပြုလုပ်ပြီး View သို့ ပို့ပေးပါ
        $donors = $query->latest('id')
            ->paginate(15)
            ->appends($request->all());

        return view('admin.donors.index', compact('donors'));
    }
}
