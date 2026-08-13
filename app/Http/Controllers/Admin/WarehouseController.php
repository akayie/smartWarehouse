<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\User;
use App\Http\Requests\WarehouseRequest;

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

        return view(
            'admin.warehouses.index',
            compact('warehouses')
        );
    }


    /**
     * Show the form for creating a new warehouse.
     */
    public function create()
    {
        $users = User::orderBy('name')
            ->get();

        return view(
            'admin.warehouses.create',
            compact('users')
        );
    }


    /**
     * Store a newly created warehouse.
     */
    public function store(WarehouseRequest $request)
    {
        Warehouse::create(
            $request->validated()
        );

        return redirect()
            ->route('backend.warehouses.index')
            ->with(
                'success',
                'Warehouse created successfully.'
            );
    }


    /**
     * Display the specified warehouse.
     */
    public function show(string $id)
    {
        $warehouse = Warehouse::with('user')
            ->findOrFail($id);

        return view(
            'admin.warehouses.show',
            compact('warehouse')
        );
    }


    /**
     * Show the form for editing the specified warehouse.
     */
    public function edit(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $users = User::orderBy('name')
            ->get();

        return view(
            'admin.warehouses.edit',
            compact(
                'warehouse',
                'users'
            )
        );
    }


    /**
     * Update the specified warehouse.
     */
    public function update(
        WarehouseRequest $request,
        string $id
    ) {
        $warehouse = Warehouse::findOrFail($id);

        $warehouse->update(
            $request->validated()
        );

        return redirect()
            ->route('backend.warehouses.index')
            ->with(
                'success',
                'Warehouse updated successfully.'
            );
    }


    /**
     * Remove the specified warehouse.
     */
    public function destroy(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $warehouse->delete();

        return redirect()
            ->route('backend.warehouses.index')
            ->with(
                'success',
                'Warehouse deleted successfully.'
            );
    }
}
