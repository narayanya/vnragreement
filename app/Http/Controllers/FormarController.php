<?php

namespace App\Http\Controllers;

use App\Models\Formar;
use Illuminate\Http\Request;

class FormarController extends Controller
{
    /* ── List ───────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $query = Formar::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('fname',        'like', "%{$s}%")
                  ->orWhere('tem_fid',    'like', "%{$s}%")
                  ->orWhere('contact_1',  'like', "%{$s}%")
                  ->orWhere('email',      'like', "%{$s}%")
                  ->orWhere('aadhar_no',  'like', "%{$s}%")
                  ->orWhere('village_id', 'like', "%{$s}%");
            });
        }

        $formars = $query->orderBy('fid', 'desc')->paginate(20)->withQueryString();
        $total   = Formar::count();

        return view('master.farmers.index', compact('formars', 'total'));
    }

    /* ── Store ──────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate([
            'fname'     => 'required|string|max:50',
            'contact_1' => 'required|string|max:10',
        ]);

        $data = $request->except(['_token', '_method']);
        $data['cr_date'] = now()->toDateString();
        $data['cr_by']   = auth()->id() ?? 0;

        Formar::create($data);

        return redirect()->route('master.farmers.index')
                         ->with('success', 'Formar added successfully.');
    }

    /* ── Update ─────────────────────────────────────────────── */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fname'     => 'required|string|max:50',
            'contact_1' => 'required|string|max:10',
        ]);

        $formar = Formar::findOrFail($id);
        $formar->update($request->except(['_token', '_method']));

        return redirect()->route('master.farmers.index')
                         ->with('success', 'Formar updated successfully.');
    }

    /* ── Destroy ────────────────────────────────────────────── */
    public function destroy($id)
    {
        Formar::findOrFail($id)->delete();

        return redirect()->route('master.farmers.index')
                         ->with('success', 'Formar deleted.');
    }
}
