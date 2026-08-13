<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistributionRequest;
use App\Models\Distribution;
use App\Models\DistributionItem;
use App\Models\Inventory;
use App\Models\ReliefRequest;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    /**
     * Display distributions.
     */
    public function index()
    {
        $distributions = Distribution::with([
            'request',
            'request.disaster',
            'warehouse',
            'handledBy',
            'distributionItems.item',
        ])
        ->orderBy('id', 'DESC')
        ->paginate(15);

        return view('admin.distributions.index', compact('distributions'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $requests = ReliefRequest::with([
            'disaster',
            'requestItems.item',
        ])
        ->whereIn('status', ['Approved', 'Processing'])
        ->orderBy('id', 'DESC')
        ->get();

        $warehouses = Warehouse::where('status', 'Active')
            ->orderBy('name')
            ->get();

        $users = User::orderBy('name')->get();

        return view('admin.distributions.create', compact('requests', 'warehouses', 'users'));
    }

    /**
     * Store distribution & Update Inventory / Stock Movements.
     */
    public function store(DistributionRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // ၁။ Distribution Main Header ဖန်တီးခြင်း
                $distribution = Distribution::create([
                    'request_id'        => $request->request_id,
                    'warehouse_id'      => $request->warehouse_id,
                    'handled_by'        => $request->handled_by,
                    'distribution_date' => $request->distribution_date,
                    'status'            => $request->status ?? 'Completed',
                    'note'              => $request->note,
                ]);

                // ၂။ ထုတ်ပေးမည့် Items များကို Loop ပတ်၍ Detail သိမ်းခြင်း + Stock လျှော့ခြင်း
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $itemData) {
                        $itemId     = $itemData['item_id'];
                        $qty        = $itemData['quantity'];
                        $expiryDate = $itemData['expiry_date'] ?? null;

                        // (A) Distribution Item Entry Save လုပ်ခြင်း
                        DistributionItem::create([
                            'distribution_id' => $distribution->id,
                            'item_id'         => $itemId,
                            'quantity'        => $qty,
                        ]);

                        // (B) Warehouse + Item + Expiry Date အလိုက် Inventory ရှာခြင်း
                        $inventoryQuery = Inventory::where('warehouse_id', $request->warehouse_id)
                            ->where('item_id', $itemId);

                        if ($expiryDate) {
                            $inventoryQuery->where('expiry_date', $expiryDate);
                        } else {
                            $inventoryQuery->whereNull('expiry_date');
                        }

                        $inventory = $inventoryQuery->first();

                        // စတော့ လုံလောက်မှု ရှိမရှိ စစ်ဆေးခြင်း
                        if (!$inventory || $inventory->quantity < $qty) {
                            $currentStock = $inventory ? $inventory->quantity : 0;
                            throw new \Exception("Stock မလုံလောက်ပါ။ Current Stock: {$currentStock} for Item ID: {$itemId}");
                        }

                        // (C) Inventory Balance လျှော့ချခြင်း
                        $inventory->decrement('quantity', $qty);

                        // (D) Stock Movement Record (OUT) သိမ်းဆည်းခြင်း
                        StockMovement::create([
                            'item_id'       => $itemId,
                            'warehouse_id'  => $request->warehouse_id,
                            'type'          => 'OUT',
                            'quantity'      => $qty,
                            'balance_after' => $inventory->quantity,
                            'expiry_date'   => $expiryDate,
                            'reference'     => 'DIST-' . $distribution->id,
                            'note'          => 'Distributed via Request #' . ($request->request_id ?? 'Direct'),
                            'created_by'    => $request->handled_by ?? auth()->id() ?? 1,
                        ]);
                    }
                }

                // ၃။ တကယ်လို့ Relief Request နဲ့ ချိတ်ထားပါက Request Status ကို 'Distributed' သို့ ပြောင်းပေးခြင်း
                if ($request->request_id) {
                    ReliefRequest::where('id', $request->request_id)->update(['status' => 'Distributed']);
                }
            });

            return redirect()
                ->route('backend.distributions.index')
                ->with('success', 'Distribution, Inventory & Stock Movement recorded successfully.');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display distribution details.
     */
    public function show(Distribution $distribution)
    {
        $distribution->load([
            'request',
            'request.disaster',
            'request.requestedBy',
            'warehouse',
            'handledBy',
            'distributionItems.item',
        ]);

        return view('admin.distributions.show', compact('distribution'));
    }

    /**
     * Show edit form.
     */
    public function edit(Distribution $distribution)
    {
        $distribution->load('distributionItems');

        $requests = ReliefRequest::with([
            'disaster',
            'requestItems.item',
        ])
        ->orderBy('id', 'DESC')
        ->get();

        $warehouses = Warehouse::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.distributions.edit', compact('distribution', 'requests', 'warehouses', 'users'));
    }

    /**
     * Update distribution.
     */
    public function update(DistributionRequest $request, Distribution $distribution)
    {
        $distribution->update([
            'request_id'        => $request->request_id,
            'warehouse_id'      => $request->warehouse_id,
            'handled_by'        => $request->handled_by,
            'distribution_date' => $request->distribution_date,
            'status'            => $request->status,
            'note'              => $request->note,
        ]);

        return redirect()
            ->route('backend.distributions.index')
            ->with('success', 'Distribution updated successfully.');
    }

    /**
     * Delete distribution.
     */
    public function destroy(Distribution $distribution)
    {
        $distribution->delete();

        return redirect()
            ->route('backend.distributions.index')
            ->with('success', 'Distribution deleted successfully.');
    }
}
