<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;

class CoreLocationController extends Controller
{
    private const PER_PAGE = 20;

    public function index(Request $request)
    {
        $tab    = $request->get('tab', 'countries');
        $search = trim($request->get('search', ''));
        $status = $request->get('status', '');   // '' | '1' | '0'

        // ── Counts for tab badges ────────────────────────────────
        $counts = [
            'countries' => Country::count(),
            'states'    => State::count(),
            'districts' => District::count(),
            'blocks'    => Block::count(),
            'cities'    => City::count(),
        ];

        // ── Active dataset (only the current tab is paginated) ───
        $countries = $states = $districts = $blocks = $cities = collect();

        switch ($tab) {

            case 'states':
                $q = State::with('country');
                if ($search) $q->where('state_name', 'like', "%{$search}%")
                               ->orWhere('state_code', 'like', "%{$search}%");
                if ($status !== '') $q->where('is_active', $status);
                $states = $q->orderBy('state_name')->paginate(self::PER_PAGE)->withQueryString();
                break;

            case 'districts':
                $q = District::with('state');
                if ($search) $q->where('district_name', 'like', "%{$search}%")
                               ->orWhere('district_code', 'like', "%{$search}%");
                if ($status !== '') $q->where('is_active', $status);
                $districts = $q->orderBy('district_name')->paginate(self::PER_PAGE)->withQueryString();
                break;

            case 'blocks':
                $q = Block::with('district');
                if ($search) $q->where('block_name', 'like', "%{$search}%")
                               ->orWhere('block_code', 'like', "%{$search}%");
                if ($status !== '') $q->where('is_active', $status);
                $blocks = $q->orderBy('block_name')->paginate(self::PER_PAGE)->withQueryString();
                break;

            case 'cities':
                $q = City::with(['state', 'district']);
                if ($search) $q->where(function ($sq) use ($search) {
                    $sq->where('city_village_name', 'like', "%{$search}%")
                       ->orWhere('city_village_code', 'like', "%{$search}%")
                       ->orWhere('pincode',           'like', "%{$search}%")
                       ->orWhere('division_name',     'like', "%{$search}%");
                });
                if ($status !== '') $q->where('is_active', $status);
                $cities = $q->orderBy('city_village_name')->paginate(self::PER_PAGE)->withQueryString();
                break;

            default: // countries
                $tab = 'countries';
                $q   = Country::query();
                if ($search) $q->where('country_name', 'like', "%{$search}%")
                               ->orWhere('country_code', 'like', "%{$search}%");
                if ($status !== '') $q->where('is_active', $status);
                $countries = $q->orderBy('country_name')->paginate(self::PER_PAGE)->withQueryString();
                break;
        }

        // ── Dropdowns for Add modal ──────────────────────────────
        $allCountries = Country::orderBy('country_name')->get(['id', 'country_name']);
        $allStates    = State::orderBy('state_name')->get(['id', 'state_name', 'country_id']);
        $allDistricts = District::orderBy('district_name')->get(['id', 'district_name', 'state_id']);
        $allBlocks    = Block::orderBy('block_name')->get(['id', 'block_name', 'district_id']);

        return view('core.location.index', compact(
            'tab', 'search', 'status', 'counts',
            'countries', 'states', 'districts', 'blocks', 'cities',
            'allCountries', 'allStates', 'allDistricts', 'allBlocks'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:country,state,district,block,city',
        ]);

        switch ($request->type) {
            case 'country':
                Country::create([
                    'country_name'  => $request->name,
                    'country_code'  => $request->code,
                    'global_region' => $request->region,
                    'is_active'     => $request->boolean('is_active', true),
                ]);
                break;

            case 'state':
                State::create([
                    'country_id' => $request->country_id,
                    'state_name' => $request->name,
                    'state_code' => $request->code,
                    'is_active'  => $request->boolean('is_active', true),
                ]);
                break;

            case 'district':
                District::create([
                    'state_id'      => $request->state_id,
                    'district_name' => $request->name,
                    'district_code' => $request->code,
                    'numeric_code'  => $request->numeric_code,
                    'is_active'     => $request->boolean('is_active', true),
                ]);
                break;

            case 'block':
                Block::create([
                    'district_id' => $request->district_id,
                    'block_name'  => $request->name,
                    'block_code'  => $request->code,
                    'is_active'   => $request->boolean('is_active', true),
                ]);
                break;

            case 'city':
                City::create([
                    'state_id'          => $request->state_id,
                    'district_id'       => $request->district_id,
                    'city_village_name' => $request->name,
                    'city_village_code' => $request->code,
                    'division_name'     => $request->division_name,
                    'pincode'           => $request->pincode,
                    'is_active'         => $request->boolean('is_active', true),
                ]);
                break;
        }

        $tab = match ($request->type) {
            'state'    => 'states',
            'district' => 'districts',
            'block'    => 'blocks',
            'city'     => 'cities',
            default    => 'countries',
        };

        return redirect()->route('core.location.index', ['tab' => $tab])
                         ->with('success', ucfirst($request->type) . ' added successfully.');
    }
}
