<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReliefRequest;
use Illuminate\Http\Request;

class ReliefRequestController extends Controller
{
    public function index()
    {
        // 'user' အစား 'requestedBy' လို့ ပြောင်းလဲထားပါသည်
        $reliefRequests = ReliefRequest::with(['disaster', 'requestedBy'])
            ->latest('id')
            ->paginate(15);

        return view('admin.relief_requests.index', compact('reliefRequests'));
    }

    public function show($id)
    {
        // 'user' အစား 'requestedBy' လို့ ပြောင်းလဲထားပါသည်
        $reliefRequest = ReliefRequest::with(['disaster', 'requestedBy', 'requestItems.item'])->findOrFail($id);
        return view('admin.relief_requests.show', compact('reliefRequest'));
    }

    public function approve($id)
    {
        $reliefRequest = ReliefRequest::findOrFail($id);

        if ($reliefRequest->status === 'Approved') {
            return redirect()->back()->with('error', 'Request is already approved.');
        }

        $reliefRequest->update([
            'status' => 'Approved'
        ]);

        return redirect()->back()->with('success', 'Relief request approved successfully!');
    }
}
