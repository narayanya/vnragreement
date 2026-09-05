<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganiserAgreementController extends Controller
{
    /* ── Helpers ─────────────────────────────────────────────── */
    private function nextAgreementNo(): string
    {
        $year  = date('Y');
        $last  = DB::table('agreement_organizer')
                    ->where('org_agree_no', 'like', "ORG{$year}%")
                    ->max('org_agree_no');
        $seq   = $last ? (int) substr($last, 7) + 1 : 1;
        return 'ORG' . $year . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    private function dropdownData(): array
    {
        return [
            'companies'  => DB::table('core_company')->where('status', 1)->orderBy('company_name')->get(['id', 'company_name', 'company_code']),
            'organisers' => DB::table('organiser')->orderBy('oname')->get(['oid', 'oname', 'tmp_oid', 'authorized_signatory']),
            'employees'  => DB::table('core_employee')->where('emp_status', 'A')->orderBy('emp_name')->get(['employee_id', 'emp_name', 'emp_code', 'emp_designation']),
            'crops'      => DB::table('core_crop')->where('is_active', 1)->orderBy('crop_name')->get(['id', 'crop_name', 'crop_code']),
            'varieties'  => DB::table('alias_veriety')->where('Sts', 'A')->orderBy('ver_alias')->get(['ver_id', 'ver_alias']),
            'states'     => DB::table('core_state')->where('is_active', 1)->orderBy('state_name')->get(['id', 'state_name']),
            'districts'  => DB::table('core_district')->where('is_active', 1)->orderBy('district_name')->get(['id', 'state_id', 'district_name']),
        ];
    }

    /* ── Index ───────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $query = DB::table('agreement_organizer as ao')
            ->leftJoin('core_company as cc2',  'ao.first_party',  '=', 'cc2.id')
            ->leftJoin('organiser as o',        'ao.second_party', '=', 'o.oid')
            ->leftJoin('core_state as cs',       'ao.states',       '=', 'cs.id')
            ->select(
                'ao.*',
                'cc2.company_name  as first_party_name',
                'o.oname           as organiser_name',
                'cs.state_name     as state_name'
            )
            ->where('ao.deleted_at', 0);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('o.oname',           'like', "%{$s}%")
                  ->orWhere('ao.org_agree_no',  'like', "%{$s}%")
                  ->orWhere('cc2.company_name', 'like', "%{$s}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('ao.agree_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('ao.agree_date', '<=', $request->to_date);
        }

        $total      = DB::table('agreement_organizer')->where('deleted_at', 0)->count();
        $agreements = $query->latest('ao.agree_id')->paginate(20)->withQueryString();

        return view('agreement.organiser.organiser-agreement', compact('agreements', 'total'));
    }

    /* ── Create form ─────────────────────────────────────────── */
    public function create()
    {
        $nextNo = $this->nextAgreementNo();
        return view('agreement.organiser.create', array_merge(
            $this->dropdownData(),
            ['nextNo' => $nextNo]
        ));
    }

    /* ── Store ───────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate([
            'first_party'              => 'required',
            'second_party'             => 'required',
            'agree_date'               => 'required|date',
            'start_date'               => 'required|date',
            'end_date'                 => 'required|date|after_or_equal:start_date',
            'agreement_location'       => 'required|string|max:255',
            'season'                   => 'required|array|min:1',
            'season.*'                 => 'in:Kharif,Rabi',
            'states'                   => 'required',
            'districts'                => 'required',
            'production_area'          => 'required|numeric|min:0.001',
            'last_date_of_sowing'      => 'required|date',
            'production_region'        => 'required|string|max:255',
        ]);

        $orgAgreeNo = $this->nextAgreementNo();

        DB::table('agreement_organizer')->insert([
            'org_agree_no'                => $orgAgreeNo,
            'first_party'                 => $request->first_party,
            'second_party'                => $request->second_party,
            'agree_date'                  => $request->agree_date,
            'start_date'                  => $request->start_date,
            'end_date'                    => $request->end_date,
            'authorized_signatory'        => $request->authorized_signatory,
            'second_authorized_signatory' => $request->second_authorized_signatory ?? '',
            'agreement_location'          => $request->agreement_location,
            'season'                      => implode(',', (array) $request->season),
            'states'                      => is_array($request->states)   ? implode(',', $request->states)   : $request->states,
            'districts'                   => is_array($request->districts) ? implode(',', $request->districts) : $request->districts,
            'production_area'             => $request->production_area,
            'last_date_of_sowing'         => $request->last_date_of_sowing,
            'production_region'           => $request->production_region,
            'crby'                        => auth()->id(),
            'crdate'                      => now(),
            'deleted_at'                  => 0,
        ]);

        // ── Annexure I: Particulars (repeatable rows) ──────────
        if ($request->filled('particulars')) {
            $rows = [];
            foreach ($request->particulars as $i => $particular) {
                if (empty($particular)) continue;
                $rows[] = [
                    'org_agree_no'  => $orgAgreeNo,
                    'particulars'   => $particular,
                    'amount_per_acre' => $request->amount_per_acre[$i] ?? null,
                    'remarks'       => $request->remarks[$i] ?? null,
                    'crby'          => auth()->id(),
                    'crdate'        => now(),
                    'created_at'    => now(),
                ];
            }
            if ($rows) DB::table('agreement_organizer_particulars')->insert($rows);
        }

        // ── Annexure IA: Crops (repeatable rows) ──────────────
        if ($request->filled('crop_id')) {
            $rows = [];
            foreach ($request->crop_id as $i => $cropId) {
                if (empty($cropId)) continue;
                $rows[] = [
                    'org_agree_no'              => $orgAgreeNo,
                    'crop_id'                   => $cropId,
                    'crop_category'             => $request->crop_category[$i] ?? null,
                    'fs_code'                   => $request->fs_code[$i] ?? null,
                    'growar_price'              => $request->growar_price[$i] ?? null,
                    'quality_based_incentive'   => $request->quality_based_incentive[$i] ?? null,
                    'organizer_commission'      => $request->organizer_commission[$i] ?? null,
                    'advance_payment'           => $request->advance_payment[$i] ?? null,
                    'crby'                      => auth()->id(),
                    'crdate'                    => now(),
                    'created_at'                => now(),
                ];
            }
            if ($rows) DB::table('agreement_organizer_crops')->insert($rows);
        }

        // ── Annexure IV: Foundation Seed (repeatable rows) ────
        if ($request->filled('found_crop_id')) {
            $rows = [];
            foreach ($request->found_crop_id as $i => $cropId) {
                if (empty($cropId)) continue;
                $rows[] = [
                    'org_agree_no'       => $orgAgreeNo,
                    'crop_id'            => $cropId,
                    'fs_production_code' => $request->fs_production_code[$i] ?? null,
                    'fs_seed_mf'         => $request->fs_seed_mf[$i] ?? null,
                    'no_of_acres'        => $request->no_of_acres[$i] ?? null,
                    'total_fs'           => $request->total_fs[$i] ?? null,
                    'price'              => $request->price[$i] ?? null,
                    'total_amount'       => $request->total_amount[$i] ?? null,
                    'crby'               => auth()->id(),
                    'crdate'             => now(),
                ];
            }
            if ($rows) DB::table('agreement_organizer_foundation')->insert($rows);
        }

        return redirect()->route('organiser-agreements.index')
                         ->with('success', "Organiser agreement {$orgAgreeNo} created successfully.");
    }

    /* ── Show / Preview ──────────────────────────────────────── */
    public function show($id)
    {
        $ag = DB::table('agreement_organizer')->where('agree_id', $id)->firstOrFail();

        // First party (company)
        $company = DB::table('core_company')->where('id', $ag->first_party)->first();

        // Second party (organiser) with location
        $organiser = DB::table('organiser')->where('oid', $ag->second_party)->first();
        $orgState  = $organiser ? DB::table('core_state')->where('id', $organiser->state_id)->value('state_name') : null;
        $orgDist   = $organiser ? DB::table('core_district')->where('id', $organiser->district_id)->value('district_name') : null;

        // Authorised signatory (employee)
        $signatory = $ag->authorized_signatory
            ? DB::table('core_employee')->where('employee_id', $ag->authorized_signatory)->first()
            : null;

        // Agreement location state/district
        $agState = DB::table('core_state')->where('id', $ag->states)->value('state_name');
        $agDist  = DB::table('core_district')->where('id', $ag->districts)->value('district_name');

        // Annexure data
        $particulars = DB::table('agreement_organizer_particulars')
            ->where('org_agree_no', $ag->org_agree_no)->get();

        $cropRows = DB::table('agreement_organizer_crops as aoc')
            ->leftJoin('core_crop as cc', 'aoc.crop_id', '=', 'cc.id')
            ->where('aoc.org_agree_no', $ag->org_agree_no)
            ->select('aoc.*', 'cc.crop_name')
            ->get();

        $foundationRows = DB::table('agreement_organizer_foundation as aof')
            ->leftJoin('core_crop as cc', 'aof.crop_id', '=', 'cc.id')
            ->where('aof.org_agree_no', $ag->org_agree_no)
            ->select('aof.*', 'cc.crop_name')
            ->get();

        // Annexure III — contracted farmers linked through farmer_agreements
        $growerRows = DB::table('farmer_agreements as fa')
            ->leftJoin('farmers as f',    'fa.farmer_id', '=', 'f.fid')
            ->leftJoin('core_crop as cc', 'fa.crop_id',   '=', 'cc.id')
            ->where('fa.organiser_id', $ag->second_party)
            ->select(
                'fa.id as agree_id',
                'cc.crop_name',
                'f.tem_fid as farmer_id',
                'f.fname   as farmer_name',
                'fa.production_region as location',
                DB::raw('NULL as standing_area')
            )
            ->get();

        return view('agreement.organiser.show', compact(
            'ag', 'company', 'organiser', 'orgState', 'orgDist',
            'signatory', 'agState', 'agDist',
            'particulars', 'cropRows', 'foundationRows', 'growerRows'
        ));
    }

    /* ── Edit form ───────────────────────────────────────────── */
    public function edit($id)
    {
        $agreement   = DB::table('agreement_organizer')->where('agree_id', $id)->firstOrFail();
        $particulars = DB::table('agreement_organizer_particulars')->where('org_agree_no', $agreement->org_agree_no)->get();
        $crops       = DB::table('agreement_organizer_crops')->where('org_agree_no', $agreement->org_agree_no)->get();
        $foundation  = DB::table('agreement_organizer_foundation')->where('org_agree_no', $agreement->org_agree_no)->get();

        $data = $this->dropdownData();
        $data['allCrops']    = $data['crops'];     // rename for view clarity
        $data['crops']       = $crops;             // override with child rows
        $data['particulars'] = $particulars;
        $data['foundation']  = $foundation;
        $data['agreement']   = $agreement;

        return view('agreement.organiser.edit', $data);
    }

    /* ── Update ──────────────────────────────────────────────── */
    public function update(Request $request, $id)
    {
        $agreement = DB::table('agreement_organizer')->where('agree_id', $id)->firstOrFail();

        $request->validate([
            'first_party'         => 'required',
            'second_party'        => 'required',
            'agree_date'          => 'required|date',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'agreement_location'  => 'required|string|max:255',
            'season'              => 'required|array|min:1',
            'season.*'            => 'in:Kharif,Rabi',
            'states'              => 'required',
            'districts'           => 'required',
            'production_area'     => 'required|numeric|min:0.001',
            'last_date_of_sowing' => 'required|date',
            'production_region'   => 'required|string|max:255',
        ]);

        // ── Main record ────────────────────────────────────────
        DB::table('agreement_organizer')->where('agree_id', $id)->update([
            'first_party'                 => $request->first_party,
            'second_party'                => $request->second_party,
            'agree_date'                  => $request->agree_date,
            'start_date'                  => $request->start_date,
            'end_date'                    => $request->end_date,
            'authorized_signatory'        => $request->authorized_signatory,
            'second_authorized_signatory' => $request->second_authorized_signatory ?? '',
            'agreement_location'          => $request->agreement_location,
            'season'                      => implode(',', (array) $request->season),
            'states'                      => is_array($request->states)    ? implode(',', $request->states)    : $request->states,
            'districts'                   => is_array($request->districts) ? implode(',', $request->districts) : $request->districts,
            'production_area'             => $request->production_area,
            'last_date_of_sowing'         => $request->last_date_of_sowing,
            'production_region'           => $request->production_region,
        ]);

        $orgAgreeNo = $agreement->org_agree_no;

        // ── Annexure I: Particulars — delete & re-insert ───────
        DB::table('agreement_organizer_particulars')->where('org_agree_no', $orgAgreeNo)->delete();
        if ($request->filled('particulars')) {
            $rows = [];
            foreach ($request->particulars as $i => $particular) {
                if (empty($particular)) continue;
                $rows[] = [
                    'org_agree_no'    => $orgAgreeNo,
                    'particulars'     => $particular,
                    'amount_per_acre' => $request->amount_per_acre[$i] ?? null,
                    'remarks'         => $request->remarks[$i] ?? null,
                    'crby'            => auth()->id(),
                    'crdate'          => now(),
                    'created_at'      => now(),
                ];
            }
            if ($rows) DB::table('agreement_organizer_particulars')->insert($rows);
        }

        // ── Annexure IA: Crops — delete & re-insert ────────────
        DB::table('agreement_organizer_crops')->where('org_agree_no', $orgAgreeNo)->delete();
        if ($request->filled('crop_id')) {
            $rows = [];
            foreach ($request->crop_id as $i => $cropId) {
                if (empty($cropId)) continue;
                $rows[] = [
                    'org_agree_no'            => $orgAgreeNo,
                    'crop_id'                 => $cropId,
                    'crop_category'           => $request->crop_category[$i] ?? null,
                    'fs_code'                 => $request->fs_code[$i] ?? null,
                    'growar_price'            => $request->growar_price[$i] ?? null,
                    'quality_based_incentive' => $request->quality_based_incentive[$i] ?? null,
                    'organizer_commission'    => $request->organizer_commission[$i] ?? null,
                    'advance_payment'         => $request->advance_payment[$i] ?? null,
                    'crby'                    => auth()->id(),
                    'crdate'                  => now(),
                    'created_at'              => now(),
                ];
            }
            if ($rows) DB::table('agreement_organizer_crops')->insert($rows);
        }

        // ── Annexure IV: Foundation — delete & re-insert ───────
        DB::table('agreement_organizer_foundation')->where('org_agree_no', $orgAgreeNo)->delete();
        if ($request->filled('found_crop_id')) {
            $rows = [];
            foreach ($request->found_crop_id as $i => $cropId) {
                if (empty($cropId)) continue;
                $rows[] = [
                    'org_agree_no'       => $orgAgreeNo,
                    'crop_id'            => $cropId,
                    'fs_production_code' => $request->fs_production_code[$i] ?? null,
                    'fs_seed_mf'         => $request->fs_seed_mf[$i] ?? null,
                    'no_of_acres'        => $request->no_of_acres[$i] ?? null,
                    'total_fs'           => $request->total_fs[$i] ?? null,
                    'price'              => $request->price[$i] ?? null,
                    'total_amount'       => $request->total_amount[$i] ?? null,
                    'crby'               => auth()->id(),
                    'crdate'             => now(),
                ];
            }
            if ($rows) DB::table('agreement_organizer_foundation')->insert($rows);
        }

        return redirect()->route('organiser-agreements.index')
                         ->with('success', "Agreement {$orgAgreeNo} updated successfully.");
    }

    /* ── Destroy ─────────────────────────────────────────────── */
    public function destroy($id)
    {
        DB::table('agreement_organizer')->where('agree_id', $id)->update(['deleted_at' => now()]);
        return redirect()->route('organiser-agreements.index')->with('success', 'Agreement deleted.');
    }
}
