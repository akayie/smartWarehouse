<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\DonationPayment;
use App\Models\Donor;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    /**
     * Show admin list of donations.
     */
    /**
     * Show admin list of donations.
     */
    public function index()
    {
        $donations = Donation::with(['donor', 'warehouse', 'donationItems.item', 'payment'])
            ->latest()
            ->paginate(10);

        // 'donations.index' အစား admin folder ထည့်ပေးပါ
        return view('admin.donations.index', compact('donations'));
    }

    /**
     * Show donation creation form.
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        $categories = Category::all();
        $items      = Item::with('category')->where('status', 'active')->get();

        // 'donations.create' အစား admin folder ထည့်ပေးပါ
        return view('admin.donations.create', compact('warehouses', 'categories', 'items'));
    }

    /**
     * Store new donation from form.
     */
    public function store(Request $request)
    {
        $request->validate([
            'donor_name'    => 'required|string|max:255',
            'phone'         => 'required|string|max:50',
            'email'         => 'nullable|email',
            'warehouse_id'  => 'required|exists:warehouses,id',
            'donation_type' => 'required|in:Cash,Item,Both',
            'donation_date' => 'nullable|date',

            // Payment Validation
            'payment_method'        => 'required_if:donation_type,Cash,Both',
            'amount'                => 'required_if:donation_type,Cash,Both|nullable|numeric|min:1',
            'transaction_reference' => 'nullable|string',
            'proof'                 => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Item Array Validation
            'items'                 => 'required_unless:donation_type,Cash|array',
            'items.*.category_id'   => 'required_unless:donation_type,Cash|nullable|exists:categories,id',
            'items.*.item_id'       => 'nullable|required_without:items.*.new_item_name',
            'items.*.new_item_name' => 'nullable|string|max:255',
            'items.*.quantity'      => 'required_unless:donation_type,Cash|nullable|integer|min:1',
            'items.*.unit'          => 'required_unless:donation_type,Cash',
            'items.*.expired_date'  => 'nullable|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. First or create Donor
                $donor = Donor::firstOrCreate(
                    ['phone' => $request->phone],
                    [
                        'name'  => $request->donor_name,
                        'email' => $request->email,
                    ]
                );

                // 2. Create Donation Record
                $donation = Donation::create([
                    'donor_id'      => $donor->id,
                    'warehouse_id'  => $request->warehouse_id,
                    'donation_type' => $request->donation_type,
                    'donation_date' => $request->donation_date ?? now(),
                    'status'        => 'Pending',
                    'note'          => $request->note,
                ]);

                // 3. Save Cash Payment Record (if Cash or Both)
                if (in_array($request->donation_type, ['Cash', 'Both'])) {
                    $proofPath = null;
                    if ($request->hasFile('proof')) {
                        $proofPath = $request->file('proof')->store('donations/proofs', 'public');
                    }

                    DonationPayment::create([
                        'donation_id'           => $donation->id,
                        'payment_method'        => $request->payment_method,
                        'transaction_reference' => $request->transaction_reference,
                        'payment_date'          => now(),
                        'amount'                => $request->amount,
                        'currency'              => 'MMK',
                        'proof'                 => $proofPath,
                        'status'                => 'Pending',
                        'note'                  => $request->note,
                    ]);
                }

                // 4. Save Items (if Item or Both)
                if (in_array($request->donation_type, ['Item', 'Both']) && $request->has('items')) {
                    $expirableKeywords = ['food', 'water', 'medical', 'hygiene', 'ရိက္ခာ', 'ဆေးဝါး', 'သောက်ရေသန့်'];

                    foreach ($request->items as $itemData) {
                        $itemId     = $itemData['item_id'] ?? null;
                        $categoryId = $itemData['category_id'] ?? null;

                        if (!$itemId && !empty($itemData['new_item_name'])) {
                            $newItem = Item::create([
                                'name'        => $itemData['new_item_name'],
                                'category_id' => $categoryId,
                                'unit'        => $itemData['unit'],
                                'status'      => 'active',
                            ]);
                            $itemId = $newItem->id;
                        }

                        $category     = Category::find($categoryId);
                        $categoryName = $category ? strtolower($category->name) : '';

                        $isExpirable = false;
                        foreach ($expirableKeywords as $keyword) {
                            if (str_contains($categoryName, strtolower($keyword))) {
                                $isExpirable = true;
                                break;
                            }
                        }

                        $expiredDate = $isExpirable ? ($itemData['expired_date'] ?? null) : null;

                        if ($itemId) {
                            DonationItem::create([
                                'donation_id'  => $donation->id,
                                'item_id'      => $itemId,
                                'quantity'     => $itemData['quantity'],
                                'unit'         => $itemData['unit'],
                                'expired_date' => $expiredDate,
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('backend.donations.index')
                ->with('success', 'လှူဒါန်းမှု သတင်းအချက်အလက် ပေးပို့မှု အောင်မြင်ပါသည်။ ကျေးဇူးတင်ရှိပါသည်။');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'အမှားအယွင်းတစ်ခု ရှိနေပါသည်: ' . $e->getMessage());
        }
    }
    /**
     * Mark donation as received and update stock/warehouse if needed.
     */
    /**
     * Mark donation as received and update stock/warehouse if needed.
     */
    public function receive($id)
{
    try {
        DB::transaction(function () use ($id) {
            $donation = Donation::with(['donationItems', 'payment'])->findOrFail($id);

            // 1. Update Donation Status to Received
            $donation->update([
                'status' => 'Received'
            ]);

            // 2. Update Payment Status if exists (ငွေသားပါရှိလျှင် Completed သို့ပြောင်းရန်)
            if ($donation->payment) {
                $donation->payment->update([
                    'status' => 'Completed'
                ]);
            }

            // 3. Update Inventory Stock (ပစ္စည်းများပါရှိလျှင် inventories table ထို့ ပမာဏနှင့် သက်တမ်းကုန်ရက် ထည့်ရန်)
            if (in_array($donation->donation_type, ['Item', 'Both']) && $donation->donationItems->count()) {
                foreach ($donation->donationItems as $donationItem) {

                    // ပစ္စည်းတစ်ခုချင်းစီအတွက် inventories table ထဲတွင် စာရင်းထည့်ခြင်း (သို့) ပမာဏပေါင်းထည့်ခြင်း
                    // အကယ်၍ အတူတူပင်ဖြစ်ပြီး သက်တမ်းကုန်ရက် (expiry_date) တူပါက ပမာဏပေါင်းထည့်ရန် updateOrInsert ကိုသုံးနိုင်ပါသည်
                    \App\Models\Inventory::updateOrInsert(
                        [
                            'warehouse_id' => $donation->warehouse_id,
                            'item_id'      => $donationItem->item_id,
                            'expiry_date'  => $donationItem->expired_date, // သက်တမ်းကုန်ရက်အလိုက် ခွဲခြားသိမ်းဆည်းရန်
                        ],
                        [
                            'quantity'     => DB::raw('COALESCE(quantity, 0) + ' . $donationItem->quantity),
                            'updated_at'   => now(),
                            'created_at'   => now(),
                        ]
                    );
                }
            }
        });

        return redirect()->back()
            ->with('success', 'လှူဒါန်းမှုကို လက်ခံအတည်ပြုပြီး Inventory Stock နှင့် ငွေစာရင်းများကို အောင်မြင်စွာ အပ်ဒိတ်လုပ်ပြီးပါပြီ။');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'အမှားအယွင်း ဖြစ်ပေါ်သွားပါသည်: ' . $e->getMessage());
    }
}
}
