<?php

namespace App\Http\Controllers;

use App\Models\Formar;
use App\Models\FarmersLand;
use App\Models\State;
use App\Models\District;
use App\Models\Block;
use App\Models\City;

use App\Models\CoreState;
use App\Models\CoreDistrict;
use App\Models\Organiser;

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
        $organisers = Organiser::orderBy('oname')->get(['oid', 'tmp_oid', 'oname']);

        return view('master.farmers.index', compact(
            'formars', 'total',
            'corestates', 'coredistricts', 
            'oldStates', 'oldDistricts', 'oldTahsils', 'oldVillages',
            'tahsils', 'villages', 'organisers'
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

    /* ══════════════════════════════════════════════════════════
     *  LAND DETAILS
     * ══════════════════════════════════════════════════════════ */

    /* ── Land list (JSON) — called via AJAX when modal opens ── */
    public function landIndex($fid)
    {
        $lands = FarmersLand::where('fid', $fid)
                    ->orderBy('flandid', 'desc')
                    ->get()
                    ->map(function ($l) {
                        // Resolve human-readable names from old tables
                        $state    = \DB::table('state')->where('StateId',    $l->StateId)->value('StateName');
                        $district = \DB::table('distric')->where('DictrictId', $l->DictrictId)->value('DictrictName');
                        $tahsil   = \DB::table('tahsil')->where('TahsilId',   $l->TahsilId)->value('TahsilName');
                        $village  = \DB::table('village')->where('VillageId', $l->VillageId)->value('VillageName');

                        return [
                            'flandid'     => $l->flandid,
                            'plot_no'     => $l->plot_no,
                            'khasra_no'   => $l->khasra_no,
                            'land_area'   => $l->land_area,
                            'state'       => $state    ?? $l->StateId,
                            'district'    => $district ?? $l->DictrictId,
                            'tahsil'      => $tahsil   ?? $l->TahsilId,
                            'village'     => $village  ?? $l->VillageId,
                            'StateId'     => $l->StateId,
                            'DictrictId'  => $l->DictrictId,
                            'TahsilId'    => $l->TahsilId,
                            'VillageId'   => $l->VillageId,
                        ];
                    });

        return response()->json($lands);
    }

    /* ── Land store ─────────────────────────────────────────── */
    public function landStore(Request $request, $fid)
    {
        $request->validate([
            'StateId'    => 'required',
            'DictrictId' => 'required',
            'TahsilId'   => 'required',
            'VillageId'  => 'required',
            'land_area'  => 'required|numeric|min:0.001',
            'khasra_no'  => 'required|string|max:30',
        ]);

        // Ensure farmer exists
        Formar::findOrFail($fid);

        FarmersLand::create([
            'fid'        => $fid,
            'StateId'    => $request->StateId,
            'DictrictId' => $request->DictrictId,
            'TahsilId'   => $request->TahsilId,
            'VillageId'  => $request->VillageId,
            'land_area'  => $request->land_area,
            'khasra_no'  => $request->khasra_no,
            'plot_no'    => $request->plot_no ?? '',
            'latitude'   => $request->latitude  ?? '',
            'longitude'  => $request->longitude ?? '',
            'cr_by'      => auth()->id() ?? 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Land entry added.']);
    }

    /* ── Land destroy ───────────────────────────────────────── */
    public function landDestroy($flandid)
    {
        FarmersLand::findOrFail($flandid)->delete();

        return response()->json(['success' => true, 'message' => 'Land entry deleted.']);
    }
}
