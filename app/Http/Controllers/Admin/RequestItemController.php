<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestItemRequest;
use App\Models\Item;
use App\Models\RequestItem;
use App\Models\ReliefRequest;

class RequestItemController extends Controller
{
    /**
     * Display request items.
     */
    public function index()
    {
        $requestItems = RequestItem::with([
            'request',
            'request.disaster',
            'item',
        ])
        ->orderBy('id', 'DESC')
        ->paginate(15);

        return view(
            'admin.request_items.index',
            compact('requestItems')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $requests = ReliefRequest::with('disaster')
            ->orderBy('id', 'DESC')
            ->get();

        $items = Item::where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view(
            'admin.request_items.create',
            compact(
                'requests',
                'items'
            )
        );
    }

    /**
     * Store request item.
     */
    public function store(
        RequestItemRequest $request
    ) {
        RequestItem::create([
            'request_id' =>
                $request->request_id,

            'item_id' =>
                $request->item_id,

            'quantity' =>
                $request->quantity,
        ]);

        return redirect()
            ->route('backend.request_items.index')
            ->with(
                'success',
                'Request item created successfully.'
            );
    }

    /**
     * Display request item details.
     */
    public function show(
        RequestItem $requestItem
    ) {
        $requestItem->load([
            'request',
            'request.disaster',
            'request.requestedBy',
            'item',
        ]);

        return view(
            'admin.request_items.show',
            compact('requestItem')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        RequestItem $requestItem
    ) {
        $requests = ReliefRequest::with('disaster')
            ->orderBy('id', 'DESC')
            ->get();

        $items = Item::orderBy('name')
            ->get();

        return view(
            'admin.request_items.edit',
            compact(
                'requestItem',
                'requests',
                'items'
            )
        );
    }

    /**
     * Update request item.
     */
    public function update(
        RequestItemRequest $request,
        RequestItem $requestItem
    ) {
        $requestItem->update([
            'request_id' =>
                $request->request_id,

            'item_id' =>
                $request->item_id,

            'quantity' =>
                $request->quantity,
        ]);

        return redirect()
            ->route('backend.request_items.index')
            ->with(
                'success',
                'Request item updated successfully.'
            );
    }

    /**
     * Delete request item.
     */
    public function destroy(
        RequestItem $requestItem
    ) {
        $requestItem->delete();

        return redirect()
            ->route('backend.request_items.index')
            ->with(
                'success',
                'Request item deleted successfully.'
            );
    }
}
