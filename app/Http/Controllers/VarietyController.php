<?php

namespace App\Http\Controllers;

use App\Models\AliasVariety;
use App\Models\Variety;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class VarietyController extends Controller
{
    public function index()
    {
        $syncedVarieties = collect();
        $customVarieties = collect();

        if (Schema::hasTable('core_variety')) {
            $syncedVarieties = Variety::where(function ($query) {
                $query->where('remark', 'sync')
                    ->orWhereNull('remark')
                    ->orWhere('remark', '');
            })->orderBy('name')->get();
        }

        if (Schema::hasTable('alias_veriety')) {
            $customVarieties = AliasVariety::orderBy('ver_id', 'desc')->get();
        }

        return view('master.variety.index', compact('syncedVarieties', 'customVarieties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'status' => 'nullable|in:0,1',
            'remark' => 'nullable|string|max:255',
        ]);

        $name = trim((string) $request->name);
        $code = trim((string) $request->code);

        if (Schema::hasTable('alias_veriety')) {
            AliasVariety::create([
                'catalogue_no' => $code !== '' ? $code : $name,
                'ver_main' => $name,
                'ver_alias' => $name,
                'com_id' => 0,
                'com_name' => 'Custom',
                'Sts' => ($request->status ?? 1) == 1 ? 'A' : 'I',
                'cr_by' => auth()->id() ?? 0,
                'cr_date' => now()->toDateString(),
            ]);
        }

        if (Schema::hasTable('core_variety')) {
            Variety::updateOrCreate(
                ['code' => $code !== '' ? $code : $name],
                [
                    'name' => $name,
                    'code' => $code !== '' ? $code : $name,
                    'status' => $request->status ?? 1,
                    'remark' => $request->remark ?? 'custom',
                ]
            );
        }

        return redirect()->route('master.variety.index')->with('success', 'Variety created successfully.');
    }

    public function syncFromApi()
    {
        $sampleVarieties = [
            ['name' => 'Basmati 370', 'code' => 'BAS370', 'status' => 1, 'remark' => 'sync'],
            ['name' => 'Jaya', 'code' => 'JAYA', 'status' => 1, 'remark' => 'sync'],
            ['name' => 'Pusa 44', 'code' => 'PUSA44', 'status' => 1, 'remark' => 'sync'],
        ];

        foreach ($sampleVarieties as $variety) {
            Variety::updateOrCreate(
                ['code' => $variety['code']],
                $variety
            );
        }

        return redirect()->route('master.variety.index')->with('success', 'Synced variety list updated.');
    }
}
