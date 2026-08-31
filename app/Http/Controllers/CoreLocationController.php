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
    public function index()
    {
        $countries = Country::withCount('states')->latest()->get();
        $states = State::with(['country', 'districts'])->latest()->get();
        $districts = District::with(['state', 'blocks'])->latest()->get();
        $blocks = Block::with(['district', 'state'])->latest()->get();
        $cities = City::with(['state', 'district', 'block'])->latest()->get();

        return view('core.location.index', compact(
            'countries',
            'states',
            'districts',
            'blocks',
            'cities'
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
                    'country_name' => $request->name,
                    'country_code' => $request->code,
                    'global_region' => $request->region,
                    'is_active' => $request->boolean('is_active', true),
                ]);
                break;

            case 'state':
                State::create([
                    'country_id' => $request->country_id,
                    'state_name' => $request->name,
                    'state_code' => $request->code,
                    'state_type' => $request->state_type,
                    'is_active' => $request->boolean('is_active', true),
                ]);
                break;

            case 'district':
                District::create([
                    'state_id' => $request->state_id,
                    'district_name' => $request->name,
                    'district_code' => $request->code,
                    'numeric_code' => $request->numeric_code,
                    'effective_date' => $request->effective_date,
                    'is_active' => $request->boolean('is_active', true),
                ]);
                break;

            case 'block':
                Block::create([
                    'state_id' => $request->state_id,
                    'district_id' => $request->district_id,
                    'block_name' => $request->name,
                    'block_code' => $request->code,
                    'is_active' => $request->boolean('is_active', true),
                ]);
                break;

            case 'city':
                City::create([
                    'state_id' => $request->state_id,
                    'district_id' => $request->district_id,
                    'block_id' => $request->block_id,
                    'name' => $request->name,
                    'city_code' => $request->code,
                    'pincode' => $request->pincode,
                    'is_active' => $request->boolean('is_active', true),
                ]);
                break;
        }

        return redirect()->route('core.location.index')->with('success', 'Location saved successfully.');
    }
}
