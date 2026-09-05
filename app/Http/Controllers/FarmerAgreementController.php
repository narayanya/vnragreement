<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmerAgreementController extends Controller
{
    /* ── List ───────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $query = DB::table('farmer_agreements as fa')
            ->leftJoin('core_company as cc2',  'fa.first_party_id', '=', 'cc2.id')
            ->leftJoin('farmers as f',          'fa.farmer_id',      '=', 'f.fid')
            ->leftJoin('organiser as o',         'fa.organiser_id',   '=', 'o.oid')
            ->leftJoin('core_crop as cc',        'fa.crop_id',        '=', 'cc.id')
            ->select(
                'fa.*',
                'cc2.company_name as first_party_name',
                'f.fname as farmer_name',
                'o.oname as organiser_name',
                'cc.crop_name'
            );

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('f.fname',   'like', "%{$s}%")
                  ->orWhere('o.oname', 'like', "%{$s}%")
                  ->orWhere('cc.crop_name', 'like', "%{$s}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('fa.agreement_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('fa.agreement_date', '<=', $request->to_date);
        }

        $total       = DB::table('farmer_agreements')->count();
        $agreements  = $query->latest('fa.id')->paginate(20)->withQueryString();

        return view('agreement.farmer-agreement', compact('agreements', 'total'));
    }

    /* ── Create form ────────────────────────────────────────── */
    public function create()
    {
        $companies  = DB::table('core_company')->where('status', 1)->orderBy('company_name')->get(['id', 'company_name', 'company_code']);
        $farmers    = DB::table('farmers')->orderBy('fname')->get(['fid', 'fname', 'tem_fid', 'contact_1', 'oid']);
        $organisers = DB::table('organiser')->orderBy('oname')->get(['oid', 'oname']);
        $crops      = DB::table('core_crop')->where('is_active', 1)->orderBy('crop_name')->get(['id', 'crop_name', 'crop_code']);
        $varieties  = DB::table('alias_veriety')->where('Sts', 'A')->orderBy('ver_alias')->get();
        $employees  = DB::table('core_employee')->where('emp_status', 'A')->orderBy('emp_name')->get(['employee_id', 'emp_name', 'emp_code', 'emp_designation']);

        return view('agreement.create', compact(
            'companies', 'farmers', 'organisers', 'crops', 'varieties', 'employees'
        ));
    }

    /* ── Store ──────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate([
            'first_party_id'  => 'required',
            'farmer_id'       => 'required',
            'organiser_id'    => 'required',
            'agreement_date'  => 'required|date',
            'period_from'     => 'required|date',
            'period_to'       => 'required|date|after_or_equal:period_from',
            'crop_id'         => 'required',
        ]);

        DB::table('farmer_agreements')->insert([
            'first_party_id'          => $request->first_party_id,
            'farmer_id'               => $request->farmer_id,
            'organiser_id'            => $request->organiser_id,
            'pi_apm_tpm'              => $request->pi_apm_tpm,
            'production_executive'    => $request->production_executive,
            'agreement_date'          => $request->agreement_date,
            'period_from'             => $request->period_from,
            'period_to'               => $request->period_to,
            'female_variety_id'       => $request->female_variety_id,
            'male_variety_id'         => $request->male_variety_id,
            'crop_id'                 => $request->crop_id,
            'production_code'         => $request->production_code,
            'variety_type'            => $request->variety_type,
            'water_availability'      => $request->water_availability,
            'topography'              => $request->topography,
            'land_type'               => $request->land_type,
            'soil_type'               => $request->soil_type,
            'extent_of_cultivability' => $request->extent_of_cultivability,
            'qc_percent'              => $request->qc_percent,
            'incentive_details'       => $request->incentive_details,
            'additional_details'      => $request->additional_details,
            'estimated_yield'         => $request->estimated_yield,
            'loss_of_yield'           => $request->loss_of_yield,
            'cost_of_fs_seed'         => $request->cost_of_fs_seed,
            'status'                  => 1,
            'cr_by'                   => auth()->id(),
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);

        return redirect()->route('farmer-agreements.index')
                         ->with('success', 'Farmer agreement created successfully.');
    }

    /* ── Edit form ──────────────────────────────────────────── */
    public function edit($id)
    {
        $agreement  = DB::table('farmer_agreements')->where('id', $id)->firstOrFail();
        $companies  = DB::table('core_company')->where('status', 1)->orderBy('company_name')->get(['id', 'company_name', 'company_code']);
        $farmers    = DB::table('farmers')->orderBy('fname')->get(['fid', 'fname', 'tem_fid', 'contact_1', 'oid']);
        $organisers = DB::table('organiser')->orderBy('oname')->get(['oid', 'oname']);
        $crops      = DB::table('core_crop')->where('is_active', 1)->orderBy('crop_name')->get(['id', 'crop_name', 'crop_code']);
        $varieties  = DB::table('alias_veriety')->where('Sts', 'A')->orderBy('ver_alias')->get();
        $employees  = DB::table('core_employee')->where('emp_status', 'A')->orderBy('emp_name')->get(['employee_id', 'emp_name', 'emp_code', 'emp_designation']);

        return view('agreement.edit', compact(
            'agreement', 'companies', 'farmers', 'organisers', 'crops', 'varieties', 'employees'
        ));
    }

    /* ── Update ─────────────────────────────────────────────── */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_party_id'  => 'required',
            'farmer_id'       => 'required',
            'organiser_id'    => 'required',
            'agreement_date'  => 'required|date',
            'period_from'     => 'required|date',
            'period_to'       => 'required|date|after_or_equal:period_from',
            'crop_id'         => 'required',
        ]);

        DB::table('farmer_agreements')->where('id', $id)->update([
            'first_party_id'          => $request->first_party_id,
            'farmer_id'               => $request->farmer_id,
            'organiser_id'            => $request->organiser_id,
            'pi_apm_tpm'              => $request->pi_apm_tpm,
            'production_executive'    => $request->production_executive,
            'agreement_date'          => $request->agreement_date,
            'period_from'             => $request->period_from,
            'period_to'               => $request->period_to,
            'female_variety_id'       => $request->female_variety_id,
            'male_variety_id'         => $request->male_variety_id,
            'crop_id'                 => $request->crop_id,
            'production_code'         => $request->production_code,
            'variety_type'            => $request->variety_type,
            'water_availability'      => $request->water_availability,
            'topography'              => $request->topography,
            'land_type'               => $request->land_type,
            'soil_type'               => $request->soil_type,
            'extent_of_cultivability' => $request->extent_of_cultivability,
            'qc_percent'              => $request->qc_percent,
            'incentive_details'       => $request->incentive_details,
            'additional_details'      => $request->additional_details,
            'estimated_yield'         => $request->estimated_yield,
            'loss_of_yield'           => $request->loss_of_yield,
            'cost_of_fs_seed'         => $request->cost_of_fs_seed,
            'updated_at'              => now(),
        ]);

        return redirect()->route('farmer-agreements.index')
                         ->with('success', 'Agreement updated successfully.');
    }

    /* ── Destroy ────────────────────────────────────────────── */
    public function destroy($id)
    {
        DB::table('farmer_agreements')->where('id', $id)->delete();

        return redirect()->route('farmer-agreements.index')
                         ->with('success', 'Agreement deleted.');
    }
}
