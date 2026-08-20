<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\User;
use App\Http\Requests\WarehouseRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    /**
     * Display a listing of warehouses.
     */
    public function index()
    {
        $warehouses = Warehouse::with('user')
            ->orderBy('id', 'DESC')
            ->paginate(15);

        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new warehouse.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('admin.warehouses.create', compact('users'));
    }

    /**
     * Store a newly created warehouse.
     */
    public function store(WarehouseRequest $request)
    {
        DB::transaction(function () use ($request) {
            // ၁။ Warehouse အသစ် ဖန်တီးပါ
            $warehouse = Warehouse::create($request->validated());

            // ၂။ ရွေးချယ်ထားသော မန်နေဂျာ၏ warehouse_id ကို Update လုပ်ပါ
            if ($request->filled('manager_id')) {
                User::where('id', $request->manager_id)->update([
                    'warehouse_id' => $warehouse->id,
                ]);
            }
        });

        return redirect()
            ->route('backend.warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the specified warehouse.
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse->load('user');

        return view('admin.warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified warehouse.
     */
    public function edit(Warehouse $warehouse)
    {
        $users = User::orderBy('name')->get();

        return view('admin.warehouses.edit', compact('warehouse', 'users'));
    }

    /**
     * Update the specified warehouse.
     */
    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        DB::transaction(function () use ($request, $warehouse) {
            // ၁။ Warehouse Data ကို Update လုပ်ပါ
            $warehouse->update($request->validated());

            // ၂။ ယခင် တာဝန်ယူထားသော မန်နေဂျာ၏ warehouse_id ကို null ပြန်လုပ်ပါ
            User::where('warehouse_id', $warehouse->id)->update([
                'warehouse_id' => null,
            ]);

            // ၃။ မန်နေဂျာ အသစ် ရွေးထားပါက ၎င်း၏ warehouse_id ကို Update လုပ်ပါ
            if ($request->filled('manager_id')) {
                User::where('id', $request->manager_id)->update([
                    'warehouse_id' => $warehouse->id,
                ]);
            }
        });

        return redirect()
            ->route('backend.warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Remove the specified warehouse.
     */
    public function destroy(Warehouse $warehouse)
    {
        DB::transaction(function () use ($warehouse) {
            // Warehouse မဖျက်မီ သက်ဆိုင်ရာ User များ၏ warehouse_id ကို null ပြန်လုပ်ပါ
            User::where('warehouse_id', $warehouse->id)->update([
                'warehouse_id' => null,
            ]);

            $warehouse->delete();
        });

        return redirect()
            ->route('backend.warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }

    /**
     * Display QR / Barcode Scanner view based on logged-in user role.
     */
    public function scan()
    {
        $user = Auth::user();

        // Admin (သို့မဟုတ် အခြားစီမံခန့်ခွဲသူ role များ) ဟုတ်မဟုတ် စစ်ဆေးခြင်း
        if ($user && in_array($user->role, ['admin', 'super_admin'])) {
            $warehouses = Warehouse::orderBy('name')->get();
        } else {
            // Warehouse Manager ဖြစ်ပါက ၎င်းနှင့် သက်ဆိုင်သော Warehouse ကိုသာ ပြမည်
            $warehouses = Warehouse::where('id', $user->warehouse_id)->get();

            // အကယ်၍ warehouse_id မရှိသေးပါက Warehouse အားလုံးကို ပြသပေးမည်
            if ($warehouses->isEmpty()) {
                $warehouses = Warehouse::orderBy('name')->get();
            }
        }

        return view('admin.scan', compact('warehouses'));
    }
}
