<?php

namespace App\Http\Controllers;

use App\Models\Organiser;
use Illuminate\Http\Request;

class OrganiserController extends Controller
{
    /* ── List ───────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $query = Organiser::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('oname',     'like', "%{$s}%")
                  ->orWhere('tmp_oid', 'like', "%{$s}%")
                  ->orWhere('mobile_1','like', "%{$s}%")
                  ->orWhere('email',   'like', "%{$s}%")
                  ->orWhere('aadhar_no','like',"%{$s}%");
            });
        }

        $organisers = $query->orderBy('oid', 'desc')->paginate(20)->withQueryString();
        $total      = Organiser::count();

        return view('master.organiser.index', compact('organisers', 'total'));
    }

    /* ── Store ──────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate([
            'oname'    => 'required|string|max:50',
            'mobile_1' => 'required|string|max:20',
        ]);

        $data = $request->except(['_token', '_method']);
        $data['cr_date'] = now()->toDateString();
        $data['cr_by']   = auth()->id() ?? 0;

        Organiser::create($data);

        return redirect()->route('master.organiser.index')
                         ->with('success', 'Organiser added successfully.');
    }

    /* ── Update ─────────────────────────────────────────────── */
    public function update(Request $request, $id)
    {
        $request->validate([
            'oname'    => 'required|string|max:50',
            'mobile_1' => 'required|string|max:20',
        ]);

        $organiser = Organiser::findOrFail($id);
        $organiser->update($request->except(['_token', '_method']));

        return redirect()->route('master.organiser.index')
                         ->with('success', 'Organiser updated successfully.');
    }

    /* ── Destroy ────────────────────────────────────────────── */
    public function destroy($id)
    {
        Organiser::findOrFail($id)->delete();

        return redirect()->route('master.organiser.index')
                         ->with('success', 'Organiser deleted.');
    }
}
