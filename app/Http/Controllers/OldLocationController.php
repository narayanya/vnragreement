<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OldLocationController extends Controller
{
    public function index(Request $request)
    {
        // ── State ────────────────────────────────────────────
        $stateQuery = DB::table('state');
        if ($request->filled('state_search')) {
            $stateQuery->where('StateName', 'like', '%' . $request->state_search . '%');
        }
        $states = $stateQuery->orderBy('StateName')->paginate(15, ['*'], 'state_page')
                             ->withQueryString();

        // ── District ─────────────────────────────────────────
        $districtQuery = DB::table('distric');
        if ($request->filled('district_search')) {
            $districtQuery->where('DictrictName', 'like', '%' . $request->district_search . '%');
        }
        if ($request->filled('filter_state')) {
            $districtQuery->where('StateId', $request->filter_state);
        }
        $districts = $districtQuery->orderBy('DictrictName')
                                   ->paginate(15, ['*'], 'district_page')
                                   ->withQueryString();

        // ── Tahsil ────────────────────────────────────────────
        $tahsilQuery = DB::table('tahsil');
        if ($request->filled('tahsil_search')) {
            $tahsilQuery->where('TahsilName', 'like', '%' . $request->tahsil_search . '%');
        }
        if ($request->filled('filter_district')) {
            $tahsilQuery->where('DistrictId', $request->filter_district);
        }
        $tahsils = $tahsilQuery->orderBy('TahsilName')
                               ->paginate(15, ['*'], 'tahsil_page')
                               ->withQueryString();

        // ── Village ───────────────────────────────────────────
        $villageQuery = DB::table('village');
        if ($request->filled('village_search')) {
            $villageQuery->where('VillageName', 'like', '%' . $request->village_search . '%');
        }
        if ($request->filled('filter_tahsil')) {
            $villageQuery->where('TahsilId', $request->filter_tahsil);
        }
        $villages = $villageQuery->orderBy('VillageName')
                                 ->paginate(15, ['*'], 'village_page')
                                 ->withQueryString();

        // Dropdown options for filters
        $allStates    = DB::table('state')->orderBy('StateName')->get(['StateId', 'StateName']);
        $allDistricts = DB::table('distric')->orderBy('DictrictName')->get(['DictrictId', 'DictrictName', 'StateId']);
        $allTahsils   = DB::table('tahsil')->orderBy('TahsilName')->get(['TahsilId', 'TahsilName', 'DistrictId']);

        return view('master.location.index', compact(
            'states', 'districts', 'tahsils', 'villages',
            'allStates', 'allDistricts', 'allTahsils'
        ));
    }
}
