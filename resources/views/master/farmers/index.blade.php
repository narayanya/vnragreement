@extends('layouts.app')

@section('content')
{{-- Page Loader --}}
<div id="pageLoader" class="page-loader">
    <div class="spinner-container">
        <div class="spinner"></div>
        <p class="loader-text">Loading farmers data...</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">Farmer Master</h3>
                <p class="mb-0 text-muted" style="font-size:13px">View and manage farmer records &mdash; Total: {{ $total }}</p>
            </div>
            <button class="btn btn-sm btn-primary" id="addFarmerBtn">
                <i class="ri-add-line me-1"></i>New Farmer
            </button>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-1"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Search --}}
        <div class="card mb-3">
            <div class="card-body py-2">
                <form method="GET" action="{{ route('master.farmers.index') }}">
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Search by name, ID, contact, email, Aadhar or village…"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label class="form-label mb-0 text-muted" style="font-size: 12px;">From Date</label>
                            <input type="date" name="from_date" class="form-control form-control-sm"
                                value="{{ request('from_date') }}">
                        </div>
                        <div class="col-auto">
                            <label class="form-label mb-0 text-muted" style="font-size: 12px;">To Date</label>
                            <input type="date" name="to_date" class="form-control form-control-sm"
                                value="{{ request('to_date') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-filter-line me-1"></i>Filter
                            </button>
                            @if(request('search') || request('from_date') || request('to_date'))
                                <a href="{{ route('master.farmers.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="ri-close-line me-1"></i>Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        @if($formars->count())
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Farmer ID</th>
                                    <th>Name</th>
                                    <th>Father Name</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Aadhar</th>
                                    <th>Total Land</th>
                                    <th>Created</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:13px">
                                @foreach($formars as $farmer)
                                <tr>
                                    <td class="ps-3 text-muted">
                                        {{ ($formars->currentPage() - 1) * $formars->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-white">{{ $farmer->tem_fid ?? $farmer->fid }}</span>
                                    </td>
                                    <td class="fw-semibold" style="color:#172b4d">{{ $farmer->fname ?? '-' }}</td>
                                    <td class="text-muted">{{ $farmer->father_name ?? '-' }}</td>
                                    <td>
                                        {{ $farmer->contact_1 ?? '-' }}
                                        @if($farmer->contact_2)
                                            <br><small class="text-muted">{{ $farmer->contact_2 }}</small>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        @if($farmer->email)
                                            <a href="mailto:{{ $farmer->email }}" class="text-decoration-none">{{ $farmer->email }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $farmer->aadhar_no ?? '-' }}</td>
                                    <td class="text-muted">
                                        {{ $farmer->total_land ? number_format($farmer->total_land, 3).' ac' : '-' }}
                                    </td>
                                    <td class="text-muted">
                                        {{ $farmer->cr_date ? \Carbon\Carbon::parse($farmer->cr_date)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td class="text-end pe-3">
                                        {{-- View --}}
                                        <button class="btn btn-sm btn-outline-info viewFarmerBtn"
                                            data-id="{{ $farmer->fid }}"
                                            data-tem_fid="{{ $farmer->tem_fid }}"
                                            data-fname="{{ $farmer->fname }}"
                                            data-father_name="{{ $farmer->father_name }}"
                                            data-father_contact="{{ $farmer->father_contact }}"
                                            data-contact_1="{{ $farmer->contact_1 }}"
                                            data-contact_2="{{ $farmer->contact_2 }}"
                                            data-email="{{ $farmer->email }}"
                                            data-dob="{{ $farmer->dob }}"
                                            data-age="{{ $farmer->age }}"
                                            data-oid="{{ $farmer->oid }}"
                                            data-address="{{ $farmer->address }}"
                                            data-pincode="{{ $farmer->pincode }}"
                                            data-aadhar_no="{{ $farmer->aadhar_no }}"
                                            data-pan_no="{{ $farmer->pan_no }}"
                                            data-bank_name="{{ $farmer->bank_name }}"
                                            data-account_no="{{ $farmer->account_no }}"
                                            data-branch_name="{{ $farmer->branch_name }}"
                                            data-ifsc_code="{{ $farmer->ifsc_code }}"
                                            data-total_land="{{ $farmer->total_land }}"
                                            data-cr_date="{{ $farmer->cr_date }}"
                                            title="View">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        {{-- Edit --}}
                                        <button class="btn btn-sm btn-outline-warning editFarmerBtn"
                                            data-id="{{ $farmer->fid }}"
                                            data-tem_fid="{{ $farmer->tem_fid }}"
                                            data-fname="{{ $farmer->fname }}"
                                            data-father_name="{{ $farmer->father_name }}"
                                            data-father_contact="{{ $farmer->father_contact }}"
                                            data-contact_1="{{ $farmer->contact_1 }}"
                                            data-contact_2="{{ $farmer->contact_2 }}"
                                            data-email="{{ $farmer->email }}"
                                            data-dob="{{ $farmer->dob }}"
                                            data-age="{{ $farmer->age }}"
                                            data-oid="{{ $farmer->oid }}"
                                            data-address="{{ $farmer->address }}"
                                            data-pincode="{{ $farmer->pincode }}"
                                            data-state_id="{{ $farmer->state_id }}"
                                            data-distric_id="{{ $farmer->distric_id }}"
                                            data-tahsil_id="{{ $farmer->tahsil_id }}"
                                            data-village_id="{{ $farmer->village_id }}"
                                            data-aadhar_no="{{ $farmer->aadhar_no }}"
                                            data-pan_no="{{ $farmer->pan_no }}"
                                            data-idproof_name="{{ $farmer->idproof_name }}"
                                            data-idproof_no="{{ $farmer->idproof_no }}"
                                            data-addproof_name="{{ $farmer->addproof_name }}"
                                            data-addproof_no="{{ $farmer->addproof_no }}"
                                            data-bank_name="{{ $farmer->bank_name }}"
                                            data-account_no="{{ $farmer->account_no }}"
                                            data-branch_name="{{ $farmer->branch_name }}"
                                            data-ifsc_code="{{ $farmer->ifsc_code }}"
                                            data-bank_add="{{ $farmer->bank_add }}"
                                            data-doc_passbook="{{ $farmer->doc_passbook }}"
                                            data-total_land="{{ $farmer->total_land }}"
                                            title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        {{-- Delete --}}
                                        <form action="{{ route('master.farmers.destroy', $farmer->fid) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this farmer?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($formars->hasPages())
                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
                    <small class="text-muted">
                        Showing {{ $formars->firstItem() }} to {{ $formars->lastItem() }} of {{ $formars->total() }} results
                    </small>
                    {{ $formars->links() }}
                </div>
                @endif
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-user-line" style="font-size:40px;color:#c8d6e5"></i>
                    <p class="text-muted mt-2 mb-3">No farmers found.</p>
                    <button class="btn btn-sm btn-primary" id="addFirstFarmerBtn">
                        <i class="ri-add-line me-1"></i>Add First Farmer
                    </button>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@section('modals')

{{-- ── View Modal ───────────────────────────────────────────── --}}
<div class="modal fade" id="viewFarmerModal" tabindex="-1" aria-labelledby="viewFarmerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewFarmerModalLabel">
                    <i class="ri-eye-line me-1"></i>Farmer Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-fill mb-3" id="viewFarmerTabs">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#vf_basic">
                            <i class="ri-user-line me-1"></i>Basic
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#vf_bank">
                            <i class="ri-bank-line me-1"></i>Bank
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#vf_land">
                            <i class="ri-map-2-line me-1"></i>Land Details
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="vf_basic">
                        <table class="table table-sm table-bordered table-striped">
                            <tr><th style="width:35%">Farmer ID</th><td id="v_tem_fid">-</td></tr>
                            <tr><th>Name</th><td id="v_fname">-</td></tr>
                            <tr><th>Father Name</th><td id="v_father_name">-</td></tr>
                            <tr><th>Father Contact</th><td id="v_father_contact">-</td></tr>
                            <tr><th>Contact 1</th><td id="v_contact_1">-</td></tr>
                            <tr><th>Contact 2</th><td id="v_contact_2">-</td></tr>
                            <tr><th>Email</th><td id="v_email">-</td></tr>
                            <tr><th>Date of Birth</th><td id="v_dob">-</td></tr>
                            <tr><th>Age</th><td id="v_age">-</td></tr>
                            <tr><th>Address</th><td id="v_address">-</td></tr>
                            <tr><th>Pincode</th><td id="v_pincode">-</td></tr>
                            <tr><th>Aadhar No</th><td id="v_aadhar_no">-</td></tr>
                            <tr><th>PAN No</th><td id="v_pan_no">-</td></tr>
                            <tr><th>Total Land</th><td id="v_total_land">-</td></tr>
                            <tr><th>Created Date</th><td id="v_cr_date">-</td></tr>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="vf_bank">
                        <table class="table table-sm table-bordered table-striped">
                            <tr><th style="width:35%">Bank Name</th><td id="v_bank_name">-</td></tr>
                            <tr><th>Account No</th><td id="v_account_no">-</td></tr>
                            <tr><th>Branch Name</th><td id="v_branch_name">-</td></tr>
                            <tr><th>IFSC Code</th><td id="v_ifsc_code">-</td></tr>
                        </table>
                    </div>

                    {{-- View Land tab --}}
                    <div class="tab-pane fade" id="vf_land">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size:12px">
                                <thead class="table-light" style="text-transform:uppercase;letter-spacing:.4px;font-size:11px">
                                    <tr>
                                        <th>#</th>
                                        <th>State</th>
                                        <th>District</th>
                                        <th>Tahsil</th>
                                        <th>Village</th>
                                        <th>Area (Ac)</th>
                                        <th>Khasra / Survey No.</th>
                                        <th>Plot No.</th>
                                    </tr>
                                </thead>
                                <tbody id="viewLandTableBody">
                                    <tr><td colspan="8" class="text-center text-muted py-3">Loading…</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ── Add / Edit Modal ─────────────────────────────────────── --}}
<div class="modal fade" id="farmerModal" tabindex="-1" aria-labelledby="farmerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmerModalLabel">
                    <i class="ri-add-line me-1"></i>New Farmer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="farmerForm" method="POST" action="{{ route('master.farmers.store') }}">
                @csrf
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="farmerTabs">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#f_basic">
                                <i class="ri-user-line me-1"></i>Basic
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#f_address">
                                <i class="ri-map-pin-line me-1"></i>Address
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#f_docs">
                                <i class="ri-file-list-line me-1"></i>Documents
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#f_bank">
                                <i class="ri-bank-line me-1"></i>Bank
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#f_land">
                                <i class="ri-map-2-line me-1"></i>Land Details
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- Basic --}}
                        <div class="tab-pane fade show active" id="f_basic">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Farmer ID (Temp)</label>
                                    <input type="text" name="tem_fid" class="form-control" maxlength="30" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Farmer Name <span class="text-danger">*</span></label>
                                    <input type="text" name="fname" class="form-control" required maxlength="50">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Organiser</label>
                                    <select name="oid" class="form-select">
                                        <option value="">-- Select Organiser --</option>
                                        @foreach($organisers as $organiser)
                                            <option value="{{ $organiser->oid }}">
                                                {{ $organiser->oname }}{{ $organiser->tmp_oid ? ' ('.$organiser->tmp_oid.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Father Name</label>
                                    <input type="text" name="father_name" class="form-control" maxlength="30">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Father Contact</label>
                                    <input type="text" name="father_contact" class="form-control" maxlength="10">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Age</label>
                                    <input type="text" name="age" class="form-control" maxlength="3">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact 1 <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_1" class="form-control" required maxlength="10">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact 2</label>
                                    <input type="text" name="contact_2" class="form-control" maxlength="10">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Total Land (acres)</label>
                                    <input type="number" step="0.001" name="total_land" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="tab-pane fade" id="f_address">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="2" maxlength="150"></textarea>
                                </div>

                                {{-- State — old table --}}
                                <div class="col-md-6">
                                    <label class="form-label">State</label>
                                    <select name="state_id" id="fa_state_id" class="form-select form-select-sm">
                                        <option value="">-- Select State --</option>
                                        @foreach($oldStates as $oldstate)
                                            <option value="{{ $oldstate->StateId }}">{{ $oldstate->StateName }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- District — old table (distric) --}}
                                <div class="col-md-6">
                                    <label class="form-label">District</label>
                                    <select name="distric_id" id="fa_distric_id" class="form-select form-select-sm">
                                        <option value="">-- Select District --</option>
                                        @foreach($oldDistricts as $dist)
                                            <option value="{{ $dist->DictrictId }}" data-state="{{ $dist->StateId }}">
                                                {{ $dist->DictrictName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tahsil — old table --}}
                                <div class="col-md-6">
                                    <label class="form-label">Tahsil</label>
                                    <select name="tahsil_id" id="fa_tahsil_id" class="form-select form-select-sm">
                                        <option value="">-- Select Tahsil --</option>
                                        @foreach($oldTahsils as $tahsil)
                                            <option value="{{ $tahsil->TahsilId }}" data-district="{{ $tahsil->DistrictId }}">
                                                {{ $tahsil->TahsilName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Village — old table --}}
                                <div class="col-md-6">
                                    <label class="form-label">Village</label>
                                    <select name="village_id" id="fa_village_id" class="form-select form-select-sm">
                                        <option value="">-- Select Village --</option>
                                        @foreach($oldVillages as $village)
                                            <option value="{{ $village->VillageId }}" data-tahsil="{{ $village->TahsilId }}">
                                                {{ $village->VillageName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Pincode</label>
                                    <input type="text" name="pincode" class="form-control" maxlength="10">
                                </div>
                            </div>
                        </div>

                        {{-- Documents --}}
                        <div class="tab-pane fade" id="f_docs">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Aadhar No</label>
                                    <input type="text" name="aadhar_no" class="form-control" maxlength="15">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">PAN No</label>
                                    <input type="text" name="pan_no" class="form-control" maxlength="15">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ID Proof Name</label>
                                    <input type="text" name="idproof_name" class="form-control" maxlength="30">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ID Proof No</label>
                                    <input type="text" name="idproof_no" class="form-control" maxlength="20">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Address Proof Type</label>
                                    <select name="addproof_name" class="form-select form-select-sm">
                                        <option value="">-- Select Type --</option>
                                        <option value="Aadhar">Aadhar</option>
                                        <option value="PAN">PAN</option>
                                        <option value="Rashan Card">Rashan Card</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Address Proof No</label>
                                    <input type="text" name="addproof_no" class="form-control" maxlength="20">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Upload Address Proof Document</label>
                                    <input type="file" name="doc_addproof" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <small class="text-muted">Allowed formats: PDF, JPG, JPEG, PNG, DOC, DOCX</small>
                                </div>
                            </div>
                        </div>

                        {{-- Bank --}}
                        <div class="tab-pane fade" id="f_bank">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" maxlength="50">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Account No</label>
                                    <input type="text" name="account_no" class="form-control" maxlength="30">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Branch Name</label>
                                    <input type="text" name="branch_name" class="form-control" maxlength="50">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="form-control" maxlength="15">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Bank Address</label>
                                    <input type="text" name="bank_add" class="form-control" maxlength="100">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Bank Passbook / Document</label>
                                    <div id="existingBankDoc" class="alert alert-info d-none mb-2">
                                        <small>
                                            <i class="ri-file-check-line me-1"></i>
                                            Document uploaded: <strong id="bankDocName">-</strong>
                                            <button type="button" class="btn btn-sm btn-outline-warning float-end" id="changeBankDoc">
                                                <i class="ri-edit-line me-1"></i>Change
                                            </button>
                                        </small>
                                    </div>
                                    <input type="file" name="doc_passbook" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <small class="text-muted">Allowed formats: PDF, JPG, JPEG, PNG, DOC, DOCX</small>
                                </div>
                            </div>
                        </div>

                        {{-- Land Details --}}
                        <div class="tab-pane fade" id="f_land">
                            {{-- Only shown when editing (fid exists) --}}
                            <div id="landEditSection" class="d-none">

                                {{-- Add new land entry --}}
                                <div class="card mb-3 border" style="background:#f6f8fb">
                                    <div class="card-body py-3">
                                        <h6 class="mb-3" style="color:#172b4d;font-weight:700">
                                            <i class="ri-add-line me-1"></i>Add Land Entry
                                        </h6>
                                        <div class="row g-2">
                                            {{-- State --}}
                                            <div class="col-md-6">
                                                <label class="form-label">State <span class="text-danger">*</span></label>
                                                <select id="land_StateId" class="form-select form-select-sm">
                                                    <option value="">-- Select State --</option>
                                                    @foreach($oldStates as $oldState)
                                                        <option value="{{ $oldState->StateId }}">{{ $oldState->StateName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- District --}}
                                            <div class="col-md-6">
                                                <label class="form-label">District <span class="text-danger">*</span></label>
                                                <select id="land_DictrictId" class="form-select form-select-sm">
                                                    <option value="">-- Select District --</option>
                                                    @foreach($oldDistricts as $dist)
                                                        <option value="{{ $dist->DictrictId }}" data-state="{{ $dist->StateId }}">{{ $dist->DictrictName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Tahsil --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Tahsil <span class="text-danger">*</span></label>
                                                <select id="land_TahsilId" class="form-select form-select-sm">
                                                    <option value="">-- Select Tahsil --</option>
                                                    @foreach($oldTahsils as $tahsil)
                                                        <option value="{{ $tahsil->TahsilId }}" data-district="{{ $tahsil->DistrictId }}">{{ $tahsil->TahsilName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Village --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Village <span class="text-danger">*</span></label>
                                                <select id="land_VillageId" class="form-select form-select-sm">
                                                    <option value="">-- Select Village --</option>
                                                    @foreach($oldVillages as $village)
                                                        <option value="{{ $village->VillageId }}" data-tahsil="{{ $village->TahsilId }}">{{ $village->VillageName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Sowing Area --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Sowing Area (Acres) <span class="text-danger">*</span></label>
                                                <input type="number" id="land_area" class="form-control form-control-sm" step="0.001" min="0.001" placeholder="e.g. 2.500">
                                            </div>
                                            {{-- Khasra No --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Khasra / Survey No. <span class="text-danger">*</span></label>
                                                <input type="text" id="land_khasra_no" class="form-control form-control-sm" maxlength="30" placeholder="e.g. 123/A">
                                            </div>
                                            {{-- Plot No --}}
                                            <div class="col-md-6">
                                                <label class="form-label">Plot No.</label>
                                                <input type="text" id="land_plot_no" class="form-control form-control-sm" maxlength="30" placeholder="Optional">
                                            </div>
                                        </div>
                                        <div class="mt-3 d-flex gap-2 align-items-center">
                                            <button type="button" class="btn btn-sm btn-primary" id="addLandEntryBtn">
                                                <i class="ri-add-line me-1"></i>Add Entry
                                            </button>
                                            <span id="landSaveMsg" class="text-success" style="font-size:12px;display:none">
                                                <i class="ri-checkbox-circle-line me-1"></i>Entry added successfully.
                                            </span>
                                            <span id="landErrMsg" class="text-danger" style="font-size:12px;display:none"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Land entries table --}}
                                <div>
                                    <h6 class="mb-2" style="color:#172b4d;font-weight:700">
                                        <i class="ri-list-check me-1"></i>Land Entries
                                        <span id="landCount" class="badge ms-1" style="background:#e6f4f3;color:#187b78;font-size:11px">0</span>
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover align-middle mb-0" style="font-size:12px">
                                            <thead class="table-light" style="text-transform:uppercase;letter-spacing:.4px;font-size:11px">
                                                <tr>
                                                    <th>#</th>
                                                    <th>State</th>
                                                    <th>District</th>
                                                    <th>Tahsil</th>
                                                    <th>Village</th>
                                                    <th>Area (Ac)</th>
                                                    <th>Khasra / Survey No.</th>
                                                    <th>Plot No.</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="landTableBody">
                                                <tr id="landEmptyRow">
                                                    <td colspan="9" class="text-center text-muted py-3">No land entries yet.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>

                            {{-- Shown when adding new farmer --}}
                            <div id="landNewSection">
                                <div class="alert alert-info mb-0" style="font-size:13px">
                                    <i class="ri-information-line me-2"></i>
                                    Save the farmer first, then add land details from the <strong>Edit</strong> form.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="farmerSubmitBtn">
                        <i class="ri-save-line me-1"></i>Save Farmer
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalEl = document.getElementById('farmerModal');
    const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);

    // ── Cascading address dropdowns (defined first so edit handler can use them) ──
    const allDistrictOpts = Array.from(document.querySelectorAll('#fa_distric_id option'));
    const allTahsilOpts   = Array.from(document.querySelectorAll('#fa_tahsil_id option'));
    const allVillageOpts  = Array.from(document.querySelectorAll('#fa_village_id option'));

    function filterSelect(selectEl, allOpts, filterAttr, filterVal, keepVal) {
        if (!selectEl) return;
        selectEl.innerHTML = '<option value="">-- Select --</option>';
        allOpts.forEach(opt => {
            if (!opt.value) return;
            if (!filterVal || opt.dataset[filterAttr] == filterVal) {
                const clone = opt.cloneNode(true);
                if (keepVal && clone.value == keepVal) clone.selected = true;
                selectEl.appendChild(clone);
            }
        });
    }

    document.getElementById('fa_state_id')?.addEventListener('change', function () {
        filterSelect(document.getElementById('fa_distric_id'), allDistrictOpts, 'state',    this.value, '');
        filterSelect(document.getElementById('fa_tahsil_id'),  allTahsilOpts,   'district', '',         '');
        filterSelect(document.getElementById('fa_village_id'), allVillageOpts,  'tahsil',   '',         '');
    });

    document.getElementById('fa_distric_id')?.addEventListener('change', function () {
        filterSelect(document.getElementById('fa_tahsil_id'),  allTahsilOpts,  'district', this.value, '');
        filterSelect(document.getElementById('fa_village_id'), allVillageOpts, 'tahsil',   '',         '');
    });

    document.getElementById('fa_tahsil_id')?.addEventListener('change', function () {
        filterSelect(document.getElementById('fa_village_id'), allVillageOpts, 'tahsil', this.value, '');
    });

    // ── Land dropdown options ─────────────────────────────────
    const allLandDistOpts  = Array.from(document.querySelectorAll('#land_DictrictId option'));
    const allLandTahsOpts  = Array.from(document.querySelectorAll('#land_TahsilId option'));
    const allLandVillOpts  = Array.from(document.querySelectorAll('#land_VillageId option'));

    document.getElementById('land_StateId')?.addEventListener('change', function () {
        filterSelect(document.getElementById('land_DictrictId'), allLandDistOpts, 'state',    this.value, '');
        filterSelect(document.getElementById('land_TahsilId'),   allLandTahsOpts, 'district', '',         '');
        filterSelect(document.getElementById('land_VillageId'),  allLandVillOpts, 'tahsil',   '',         '');
    });
    document.getElementById('land_DictrictId')?.addEventListener('change', function () {
        filterSelect(document.getElementById('land_TahsilId'),  allLandTahsOpts, 'district', this.value, '');
        filterSelect(document.getElementById('land_VillageId'), allLandVillOpts, 'tahsil',   '',         '');
    });
    document.getElementById('land_TahsilId')?.addEventListener('change', function () {
        filterSelect(document.getElementById('land_VillageId'), allLandVillOpts, 'tahsil', this.value, '');
    });

    // ── Load land entries ─────────────────────────────────────
    let currentFid = null;

    function loadLandEntries(fid, tableBodyId) {
        const tbody = document.getElementById(tableBodyId);
        if (!tbody) return;
        tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-2"><i class="ri-loader-4-line me-1"></i>Loading…</td></tr>';

        fetch(`/master/farmers/${fid}/land`, {
            headers: { 'Accept': 'application/json',
                       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => {
            if (tableBodyId === 'landTableBody') {
                document.getElementById('landCount').textContent = data.length;
            }
            if (!data.length) {
                const cols = tableBodyId === 'landTableBody' ? 9 : 8;
                tbody.innerHTML = `<tr><td colspan="${cols}" class="text-center text-muted py-3">No land entries yet.</td></tr>`;
                return;
            }
            tbody.innerHTML = data.map((l, i) => `
                <tr>
                    <td class="text-muted">${i+1}</td>
                    <td>${l.state   || '-'}</td>
                    <td>${l.district|| '-'}</td>
                    <td>${l.tahsil  || '-'}</td>
                    <td>${l.village || '-'}</td>
                    <td><strong>${l.land_area || '-'}</strong></td>
                    <td>${l.khasra_no || '-'}</td>
                    <td>${l.plot_no   || '-'}</td>
                    ${tableBodyId === 'landTableBody'
                        ? `<td><button class="btn btn-sm btn-outline-danger deleteLandBtn" data-id="${l.flandid}" title="Delete"><i class="ri-delete-bin-line"></i></button></td>`
                        : ''}
                </tr>`).join('');

            // Bind delete buttons
            if (tableBodyId === 'landTableBody') {
                tbody.querySelectorAll('.deleteLandBtn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        if (!confirm('Delete this land entry?')) return;
                        const id = this.dataset.id;
                        fetch(`/master/farmers/land/${id}`, {
                            method : 'DELETE',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                       'Accept': 'application/json' }
                        })
                        .then(r => r.json())
                        .then(() => loadLandEntries(currentFid, 'landTableBody'))
                        .catch(() => alert('Delete failed.'));
                    });
                });
            }
        })
        .catch(() => {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center text-danger py-2">Failed to load.</td></tr>';
        });
    }

    // ── Add land entry ────────────────────────────────────────
    document.getElementById('addLandEntryBtn')?.addEventListener('click', function () {
        const saveMsg = document.getElementById('landSaveMsg');
        const errMsg  = document.getElementById('landErrMsg');
        saveMsg.style.display = 'none';
        errMsg.style.display  = 'none';

        const payload = {
            StateId    : document.getElementById('land_StateId').value,
            DictrictId : document.getElementById('land_DictrictId').value,
            TahsilId   : document.getElementById('land_TahsilId').value,
            VillageId  : document.getElementById('land_VillageId').value,
            land_area  : document.getElementById('land_area').value,
            khasra_no  : document.getElementById('land_khasra_no').value,
            plot_no    : document.getElementById('land_plot_no').value,
            _token     : document.querySelector('meta[name="csrf-token"]').content,
        };

        // Client-side validation
        if (!payload.StateId || !payload.DictrictId || !payload.TahsilId ||
            !payload.VillageId || !payload.land_area || !payload.khasra_no) {
            errMsg.textContent = 'Please fill all required fields (State, District, Tahsil, Village, Area, Khasra No).';
            errMsg.style.display = 'inline';
            return;
        }

        this.disabled = true;
        this.innerHTML = '<i class="ri-loader-4-line me-1"></i>Saving…';

        fetch(`/master/farmers/${currentFid}/land`, {
            method : 'POST',
            headers: { 'Content-Type': 'application/json',
                       'X-CSRF-TOKEN': payload._token,
                       'Accept'      : 'application/json' },
            body   : JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                // Reset land form
                document.getElementById('land_StateId').value    = '';
                document.getElementById('land_DictrictId').value = '';
                document.getElementById('land_TahsilId').value   = '';
                document.getElementById('land_VillageId').value  = '';
                document.getElementById('land_area').value       = '';
                document.getElementById('land_khasra_no').value  = '';
                document.getElementById('land_plot_no').value    = '';
                filterSelect(document.getElementById('land_DictrictId'), allLandDistOpts, 'state',    '', '');
                filterSelect(document.getElementById('land_TahsilId'),   allLandTahsOpts, 'district', '', '');
                filterSelect(document.getElementById('land_VillageId'),  allLandVillOpts, 'tahsil',   '', '');

                saveMsg.style.display = 'inline';
                setTimeout(() => saveMsg.style.display = 'none', 3000);
                loadLandEntries(currentFid, 'landTableBody');
            } else {
                errMsg.textContent = res.message || 'Save failed.';
                errMsg.style.display = 'inline';
            }
        })
        .catch(() => {
            errMsg.textContent = 'Server error. Please try again.';
            errMsg.style.display = 'inline';
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '<i class="ri-add-line me-1"></i>Add Entry';
        });
    });

    function resetToFirstTab() {
        document.querySelector('#farmerTabs .nav-link.active')?.classList.remove('active');
        document.querySelector('#farmerTabs .nav-link[data-bs-target="#f_basic"]').classList.add('active');
        document.querySelectorAll('#farmerModal .tab-pane').forEach(p => p.classList.remove('show','active'));
        document.getElementById('f_basic').classList.add('show','active');
    }

    function setVal(form, name, val) {
        const el = form.querySelector('[name="' + name + '"]');
        if (el) el.value = val ?? '';
    }

    function openAdd() {
        const form = document.getElementById('farmerForm');
        form.reset();
        form.action = '{{ route("master.farmers.store") }}';
        document.getElementById('farmerModalLabel').innerHTML = '<i class="ri-add-line me-1"></i>New Farmer';
        document.getElementById('farmerSubmitBtn').innerHTML  = '<i class="ri-save-line me-1"></i>Save Farmer';
        const m = form.querySelector('input[name="_method"]');
        if (m) m.remove();
        resetToFirstTab();
        modal.show();
    }

    document.getElementById('addFarmerBtn')?.addEventListener('click', openAdd);
    document.getElementById('addFirstFarmerBtn')?.addEventListener('click', openAdd);

    // ── View ──────────────────────────────────────────────────
    document.querySelectorAll('.viewFarmerBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const d   = this.dataset;
            const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val || '-'; };
            set('v_tem_fid',       d.tem_fid);
            set('v_fname',         d.fname);
            set('v_father_name',   d.father_name);
            set('v_father_name',   d.father_name);
            set('v_father_contact',d.father_contact);
            set('v_contact_1',     d.contact_1);
            set('v_contact_2',     d.contact_2);
            set('v_email',         d.email);
            set('v_dob',           d.dob);
            set('v_age',           d.age);
            set('v_address',       d.address);
            set('v_pincode',       d.pincode);
            set('v_aadhar_no',     d.aadhar_no);
            set('v_pan_no',        d.pan_no);
            set('v_total_land',    d.total_land ? d.total_land + ' ac' : '');
            set('v_cr_date',       d.cr_date);
            set('v_bank_name',     d.bank_name);
            set('v_account_no',    d.account_no);
            set('v_branch_name',   d.branch_name);
            set('v_ifsc_code',     d.ifsc_code);

            // reset to first tab
            const tabs = document.querySelectorAll('#viewFarmerTabs .nav-link');
            tabs.forEach(t => t.classList.remove('active'));
            tabs[0]?.classList.add('active');
            document.querySelectorAll('#viewFarmerModal .tab-pane').forEach(p => p.classList.remove('show','active'));
            document.getElementById('vf_basic')?.classList.add('show','active');

            // Load land entries for view modal when land tab clicked
            const viewLandTabBtn = document.querySelector('[data-bs-target="#vf_land"]');
            if (viewLandTabBtn) {
                viewLandTabBtn._viewFid = d.id;
                viewLandTabBtn.addEventListener('shown.bs.tab', function () {
                    loadLandEntries(this._viewFid, 'viewLandTableBody');
                }, { once: false });
            }

            bootstrap.Modal.getOrCreateInstance(document.getElementById('viewFarmerModal')).show();
        });
    });

    // ── Edit ──────────────────────────────────────────────────
    document.querySelectorAll('.editFarmerBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const d    = this.dataset;
            const form = document.getElementById('farmerForm');

            form.action = '{{ url("master/farmers") }}/' + d.id;
            document.getElementById('farmerModalLabel').innerHTML = '<i class="ri-edit-line me-1"></i>Edit Farmer';
            document.getElementById('farmerSubmitBtn').innerHTML  = '<i class="ri-save-line me-1"></i>Update Farmer';

            // ── Land tab: show edit section, load entries ─────────
            currentFid = d.id;
            document.getElementById('landEditSection').classList.remove('d-none');
            document.getElementById('landNewSection').classList.add('d-none');
            loadLandEntries(currentFid, 'landTableBody');

            // Basic
            setVal(form, 'tem_fid',       d.tem_fid);
            setVal(form, 'fname',          d.fname);
            setVal(form, 'father_name',    d.father_name);
            setVal(form, 'father_contact', d.father_contact);
            setVal(form, 'oid',             d.oid);
            setVal(form, 'dob',            d.dob);
            setVal(form, 'age',            d.age);
            setVal(form, 'contact_1',      d.contact_1);
            setVal(form, 'contact_2',      d.contact_2);
            setVal(form, 'email',          d.email);
            setVal(form, 'total_land',     d.total_land);
            // Address — set with cascade
            setVal(form, 'address', d.address);
            setVal(form, 'pincode', d.pincode);

            // State → filter districts → set district → filter tahsils → set tahsil → filter villages → set village
            const stateEl   = document.getElementById('fa_state_id');
            const distEl    = document.getElementById('fa_distric_id');
            const tahsilEl  = document.getElementById('fa_tahsil_id');
            const villageEl = document.getElementById('fa_village_id');

            if (stateEl) {
                stateEl.value = d.state_id || '';
                filterSelect(distEl,   allDistrictOpts, 'state',    d.state_id,   d.distric_id);
                filterSelect(tahsilEl, allTahsilOpts,   'district', d.distric_id, d.tahsil_id);
                filterSelect(villageEl,allVillageOpts,  'tahsil',   d.tahsil_id,  d.village_id);
            }
            // Docs
            setVal(form, 'aadhar_no',     d.aadhar_no);
            setVal(form, 'pan_no',        d.pan_no);
            setVal(form, 'idproof_name',  d.idproof_name);
            setVal(form, 'idproof_no',    d.idproof_no);
            setVal(form, 'addproof_name', d.addproof_name);
            setVal(form, 'addproof_no',   d.addproof_no);
            // Bank
            setVal(form, 'bank_name',   d.bank_name);
            setVal(form, 'account_no',  d.account_no);
            setVal(form, 'branch_name', d.branch_name);
            setVal(form, 'ifsc_code',   d.ifsc_code);
            setVal(form, 'bank_add',    d.bank_add);

            // Handle bank document display
            const existingDocDiv = document.getElementById('existingBankDoc');
            const bankDocNameEl = document.getElementById('bankDocName');
            const docPassbookInput = form.querySelector('[name="doc_passbook"]');
            
            if (d.doc_passbook) {
                existingDocDiv.classList.remove('d-none');
                bankDocNameEl.textContent = d.doc_passbook;
                docPassbookInput.value = '';
            } else {
                existingDocDiv.classList.add('d-none');
                bankDocNameEl.textContent = '-';
                docPassbookInput.value = '';
            }

            // _method = PATCH
            let mi = form.querySelector('input[name="_method"]');
            if (!mi) {
                mi = document.createElement('input');
                mi.type = 'hidden';
                mi.name = '_method';
                form.appendChild(mi);
            }
            mi.value = 'PATCH';

            resetToFirstTab();
            modal.show();
        });
    });

});

// Hide page loader when page is fully loaded
window.addEventListener('load', function() {
    const loader = document.getElementById('pageLoader');
    if (loader) {
        loader.style.opacity = '0';
        setTimeout(() => {
            loader.style.display = 'none';
        }, 300);
    }
});
</script>
@endpush

@push('styles')
<style>
    .page-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 0.3s ease-in-out;
    }

    .spinner-container {
        text-align: center;
    }

    .spinner {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loader-text {
        color: #172b4d;
        font-size: 14px;
        font-weight: 500;
        margin: 0;
    }
</style>
@endpush
