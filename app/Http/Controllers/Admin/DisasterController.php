<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisasterRequest;
use App\Models\Disaster;
use Illuminate\Http\Request;
class DisasterController extends Controller
{
    /**
     * Display a listing of disasters.
     */
    public function index()
    {
        $disasters = Disaster::withCount('reliefRequests')
            ->latest()
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
   public function store(Request $request)
    {
        // 1. Validation စစ်ဆေးခြင်း
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|string',
            'location'   => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'required|in:Active,Completed,Cancelled',
        ]);

        // 2. Disaster Model ဖြင့် ဒေတာအသစ်ထည့်သွင်းခြင်း (Mass Assignment)
        Disaster::create($validated);

        // 3. အောင်မြင်ကြောင်း မက်ဆေ့ခ်ျနှင့်အတူ စာရင်းပြ မျက်နှာပြင်သို့ ပြန်လည်ညွှန်းဆိုခြင်း
        return redirect()
            ->route('backend.disasters.index')
            ->with('success', 'ဘေးအန္တရာယ်ဖြစ်ရပ် အသစ်ကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
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
            ->with('success', 'ဘေးအန္တရာယ် အချက်အလက် ပြင်ဆင်ပြီးပါပြီ။');
    }

    /**
     * Remove the specified disaster.
     */
    public function destroy(Disaster $disaster)
    {
        $disaster->delete();

        return redirect()
            ->route('backend.disasters.index')
            ->with('success', 'ဘေးအန္တရာယ် စာရင်းအား ဖျက်ကွက်ပြီးပါပြီ။');
    }
}
