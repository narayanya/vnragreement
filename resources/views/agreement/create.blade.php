@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">New Farmer Agreement</h3>
                <p class="mb-0" style="font-size:13px;color:#71809a">Create a new seed production agreement</p>
            </div>
            <a href="{{ route('farmer-agreements.index') }}" class="btn btn-sm btn-outline-secondary">
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

        <form method="POST" action="{{ route('farmer-agreements.store') }}" id="agreementForm">
            @csrf

            {{-- ── Section 1: Parties ───────────────────────────────────── --}}
            <div class="card mb-3">
                <div class="card-header py-2" style="background:#f6f8fb;border-bottom:1px solid #e3e8ef">
                    <h6 class="mb-0 fw-bold" style="color:#172b4d">
                        <i class="ri-team-line me-2 text-primary"></i>Agreement Parties
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        {{-- First Party --}}
                        <div class="col-md-6">
                            <label class="form-label">First Party (Company) <span class="text-danger">*</span></label>
                            <select name="first_party_id" class="form-select @error('first_party_id') is-invalid @enderror" required>
                                <option value="">-- Select Company --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('first_party_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}{{ $company->company_code ? ' ('.$company->company_code.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('first_party_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Farmer --}}
                        <div class="col-md-6">
                            <label class="form-label">Farmer <span class="text-danger">*</span></label>
                            <select name="farmer_id" id="farmer_id" class="form-select @error('farmer_id') is-invalid @enderror" required style="width:100%">
                                <option value="">-- Search or select farmer --</option>
                                @foreach($farmers as $farmer)
                                    <option value="{{ $farmer->fid }}"
                                        data-contact="{{ $farmer->contact_1 }}"
                                        data-oid="{{ $farmer->oid }}"
                                        data-tem_fid="{{ $farmer->tem_fid }}"
                                        {{ old('farmer_id') == $farmer->fid ? 'selected' : '' }}>
                                        {{ $farmer->fname }}{{ $farmer->tem_fid ? ' ('.$farmer->tem_fid.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('farmer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Farmer Info (auto-filled) --}}
                        <div class="col-md-4">
                            <label class="form-label text-muted" style="font-size:12px">Farmer Contact</label>
                            <input type="text" id="farmer_contact" class="form-control form-control-sm bg-light" readonly placeholder="Auto-filled">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted" style="font-size:12px">Farmer ID</label>
                            <input type="text" id="farmer_tem_fid" class="form-control form-control-sm bg-light" readonly placeholder="Auto-filled">
                        </div>

                        {{-- Organiser --}}
                        <div class="col-md-4">
                            <label class="form-label">Organiser <span class="text-danger">*</span></label>
                            <select name="organiser_id" id="organiser_id" class="form-select @error('organiser_id') is-invalid @enderror" required style="width:100%">
                                <option value="">-- Select Organiser --</option>
                                @foreach($organisers as $org)
                                    <option value="{{ $org->oid }}" {{ old('organiser_id') == $org->oid ? 'selected' : '' }}>
                                        {{ $org->oname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('organiser_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- PI/APM/TPM --}}
                        <div class="col-md-6">
                            <label class="form-label">PI / APM / TPM</label>
                            <input type="text" name="pi_apm_tpm" class="form-control"
                                value="{{ old('pi_apm_tpm') }}" maxlength="100"
                                placeholder="Enter PI / APM / TPM name">
                        </div>

                        {{-- Production Executive --}}
                        <div class="col-md-6">
                            <label class="form-label">Production Executive</label>
                            <select name="production_executive" id="production_executive" class="form-select" style="width:100%">
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->emp_name }}" {{ old('production_executive') == $emp->emp_name ? 'selected' : '' }}>
                                        {{ $emp->emp_name }}{{ $emp->emp_code ? ' ('.$emp->emp_code.')' : '' }}
                                        {{ $emp->emp_designation ? ' – '.$emp->emp_designation : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Section 2: Agreement Dates ──────────────────────────── --}}
            <div class="card mb-3">
                <div class="card-header py-2" style="background:#f6f8fb;border-bottom:1px solid #e3e8ef">
                    <h6 class="mb-0 fw-bold" style="color:#172b4d">
                        <i class="ri-calendar-line me-2 text-primary"></i>Agreement Dates
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Agreement Date <span class="text-danger">*</span></label>
                            <input type="date" name="agreement_date"
                                class="form-control @error('agreement_date') is-invalid @enderror"
                                value="{{ old('agreement_date') }}" required>
                            @error('agreement_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Period From <span class="text-danger">*</span></label>
                            <input type="date" name="period_from"
                                class="form-control @error('period_from') is-invalid @enderror"
                                value="{{ old('period_from') }}" required>
                            @error('period_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Period To <span class="text-danger">*</span></label>
                            <input type="date" name="period_to"
                                class="form-control @error('period_to') is-invalid @enderror"
                                value="{{ old('period_to') }}" required>
                            @error('period_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Section 3: Crop & Variety Details ───────────────────── --}}
            <div class="card mb-3">
                <div class="card-header py-2" style="background:#f6f8fb;border-bottom:1px solid #e3e8ef">
                    <h6 class="mb-0 fw-bold" style="color:#172b4d">
                        <i class="ri-plant-line me-2 text-primary"></i>Crop & Variety Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        {{-- Crop --}}
                        <div class="col-md-4">
                            <label class="form-label">Crop <span class="text-danger">*</span></label>
                            <select name="crop_id" id="crop_id" class="form-select @error('crop_id') is-invalid @enderror" required>
                                <option value="">-- Select Crop --</option>
                                @foreach($crops as $crop)
                                    <option value="{{ $crop->id }}"
                                        data-code="{{ $crop->crop_code }}"
                                        {{ old('crop_id') == $crop->id ? 'selected' : '' }}>
                                        {{ $crop->crop_name }}{{ $crop->crop_code ? ' ('.$crop->crop_code.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('crop_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Variety Type --}}
                        <div class="col-md-4">
                            <label class="form-label">Variety Type</label>
                            <select name="variety_type" class="form-select">
                                <option value="">-- Select Type --</option>
                                <option value="HYB" {{ old('variety_type') == 'HYB' ? 'selected' : '' }}>HYB – Hybrid</option>
                                <option value="OPV" {{ old('variety_type') == 'OPV' ? 'selected' : '' }}>OPV – Open Pollinated</option>
                                <option value="CMS" {{ old('variety_type') == 'CMS' ? 'selected' : '' }}>CMS – Cytoplasmic Male Sterile</option>
                                <option value="PAR" {{ old('variety_type') == 'PAR' ? 'selected' : '' }}>PAR – Parental Line</option>
                            </select>
                        </div>

                        {{-- Production Code --}}
                        <div class="col-md-4">
                            <label class="form-label">Production Code</label>
                            <input type="text" name="production_code" class="form-control"
                                value="{{ old('production_code') }}" maxlength="50"
                                placeholder="e.g. PC-2026-001">
                        </div>

                        {{-- Female Variety --}}
                        <div class="col-md-6">
                            <label class="form-label">Female Variety (♀)</label>
                            <select name="female_variety_id" class="form-select">
                                <option value="">-- Select Female Variety --</option>
                                @foreach($varieties as $variety)
                                    <option value="{{ $variety->ver_id }}" {{ old('female_variety_id') == $variety->ver_id ? 'selected' : '' }}>
                                        {{ $variety->ver_alias }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Male Variety --}}
                        <div class="col-md-6">
                            <label class="form-label">Male Variety (♂)</label>
                            <select name="male_variety_id" class="form-select">
                                <option value="">-- Select Male Variety --</option>
                                @foreach($varieties as $variety)
                                    <option value="{{ $variety->ver_id }}" {{ old('male_variety_id') == $variety->ver_id ? 'selected' : '' }}>
                                        {{ $variety->ver_alias }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Section 4: Annexure – Soil & Land ───────────────────── --}}
            <div class="card mb-3">
                <div class="card-header py-2" style="background:#f6f8fb;border-bottom:1px solid #e3e8ef">
                    <h6 class="mb-0 fw-bold" style="color:#172b4d">
                        <i class="ri-map-2-line me-2 text-primary"></i>Annexure – Soil & Land Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Water Availability</label>
                            <select name="water_availability" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="Irrigated"   {{ old('water_availability') == 'Irrigated'   ? 'selected' : '' }}>Irrigated</option>
                                <option value="Rain-fed"    {{ old('water_availability') == 'Rain-fed'    ? 'selected' : '' }}>Rain-fed</option>
                                <option value="Both"        {{ old('water_availability') == 'Both'        ? 'selected' : '' }}>Both</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Topography</label>
                            <select name="topography" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="Plain"   {{ old('topography') == 'Plain'   ? 'selected' : '' }}>Plain</option>
                                <option value="Undulating" {{ old('topography') == 'Undulating' ? 'selected' : '' }}>Undulating</option>
                                <option value="Hilly"   {{ old('topography') == 'Hilly'   ? 'selected' : '' }}>Hilly</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Land Type</label>
                            <select name="land_type" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="Agricultural" {{ old('land_type') == 'Agricultural' ? 'selected' : '' }}>Agricultural</option>
                                <option value="Horticultural" {{ old('land_type') == 'Horticultural' ? 'selected' : '' }}>Horticultural</option>
                                <option value="Waste Land" {{ old('land_type') == 'Waste Land' ? 'selected' : '' }}>Waste Land</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Soil Type</label>
                            <select name="soil_type" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="Black Cotton" {{ old('soil_type') == 'Black Cotton' ? 'selected' : '' }}>Black Cotton</option>
                                <option value="Red"         {{ old('soil_type') == 'Red'         ? 'selected' : '' }}>Red</option>
                                <option value="Alluvial"    {{ old('soil_type') == 'Alluvial'    ? 'selected' : '' }}>Alluvial</option>
                                <option value="Sandy Loam"  {{ old('soil_type') == 'Sandy Loam'  ? 'selected' : '' }}>Sandy Loam</option>
                                <option value="Clay Loam"   {{ old('soil_type') == 'Clay Loam'   ? 'selected' : '' }}>Clay Loam</option>
                                <option value="Laterite"    {{ old('soil_type') == 'Laterite'    ? 'selected' : '' }}>Laterite</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Extent of Cultivability</label>
                            <input type="text" name="extent_of_cultivability" class="form-control"
                                value="{{ old('extent_of_cultivability') }}" maxlength="50"
                                placeholder="e.g. 5.000 Acres">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">QC %</label>
                            <div class="input-group">
                                <input type="number" name="qc_percent" class="form-control"
                                    value="{{ old('qc_percent') }}" step="0.01" min="0" max="100"
                                    placeholder="e.g. 98.50">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Section 5: Annexures II–V ────────────────────────────── --}}
            <div class="card mb-3">
                <div class="card-header py-2" style="background:#f6f8fb;border-bottom:1px solid #e3e8ef">
                    <h6 class="mb-0 fw-bold" style="color:#172b4d">
                        <i class="ri-file-list-2-line me-2 text-primary"></i>Annexures – Incentive, Yield & Cost
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        {{-- Incentive Details --}}
                        <div class="col-md-6">
                            <label class="form-label">Incentive Details <small class="text-muted">(Annexure II)</small></label>
                            <textarea name="incentive_details" class="form-control" rows="3"
                                placeholder="Describe incentive terms…">{{ old('incentive_details') }}</textarea>
                        </div>

                        {{-- Additional Details --}}
                        <div class="col-md-6">
                            <label class="form-label">Additional Details <small class="text-muted">(Annexure III)</small></label>
                            <textarea name="additional_details" class="form-control" rows="3"
                                placeholder="Any additional agreement terms…">{{ old('additional_details') }}</textarea>
                        </div>

                        {{-- Estimated Yield --}}
                        <div class="col-md-4">
                            <label class="form-label">Estimated Yield (Qtl) <small class="text-muted">(Annexure IV)</small></label>
                            <div class="input-group">
                                <input type="number" name="estimated_yield" class="form-control"
                                    value="{{ old('estimated_yield') }}" step="0.001" min="0"
                                    placeholder="e.g. 12.500">
                                <span class="input-group-text">Qtl</span>
                            </div>
                        </div>

                        {{-- Loss of Yield --}}
                        <div class="col-md-4">
                            <label class="form-label">Loss of Yield (Qtl) <small class="text-muted">(Annexure IV-A)</small></label>
                            <div class="input-group">
                                <input type="number" name="loss_of_yield" class="form-control"
                                    value="{{ old('loss_of_yield') }}" step="0.001" min="0"
                                    placeholder="e.g. 0.500">
                                <span class="input-group-text">Qtl</span>
                            </div>
                        </div>

                        {{-- Cost of FS Seed --}}
                        <div class="col-md-4">
                            <label class="form-label">Cost of FS Seed (₹) <small class="text-muted">(Annexure V)</small></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="cost_of_fs_seed" class="form-control"
                                    value="{{ old('cost_of_fs_seed') }}" step="0.01" min="0"
                                    placeholder="e.g. 1500.00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Footer Actions ───────────────────────────────────────── --}}
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('farmer-agreements.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line me-1"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="ri-save-line me-1"></i>Create Agreement
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
    /* Match Select2 height and border to Bootstrap form-select */
    .select2-container--default .select2-selection--single {
        height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        border: 1px solid #ced4da;
        border-radius: .375rem;
        font-size: 1rem;
        line-height: 1.5;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        color: #212529;
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        top: 0;
        right: 6px;
    }
    .select2-container--default .select2-selection--single.is-invalid,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
        outline: 0;
    }
    .select2-dropdown {
        border: 1px solid #ced4da;
        border-radius: .375rem;
        font-size: .9rem;
    }
    .select2-search--dropdown .select2-search__field {
        border: 1px solid #ced4da;
        border-radius: .25rem;
        padding: .3rem .5rem;
    }
    .select2-results__option--highlighted {
        background-color: #0d6efd !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Select2 for Farmer ────────────────────────────────────
    $('#farmer_id').select2({
        placeholder: 'Search farmer by name or ID…',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#farmer_id').closest('.col-md-6'),
    });

    // ── Select2 for Organiser ─────────────────────────────────
    $('#organiser_id').select2({
        placeholder: 'Search organiser by name…',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#organiser_id').closest('.col-md-4'),
    });

    // ── Select2 for Production Executive ─────────────────────
    $('#production_executive').select2({
        placeholder: 'Search employee by name or code…',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#production_executive').closest('.col-md-6'),
    });

    // Auto-fill contact & temp-ID when farmer is chosen
    $('#farmer_id').on('change', function () {
        const opt = this.options[this.selectedIndex];
        document.getElementById('farmer_contact').value = opt?.dataset?.contact  || '';
        document.getElementById('farmer_tem_fid').value = opt?.dataset?.tem_fid  || '';

        // Auto-select organiser from farmer's oid if organiser not yet set
        const oid = opt?.dataset?.oid;
        const organiserEl = document.getElementById('organiser_id');
        if (oid && organiserEl && !organiserEl.value) {
            $('#organiser_id').val(oid).trigger('change');
        }
    });

    // Trigger on load if old() value is present (after validation failure)
    if ($('#farmer_id').val()) {
        $('#farmer_id').trigger('change');
    }

    // Period validation: period_to must be >= period_from
    const periodFrom = document.querySelector('[name="period_from"]');
    const periodTo   = document.querySelector('[name="period_to"]');

    function validatePeriod() {
        if (periodFrom.value && periodTo.value && periodTo.value < periodFrom.value) {
            periodTo.setCustomValidity('Period To must be on or after Period From.');
        } else {
            periodTo.setCustomValidity('');
        }
    }
    periodFrom?.addEventListener('change', validatePeriod);
    periodTo?.addEventListener('change', validatePeriod);

});
</script>
@endpush
