<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisasterRequest;
use App\Models\Disaster;

class DisasterController extends Controller
{
    /**
     * Display a listing of disasters.
     */
   public function index()
{
    $disasters = Disaster::withCount('reliefRequests')
        ->orderBy('id', 'DESC')
        ->paginate(10);

    return view('admin.disasters.index', compact('disasters'));
}
    /**
     * Show the form for creating a new disaster.
     */
    public function create()
    {
        return view('admin.disasters.create');
    }

    /**
     * Store a newly created disaster.
     */
    public function store(DisasterRequest $request)
    {
        Disaster::create($request->validated());

        return redirect()
            ->route('backend.disasters.index')
            ->with('success', 'Disaster created successfully.');
    }

    /**
     * Display the specified disaster.
     */
    public function show(Disaster $disaster)
    {
        return view('admin.disasters.show', compact('disaster'));
    }

    /**
     * Show the form for editing the specified disaster.
     */
    public function edit(Disaster $disaster)
    {
        return view('admin.disasters.edit', compact('disaster'));
    }

    /**
     * Update the specified disaster.
     */
    public function update(DisasterRequest $request, Disaster $disaster)
    {
        $disaster->update($request->validated());

        return redirect()
            ->route('backend.disasters.index')
            ->with('success', 'Disaster updated successfully.');
    }

    /**
     * Remove the specified disaster.
     */
    public function destroy(Disaster $disaster)
    {
        $disaster->delete();

        return redirect()
            ->route('backend.disasters.index')
            ->with('success', 'Disaster deleted successfully.');
    }
}
