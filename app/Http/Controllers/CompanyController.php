<?php

namespace App\Http\Controllers;

use App\Models\AliasCompany;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $syncedCompanies = Company::where(function ($query) {
            $query->where('remark', 'sync')
                ->orWhereNull('remark')
                ->orWhere('remark', '');
        })->get();

        $customCompanies = AliasCompany::orderBy('com_id', 'desc')->get();

        return view('master.company.index', compact('syncedCompanies', 'customCompanies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_code' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:1000',
            'status' => 'nullable|in:0,1',
        ]);

        $companyName = trim((string) $request->company_name);
        $companyCode = trim((string) $request->company_code);

        AliasCompany::create([
            'com_main' => $companyName,
            'com_alias' => $companyName,
            'com_code' => $companyCode !== '' ? $companyCode : $companyName,
            'Sts' => ($request->status ?? 1) == 1 ? 'A' : 'I',
            'cr_by' => auth()->id() ?? 0,
            'cr_date' => now()->toDateString(),
        ]);

        Company::updateOrCreate(
            ['company_code' => $companyCode !== '' ? $companyCode : $companyName],
            [
                'company_name' => $companyName,
                'company_code' => $companyCode !== '' ? $companyCode : $companyName,
                'email' => $request->email,
                'phone' => $request->phone,
                'website' => $request->website,
                'address' => $request->address,
                'status' => $request->status ?? 1,
                'remark' => 'custom',
            ]
        );

        return redirect()->route('master.company.index')->with('success', 'Company created successfully.');
    }

    public function syncFromApi()
    {
        $sampleCompanies = [
            ['company_name' => 'ABC Agro Ltd', 'company_code' => 'ABC001', 'email' => 'hello@abcagro.com', 'phone' => '9876543210', 'website' => 'https://abcagro.com', 'address' => 'Mumbai, India', 'status' => 1, 'remark' => 'sync'],
            ['company_name' => 'Green Valley Seeds', 'company_code' => 'GVS002', 'email' => 'sales@greenvalley.in', 'phone' => '9123456780', 'website' => 'https://greenvalley.in', 'address' => 'Hyderabad, India', 'status' => 1, 'remark' => 'sync'],
            ['company_name' => 'Prime Agritech', 'company_code' => 'PAT003', 'email' => 'contact@primeagritech.in', 'phone' => '9988776655', 'website' => 'https://primeagritech.in', 'address' => 'Pune, India', 'status' => 1, 'remark' => 'sync'],
        ];

        foreach ($sampleCompanies as $company) {
            Company::updateOrCreate(
                ['company_code' => $company['company_code']],
                $company
            );
        }

        return redirect()->route('master.company.index')->with('success', 'Synced company list updated.');
    }
}
