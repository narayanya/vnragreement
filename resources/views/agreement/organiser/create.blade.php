@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-11">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">New Organiser Agreement</h3>
                <p class="mb-0" style="font-size:13px;color:#71809a">
                    Agreement No: <strong class="text-primary">{{ $nextNo }}</strong>
                </p>
            </div>
            <a href="{{ route('organiser-agreements.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="ri-arrow-left-line me-1"></i>Back to List
            </a>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-1"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li style="font-size:13px">{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('organiser-agreements.store') }}" id="orgAgreementForm">
            @csrf

            {{-- ── Section 1: Parties ────────────────────────────────── --}}
            <div class="card mb-3">
                <div class="card-header py-2" style="background:#f6f8fb;border-bottom:1px solid #e3e8ef">
                    <h6 class="mb-0 fw-bold" style="color:#172b4d">
                        <i class="ri-team-line me-2 text-primary"></i>Agreement Parties
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        {{-- First Party --}}
                        <div class="col-md-4">
                            <label class="form-label">First Party <span class="text-danger">*</span></label>
                            <select name="first_party" id="first_party" class="form-select @error('first_party') is-invalid @enderror" required style="width:100%">
                                <option value="">-- Select Company --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('first_party') == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}{{ $company->company_code ? ' ('.$company->company_code.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('first_party')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Second Party (Organiser) --}}
                        <div class="col-md-4">
                            <label class="form-label">Second Party (Organiser) <span class="text-danger">*</span></label>
                            <select name="second_party" id="second_party" class="form-select @error('second_party') is-invalid @enderror" required style="width:100%">
                                <option value="">-- Select Organiser --</option>
                                @foreach($organisers as $org)
                                    <option value="{{ $org->oid }}" {{ old('second_party') == $org->oid ? 'selected' : '' }}>
                                        {{ $org->oname }}{{ $org->tmp_oid ? ' ('.$org->tmp_oid.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('second_party')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- First Party Authority --}}
                        <div class="col-md-4">
                            <label class="form-label">First Party Authority</label>
                            <select name="authorized_signatory" id="authorized_signatory" class="form-select" style="width:100%">
                                <option value="">-- Select Authority --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->employee_id }}" {{ old('authorized_signatory') == $emp->employee_id ? 'selected' : '' }}>
                                        {{ $emp->emp_name }}{{ $emp->emp_designation ? ' – '.$emp->emp_designation : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Second Party Authority --}}
                        <div class="col-md-4">
                            <label class="form-label">Second Party Authority</label>
                            <select name="second_authorized_signatory" id="second_authorized_signatory" class="form-select" style="width:100%">
                                <option value="">-- Select Authority --</option>
                                @foreach($organisers as $org)
                                    <option value="{{ $org->oid }}" {{ old('second_authorized_signatory') == $org->oid ? 'selected' : '' }}>
                                        {{ $org->oname }}{{ $org->tmp_oid ? ' ('.$org->tmp_oid.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Agreement Date --}}
                        <div class="col-md-4">
                            <label class="form-label">Agreement Date <span class="text-danger">*</span></label>
                            <input type="date" name="agree_date"
                                class="form-control @error('agree_date') is-invalid @enderror"
                                value="{{ old('agree_date') }}" required>
                            @error('agree_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Period --}}
                        <div class="col-md-4">
                            <label class="form-label">Period <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="date" name="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ old('start_date') }}" required placeholder="From">
                                <span class="input-group-text">to</span>
                                <input type="date" name="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror"
                                    value="{{ old('end_date') }}" required placeholder="To">
                            </div>
                            @error('start_date')<div class="text-danger" style="font-size:12px">{{ $message }}</div>@enderror
                            @error('end_date')<div class="text-danger" style="font-size:12px">{{ $message }}</div>@enderror
                        </div>

                        {{-- Agreement Location --}}
                        <div class="col-md-4">
                            <label class="form-label">Agreement Location <span class="text-danger">*</span></label>
                            <input type="text" name="agreement_location"
                                class="form-control @error('agreement_location') is-invalid @enderror"
                                value="{{ old('agreement_location') }}" maxlength="255"
                                placeholder="e.g. PORUMAMILLA, KADAPA">
                            @error('agreement_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Section 2: Location, Area & Season ───────────────── --}}
            <div class="card mb-3">
                <div class="card-header py-2" style="background:#f6f8fb;border-bottom:1px solid #e3e8ef">
                    <h6 class="mb-0 fw-bold" style="color:#172b4d">
                        <i class="ri-map-pin-line me-2 text-primary"></i>Location & Production Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        {{-- States --}}
                        <div class="col-md-4">
                            <label class="form-label">States <span class="text-danger">*</span></label>
                            <select name="states" id="states" class="form-select @error('states') is-invalid @enderror" required style="width:100%">
                                <option value="">-- Select State --</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ old('states') == $state->id ? 'selected' : '' }}>
                                        {{ $state->state_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('states')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Districts --}}
                        <div class="col-md-4">
                            <label class="form-label">Districts <span class="text-danger">*</span></label>
                            <select name="districts" id="districts" class="form-select @error('districts') is-invalid @enderror" required style="width:100%">
                                <option value="">-- Select District --</option>
                                @foreach($districts as $dist)
                                    <option value="{{ $dist->id }}"
                                        data-state="{{ $dist->state_id }}"
                                        {{ old('districts') == $dist->id ? 'selected' : '' }}>
                                        {{ $dist->district_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('districts')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Production Area --}}
                        <div class="col-md-4">
                            <label class="form-label">Production Area (Acres) <span class="text-danger">*</span></label>
                            <input type="number" name="production_area" step="0.01" min="0.01"
                                class="form-control @error('production_area') is-invalid @enderror"
                                value="{{ old('production_area') }}" placeholder="e.g. 50.00">
                            @error('production_area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Last Date of Sowing --}}
                        <div class="col-md-4">
                            <label class="form-label">Last Date of Sowing <span class="text-danger">*</span></label>
                            <input type="date" name="last_date_of_sowing"
                                class="form-control @error('last_date_of_sowing') is-invalid @enderror"
                                value="{{ old('last_date_of_sowing') }}" required>
                            @error('last_date_of_sowing')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Season --}}
                        <div class="col-md-4">
                            <label class="form-label d-block">Season <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="season[]"
                                        value="Kharif" id="season_kharif"
                                        {{ in_array('Kharif', (array) old('season', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="season_kharif">Kharif</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="season[]"
                                        value="Rabi" id="season_rabi"
                                        {{ in_array('Rabi', (array) old('season', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="season_rabi">Rabi</label>
                                </div>
                            </div>
                            @error('season')<div class="text-danger" style="font-size:12px">{{ $message }}</div>@enderror
                        </div>

                        {{-- Production Region --}}
                        <div class="col-md-4">
                            <label class="form-label">Production Region <span class="text-danger">*</span></label>
                            <input type="text" name="production_region"
                                class="form-control @error('production_region') is-invalid @enderror"
                                value="{{ old('production_region') }}" maxlength="255"
                                placeholder="e.g. KADAPA-A.P">
                            @error('production_region')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Section 3: Annexures ──────────────────────────────── --}}
            <div class="card mb-3">
                <div class="card-header py-0" style="background:#d0e8f5;border-bottom:1px solid #b8d4e8">
                    <div class="text-center py-2 fw-bold" style="color:#172b4d;font-size:13px;letter-spacing:.3px">
                        Annexure
                    </div>
                    {{-- Tab nav --}}
                    <ul class="nav nav-tabs card-header-tabs border-bottom-0" id="annexureTabs" style="font-size:12px">
                        <li class="nav-item">
                            <button type="button" class="nav-link active px-3 py-2 fw-semibold" data-bs-toggle="tab" data-bs-target="#ann1"
                                style="color:#1565c0">
                                Annexure – I<br><small>Details</small>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link px-3 py-2 fw-semibold" data-bs-toggle="tab" data-bs-target="#ann1a"
                                style="color:#1565c0">
                                Annexure – IA<br>
                                <small>Payment for Detasselling and additional input cost for Hybrid Maize Seed Production</small>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link px-3 py-2 fw-semibold" data-bs-toggle="tab" data-bs-target="#ann3"
                                style="color:#1565c0">
                                Annexure – III<br><small>List of contracted growers</small>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link px-3 py-2 fw-semibold" data-bs-toggle="tab" data-bs-target="#ann4"
                                style="color:#1565c0">
                                Annexure – IV<br><small>Details of parent material/foundation seed</small>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link px-3 py-2 fw-semibold" data-bs-toggle="tab" data-bs-target="#ann5"
                                style="color:#1565c0">
                                Annexure – V<br><small>Health, Safety, and Environment Policy</small>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-0">
                    <div class="tab-content">

                        {{-- ── Annexure I: Crop Details ──────────────── --}}
                        <div class="tab-pane fade show active p-3" id="ann1">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0" id="cropTable" style="font-size:13px">
                                    <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.4px">
                                        <tr>
                                            <th style="min-width:160px">Crop <span class="text-danger">*</span></th>
                                            <th style="min-width:160px">Hybrid/Variety/OP</th>
                                            <th style="min-width:130px">FS/Production Code</th>
                                            <th style="min-width:120px">Grower Price (₹/kg)</th>
                                            <th style="min-width:140px">Quality-based Incentive (₹/kg)</th>
                                            <th style="min-width:140px">Organizer Commission</th>
                                            <th style="min-width:120px">Advance if any (₹)</th>
                                            <th style="width:50px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cropTableBody">
                                        <tr class="crop-row">
                                            <td>
                                                <select name="crop_id[]" class="form-select form-select-sm crop-select" style="min-width:150px">
                                                    <option value="">-- Crop --</option>
                                                    @foreach($crops as $crop)
                                                        <option value="{{ $crop->id }}">{{ $crop->crop_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="crop_category[]" class="form-select form-select-sm variety-select" style="min-width:150px">
                                                    <option value="">-- Variety --</option>
                                                    @foreach($varieties as $v)
                                                        <option value="{{ $v->ver_alias }}">{{ $v->ver_alias }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="fs_code[]" class="form-control form-control-sm" placeholder="e.g. M05"></td>
                                            <td><input type="number" name="growar_price[]" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00"></td>
                                            <td><input type="number" name="quality_based_incentive[]" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00"></td>
                                            <td><input type="number" name="organizer_commission[]" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00"></td>
                                            <td><input type="number" name="advance_payment[]" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Remove"><i class="ri-delete-bin-line"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addCropRow">
                                <i class="ri-add-circle-line me-1"></i>Add Row
                            </button>
                        </div>

                        {{-- ── Annexure IA: Particulars ──────────────── --}}
                        <div class="tab-pane fade p-3" id="ann1a">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0" id="particularTable" style="font-size:13px">
                                    <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.4px">
                                        <tr>
                                            <th style="min-width:220px">Particulars</th>
                                            <th style="min-width:180px">Amount per Acre (inRs.)</th>
                                            <th style="min-width:200px">Remarks</th>
                                            <th style="width:50px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="particularTableBody">
                                        <tr class="particular-row">
                                            <td><input type="text" name="particulars[]" class="form-control form-control-sm" placeholder="e.g. Input cost"></td>
                                            <td><input type="number" name="amount_per_acre[]" class="form-control form-control-sm" step="0.01" min="0" placeholder="use only numbers"></td>
                                            <td><input type="text" name="remarks[]" class="form-control form-control-sm" placeholder="Remarks"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Remove"><i class="ri-delete-bin-line"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addParticularRow">
                                <i class="ri-add-circle-line me-1"></i>Add Row
                            </button>
                        </div>

                        {{-- ── Annexure III: Contracted Growers (auto-filled) ── --}}
                        <div class="tab-pane fade p-3" id="ann3">
                            <div class="alert alert-info mb-3" style="font-size:13px">
                                <i class="ri-information-line me-1"></i>
                                <strong>Note –</strong> This annexure will be automatically filled and shown in the annexure PDF.
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0" style="font-size:13px">
                                    <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.4px">
                                        <tr>
                                            <th>Agreement ID</th>
                                            <th>Crop</th>
                                            <th>Farmer ID</th>
                                            <th>Grower Name</th>
                                            <th>Production Location</th>
                                            <th>Total Standing Area (Acre)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3" style="font-size:12px">
                                                <i class="ri-information-line me-1"></i>
                                                Selected crop growers will appear here after agreement is saved.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ── Annexure IV: Foundation Seed ─────────── --}}
                        <div class="tab-pane fade p-3" id="ann4">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0" id="foundationTable" style="font-size:13px">
                                    <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.4px">
                                        <tr>
                                            <th style="min-width:150px">Crop</th>
                                            <th style="min-width:130px">FS/Production Code</th>
                                            <th style="min-width:150px">FS Seed per acre supplied (M+F) in kg</th>
                                            <th style="min-width:110px">No of Acres</th>
                                            <th style="min-width:140px">Total FS Supplied in kg</th>
                                            <th style="min-width:110px">Price/Acre</th>
                                            <th style="min-width:120px">Total Amount</th>
                                            <th style="width:50px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="foundationTableBody">
                                        <tr class="foundation-row">
                                            <td>
                                                <select name="found_crop_id[]" class="form-select form-select-sm found-crop-select" style="min-width:140px">
                                                    <option value="">-- Crop --</option>
                                                    @foreach($crops as $crop)
                                                        <option value="{{ $crop->id }}">{{ $crop->crop_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="fs_production_code[]" class="form-control form-control-sm" placeholder="e.g. M05"></td>
                                            <td><input type="number" name="fs_seed_mf[]" class="form-control form-control-sm" step="0.001" min="0" placeholder="kg"></td>
                                            <td><input type="number" name="no_of_acres[]" class="form-control form-control-sm acres-input" step="0.01" min="0" placeholder="0.00"></td>
                                            <td><input type="number" name="total_fs[]" class="form-control form-control-sm total-fs-input" step="0.001" min="0" placeholder="auto-calc" readonly style="background:#f8f9fa"></td>
                                            <td><input type="number" name="price[]" class="form-control form-control-sm price-input" step="0.01" min="0" placeholder="0.00"></td>
                                            <td><input type="number" name="total_amount[]" class="form-control form-control-sm total-amount-input" step="0.01" min="0" placeholder="auto-calc" readonly style="background:#f8f9fa"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Remove"><i class="ri-delete-bin-line"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addFoundationRow">
                                <i class="ri-add-circle-line me-1"></i>Add Row
                            </button>
                        </div>

                        {{-- ── Annexure V: HSE Policy ────────────────── --}}
                        <div class="tab-pane fade p-3" id="ann5">
                            <div class="card border-0" style="background:#f8f9fa">
                                <div class="card-body" style="font-size:13px;line-height:1.7;color:#333">
                                    <h6 class="text-center fw-bold mb-3" style="text-transform:uppercase;letter-spacing:.5px">
                                        HEALTH, SAFETY AND ENVIRONMENT POLICY
                                    </h6>
                                    <p>VNR Seeds Private Limited is committed to ensuring the health, safety, and environmental well-being of all personnel, visitors, and the surrounding community involved in agricultural production activities. The Company recognize the importance of maintaining a safe and sustainable working environment and are dedicated to continual improvement in health, safety, and environmental performance in this regard:</p>

                                    <p><strong>1. Legal and Regulatory Compliance</strong><br>
                                    The Company will comply with all relevant local, national, and international laws and regulations pertaining to health, safety, and environmental standards applicable to agricultural operations.</p>

                                    <p><strong>2. Risk Assessment</strong><br>
                                    A comprehensive risk assessment shall be conducted on a regular basis to identify potential hazards associated with agricultural production activities. These will include but not be restricted to the safe application of crop protection products, avoidance of snake bites and electrocution, safe handling of agricultural implements and provisions of personal protective equipment whenever it is essential during the crop production. Based on the assessment, appropriate control measures will be implemented to minimize or eliminate risk.</p>

                                    <p><strong>3. Training and Awareness</strong><br>
                                    All personnel and contractors will receive adequate training and information on health, safety, and environmental procedures. Regular awareness programs shall be conducted to ensure everyone is informed about potential hazards and the necessary preventive measures.</p>

                                    <p><strong>4. Emergency Preparedness and Response</strong><br>
                                    Establish and regularly review emergency response procedures. All personnel shall be trained on emergency protocols, including evacuation procedures, first aid, and the use of emergency equipment.</p>

                                    <p><strong>5. Equipment Maintenance</strong><br>
                                    Regular maintenance and inspection of equipment and machinery used in agricultural production will be conducted to ensure safe operation. Defective equipment shall be promptly repaired or replaced.</p>

                                    <p><strong>6. Waste Management</strong><br>
                                    Implementation of effective waste management practices, including proper disposal of agricultural waste, recycling initiatives, and compliance with relevant waste disposal regulations.</p>

                                    <p><strong>7. Continuous Improvements</strong><br>
                                    Always committed to continuous improvement in health, safety, and environmental performance. Regular reviews and audits will be conducted to identify areas for improvement and ensure the ongoing effectiveness of our policies and procedures.</p>

                                    <p class="mt-3">I, __________, agree to comply with all relevant points of Health, Safety &amp; Environment policy of VNR Seeds Private Limited.</p>
                                    <p class="mb-0"><strong>Date:</strong> __________</p>
                                    <p><strong>Place:</strong> __________</p>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /tab-content --}}
                </div>
            </div>

            {{-- ── Footer Actions ────────────────────────────────────── --}}
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('organiser-agreements.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line me-1"></i>Cancel
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="ri-save-line me-1"></i>Save Agreement
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    /* Bootstrap-matching Select2 */
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        border: 1px solid #ced4da;
        border-radius: .375rem;
        font-size: 1rem;
        line-height: 1.5;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5; color: #212529; padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%; top: 0; right: 6px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
        outline: 0;
    }
    .select2-dropdown { border: 1px solid #ced4da; border-radius: .375rem; font-size: .9rem; }
    .select2-search--dropdown .select2-search__field { border: 1px solid #ced4da; border-radius: .25rem; padding: .3rem .5rem; }
    .select2-results__option--highlighted { background-color: #0d6efd !important; }

    /* Annexure tab header styling */
    #annexureTabs .nav-link { border-radius: 0; font-size: 11px; border-bottom: 3px solid transparent; }
    #annexureTabs .nav-link.active { background: #fff; border-bottom-color: #0d6efd; color: #0d6efd !important; font-weight: 700; }
    #annexureTabs .nav-link:not(.active):hover { background: #e8f0fe; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Select2 inits ─────────────────────────────────────────
    const s2 = (id, placeholder, parent) => {
        $(id).select2({ placeholder, allowClear: true, width: '100%', dropdownParent: $(id).closest(parent) });
    };
    s2('#first_party',                  'Search company…',    '.col-md-4');
    s2('#second_party',                 'Search organiser…',  '.col-md-4');
    s2('#authorized_signatory',         'Search employee…',   '.col-md-4');
    s2('#second_authorized_signatory',  'Search organiser…',  '.col-md-4');
    s2('#states',                       'Select state…',      '.col-md-4');
    s2('#districts',                    'Select district…',   '.col-md-4');

    // ── Cascading State → District ────────────────────────────
    const allDistOpts = @json($districts);

    $('#states').on('change', function () {
        const stateId  = this.value;
        const distSel  = document.getElementById('districts');
        const oldVal   = distSel.value;
        distSel.innerHTML = '<option value="">-- Select District --</option>';
        allDistOpts
            .filter(d => !stateId || String(d.state_id) === String(stateId))
            .forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = d.district_name;
                if (String(d.id) === oldVal) opt.selected = true;
                distSel.appendChild(opt);
            });
        $('#districts').trigger('change.select2');
    });

    // Trigger on load for old() repopulation
    if ($('#states').val()) $('#states').trigger('change');

    // ── Generic remove-row ────────────────────────────────────
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-row');
        if (!btn) return;
        const tbody = btn.closest('tbody');
        if (tbody.querySelectorAll('tr').length <= 1) return; // keep at least 1
        btn.closest('tr').remove();
    });

    // ── Annexure I: Add crop row ──────────────────────────────
    const cropRowTemplate = () => {
        const cropOpts = `<option value="">-- Crop --</option>` +
            @json($crops).map(c => `<option value="${c.id}">${c.crop_name}</option>`).join('');
        const varOpts  = `<option value="">-- Variety --</option>` +
            @json($varieties).map(v => `<option value="${v.ver_alias}">${v.ver_alias}</option>`).join('');
        return `<tr class="crop-row">
            <td><select name="crop_id[]" class="form-select form-select-sm">${cropOpts}</select></td>
            <td><select name="crop_category[]" class="form-select form-select-sm">${varOpts}</select></td>
            <td><input type="text" name="fs_code[]" class="form-control form-control-sm" placeholder="e.g. M05"></td>
            <td><input type="number" name="growar_price[]" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00"></td>
            <td><input type="number" name="quality_based_incentive[]" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00"></td>
            <td><input type="number" name="organizer_commission[]" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00"></td>
            <td><input type="number" name="advance_payment[]" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="ri-delete-bin-line"></i></button></td>
        </tr>`;
    };

    document.getElementById('addCropRow').addEventListener('click', () => {
        document.getElementById('cropTableBody').insertAdjacentHTML('beforeend', cropRowTemplate());
    });

    // ── Annexure IA: Add particular row ──────────────────────
    document.getElementById('addParticularRow').addEventListener('click', () => {
        document.getElementById('particularTableBody').insertAdjacentHTML('beforeend', `
            <tr class="particular-row">
                <td><input type="text" name="particulars[]" class="form-control form-control-sm" placeholder="e.g. Input cost"></td>
                <td><input type="number" name="amount_per_acre[]" class="form-control form-control-sm" step="0.01" min="0" placeholder="use only numbers"></td>
                <td><input type="text" name="remarks[]" class="form-control form-control-sm" placeholder="Remarks"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="ri-delete-bin-line"></i></button></td>
            </tr>`);
    });

    // ── Annexure IV: Add foundation row + auto-calc ───────────
    const foundCropOpts = `<option value="">-- Crop --</option>` +
        @json($crops).map(c => `<option value="${c.id}">${c.crop_name}</option>`).join('');

    document.getElementById('addFoundationRow').addEventListener('click', () => {
        const row = document.createElement('tr');
        row.className = 'foundation-row';
        row.innerHTML = `
            <td><select name="found_crop_id[]" class="form-select form-select-sm found-crop-select">${foundCropOpts}</select></td>
            <td><input type="text" name="fs_production_code[]" class="form-control form-control-sm" placeholder="e.g. M05"></td>
            <td><input type="number" name="fs_seed_mf[]" class="form-control form-control-sm fs-mf-input" step="0.001" min="0" placeholder="kg"></td>
            <td><input type="number" name="no_of_acres[]" class="form-control form-control-sm acres-input" step="0.01" min="0" placeholder="0.00"></td>
            <td><input type="number" name="total_fs[]" class="form-control form-control-sm total-fs-input" step="0.001" readonly style="background:#f8f9fa" placeholder="auto-calc"></td>
            <td><input type="number" name="price[]" class="form-control form-control-sm price-input" step="0.01" min="0" placeholder="0.00"></td>
            <td><input type="number" name="total_amount[]" class="form-control form-control-sm total-amount-input" step="0.01" readonly style="background:#f8f9fa" placeholder="auto-calc"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="ri-delete-bin-line"></i></button></td>`;
        document.getElementById('foundationTableBody').appendChild(row);
        bindFoundationCalc(row);
    });

    // Auto-calc: total_fs = fs_seed_mf × no_of_acres; total_amount = total_fs × price
    function calcFoundationRow(row) {
        const mf    = parseFloat(row.querySelector('.fs-mf-input, [name="fs_seed_mf[]"]')?.value) || 0;
        const acres = parseFloat(row.querySelector('.acres-input')?.value) || 0;
        const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
        const totalFs = mf * acres;
        const totalAmt = totalFs * price;
        const tfEl  = row.querySelector('.total-fs-input');
        const taEl  = row.querySelector('.total-amount-input');
        if (tfEl) tfEl.value = totalFs ? totalFs.toFixed(3) : '';
        if (taEl) taEl.value = totalAmt ? totalAmt.toFixed(2) : '';
    }

    function bindFoundationCalc(row) {
        row.querySelectorAll('.fs-mf-input, [name="fs_seed_mf[]"], .acres-input, .price-input').forEach(inp => {
            inp.addEventListener('input', () => calcFoundationRow(row));
        });
    }

    // Bind existing first row
    document.querySelectorAll('.foundation-row').forEach(bindFoundationCalc);

    // ── Season validation: at least one checked ───────────────
    document.getElementById('orgAgreementForm').addEventListener('submit', function (e) {
        const checked = document.querySelectorAll('input[name="season[]"]:checked');
        if (checked.length === 0) {
            e.preventDefault();
            alert('Please select at least one Season (Kharif / Rabi).');
        }
    });

});
</script>
@endpush
