<?php

namespace App\Http\Controllers;

use App\Models\Formar;
use App\Models\State;
use App\Models\District;
use App\Models\Block;
use App\Models\City;

use App\Models\CoreState;
use App\Models\CoreDistrict;

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

        if ($request->filled('from_date')) {
            $query->whereDate('cr_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('cr_date', '<=', $request->to_date);
        }

        $formars = $query->orderBy('fid', 'desc')->paginate(20)->withQueryString();
        $total   = Formar::count();

        $corestates    = CoreState::where('is_active', 1)->orderBy('state_name')->get();
        $coredistricts = CoreDistrict::where('is_active', 1)->orderBy('district_name')->get();
        //$corevillages    = City::where('is_active', 1)->orderBy('city_village_name')->get();
        //$coreblocks    = Block::where('is_active', 1)->orderBy('block_name')->get();

        $oldStates      = State::orderBy('StateName')->get();
        $oldDistricts = \DB::table('distric')->orderBy('DictrictName')->get();
        $oldTahsils   = \DB::table('tahsil')->orderBy('TahsilName')->get();
        $oldVillages  = \DB::table('village')->orderBy('VillageName')->get();

        // Keep for backward compat (core dropdowns)
        $tahsils  = Block::where('is_active', 1)->orderBy('block_name')->get();
        $villages = City::where('is_active', 1)->orderBy('city_village_name')->get();

        return view('master.farmers.index', compact(
            'formars', 'total',
            'corestates', 'coredistricts', 
            'oldStates', 'oldDistricts', 'oldTahsils', 'oldVillages',
            'tahsils', 'villages'
        ));
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
