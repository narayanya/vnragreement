@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">Crop Master</h3>
                <p class="mb-0 text-muted" style="font-size:13px">View and manage crop master data</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="ri-upload-line me-1"></i>Import
                </button>
                <button class="btn btn-sm btn-primary" id="addNewCropBtn">
                    <i class="ri-add-line me-1"></i>New Crop
                </button>
            </div>
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
                <form method="GET" action="{{ route('crops.index') }}">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Search by crop name, code or scientific name…"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-filter-line me-1"></i>Filter
                            </button>
                            @if(request('search'))
                                <a href="{{ route('crops.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="ri-close-line me-1"></i>Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        @if($crops->count())
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                                <tr>
                                    <th class="ps-3">Name</th>
                                    <th>Code</th>
                                    <th>Effective Date</th>
                                    <th>Scientific Name</th>
                                    <th>Common Name</th>
                                    <th>Crop Type</th>
                                    <th>Season</th>
                                    <th>Status</th>
                                    <th>Update Status</th>
                                    <th>Updated</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:13px">
                                @foreach($crops as $crop)
                                <tr>
                                    <td class="ps-3 fw-semibold" style="color:#172b4d">
                                        {{ $crop->crop_name_elias ?? $crop->crop_name }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-white">
                                            {{ $crop->crop_code_elias ?? $crop->crop_code ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        {{ $crop->effective_date ? \Carbon\Carbon::parse($crop->effective_date)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td class="text-muted fst-italic">{{ $crop->scientific_name ?? '-' }}</td>
                                    <td class="text-muted">{{ $crop->common_name ?? '-' }}</td>
                                    <td>
                                        @if($crop->cropType)
                                            <span class="badge bg-primary">{{ $crop->cropType->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($crop->season)
                                            <span class="badge bg-secondary">{{ $crop->season->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($crop->is_active == '1')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($crop->update_status == '1')
                                            <span class="badge bg-success">Activated</span>
                                        @else
                                            <span class="badge bg-danger">Deactivated</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        {{ $crop->updated_at ? \Carbon\Carbon::parse($crop->updated_at)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td class="text-end pe-3">
                                        {{-- View --}}
                                        <button class="btn btn-sm btn-outline-info viewCropBtn"
                                            data-name="{{ $crop->crop_name }}"
                                            data-code="{{ $crop->crop_code }}"
                                            data-scientific="{{ $crop->scientific_name }}"
                                            data-common="{{ $crop->common_name }}"
                                            data-type="{{ $crop->cropType->name ?? '' }}"
                                            data-season="{{ $crop->season->name ?? '' }}"
                                            data-description="{{ $crop->description }}"
                                            data-vertical_id="{{ $crop->vertical_id }}"
                                            data-numeric_code="{{ $crop->numeric_code }}"
                                            data-effective_date="{{ $crop->effective_date }}"
                                            data-crop_flag="{{ $crop->crop_flag }}"
                                            data-focus_code="{{ $crop->focus_code }}"
                                            data-family="{{ $crop->family_name }}"
                                            data-genus="{{ $crop->genus }}"
                                            data-species="{{ $crop->species }}"
                                            data-duration="{{ $crop->duration_days }}"
                                            data-sowing="{{ $crop->sowing_time }}"
                                            data-harvest="{{ $crop->harvest_time }}"
                                            data-climate="{{ $crop->climate_requirement }}"
                                            data-soil="{{ $crop->soilType->name ?? '' }}"
                                            data-isolation="{{ $crop->isolation_distance }}"
                                            data-yield="{{ $crop->expected_yield }}"
                                            data-update_status="{{ $crop->update_status }}"
                                            title="View Details">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        {{-- Edit --}}
                                        <button class="btn btn-sm btn-outline-warning editCropBtn"
                                            data-id="{{ $crop->id }}"
                                            data-name="{{ $crop->crop_name }}"
                                            data-code="{{ $crop->crop_code }}"
                                            data-elias-name="{{ $crop->crop_name_elias }}"
                                            data-elias-code="{{ $crop->crop_code_elias }}"
                                            data-is_active="{{ $crop->is_active }}"
                                            data-scientific="{{ $crop->scientific_name }}"
                                            data-common="{{ $crop->common_name }}"
                                            data-type="{{ $crop->crop_type_id }}"
                                            data-season="{{ $crop->season_id }}"
                                            data-description="{{ $crop->description }}"
                                            data-vertical_id="{{ $crop->vertical_id }}"
                                            data-numeric_code="{{ $crop->numeric_code }}"
                                            data-effective_date="{{ $crop->effective_date }}"
                                            data-crop_flag="{{ $crop->crop_flag }}"
                                            data-focus_code="{{ $crop->focus_code }}"
                                            data-soil="{{ $crop->soil_type_id }}"
                                            data-family="{{ $crop->family_name }}"
                                            data-genus="{{ $crop->genus }}"
                                            data-species="{{ $crop->species }}"
                                            data-duration="{{ $crop->duration_days }}"
                                            data-sowing="{{ $crop->sowing_time }}"
                                            data-harvest="{{ $crop->harvest_time }}"
                                            data-climate="{{ $crop->climate_requirement }}"
                                            data-isolation="{{ $crop->isolation_distance }}"
                                            data-yield="{{ $crop->expected_yield }}"
                                            data-start_month="{{ $crop->season_start_month_id ?? '' }}"
                                            data-end_month="{{ $crop->season_end_month_id ?? '' }}"
                                            data-update_status="{{ $crop->update_status }}"
                                            title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        {{-- Delete --}}
                                        <form action="{{ route('crops.destroy', $crop->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this crop?')">
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
                @if($crops->hasPages())
                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
                    <small class="text-muted">
                        Showing {{ $crops->firstItem() }} to {{ $crops->lastItem() }} of {{ $crops->total() }} results
                    </small>
                    {{ $crops->links() }}
                </div>
                @endif
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-plant-line" style="font-size:40px;color:#c8d6e5"></i>
                    <p class="text-muted mt-2 mb-3">No crops found.</p>
                    <button class="btn btn-sm btn-primary" id="addFirstCropBtn">
                        <i class="ri-add-line me-1"></i>Add First Crop
                    </button>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@section('modals')

{{-- ── Import Modal ──────────────────────────────────────────── --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="ri-upload-line me-1"></i>Import Crop Master
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-0">Import functionality coming soon…</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ── View Crop Modal ───────────────────────────────────────── --}}
<div class="modal fade" id="viewCropModal" tabindex="-1" aria-labelledby="viewCropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCropModalLabel">
                    <i class="ri-eye-line me-1"></i>Crop Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-fill mb-3" id="viewCropTabs">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#v_basic">
                            <i class="ri-information-line me-1"></i>Basic
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#v_core">
                            <i class="ri-database-line me-1"></i>Core
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#v_classification">
                            <i class="ri-layout-grid-line me-1"></i>Classification
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#v_agronomy">
                            <i class="ri-seedling-line me-1"></i>Agronomy
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="v_basic">
                        <table class="table table-sm table-bordered table-striped">
                            <tr><th style="width:35%">Name</th><td id="c_name">-</td></tr>
                            <tr><th>Code</th><td id="c_code">-</td></tr>
                            <tr><th>Scientific Name</th><td id="c_scientific">-</td></tr>
                            <tr><th>Common Name</th><td id="c_common">-</td></tr>
                            <tr><th>Crop Type</th><td id="c_type">-</td></tr>
                            <tr><th>Description</th><td id="c_description">-</td></tr>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="v_core">
                        <table class="table table-sm table-bordered table-striped">
                            <tr><th style="width:35%">Vertical ID</th><td id="c_vertical_id">-</td></tr>
                            <tr><th>Numeric Code</th><td id="c_numeric_code">-</td></tr>
                            <tr><th>Effective Date</th><td id="c_effective_date">-</td></tr>
                            <tr><th>Crop Flag</th><td id="c_crop_flag">-</td></tr>
                            <tr><th>Focus Code</th><td id="c_focus_code">-</td></tr>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="v_classification">
                        <table class="table table-sm table-bordered table-striped">
                            <tr><th style="width:35%">Family</th><td id="c_family">-</td></tr>
                            <tr><th>Genus</th><td id="c_genus">-</td></tr>
                            <tr><th>Species</th><td id="c_species">-</td></tr>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="v_agronomy">
                        <table class="table table-sm table-bordered table-striped">
                            <tr><th style="width:35%">Season</th><td id="c_season">-</td></tr>
                            <tr><th>Duration (Days)</th><td id="c_duration">-</td></tr>
                            <tr><th>Sowing Time</th><td id="c_sowing">-</td></tr>
                            <tr><th>Harvest Time</th><td id="c_harvest">-</td></tr>
                            <tr><th>Climate Requirement</th><td id="c_climate">-</td></tr>
                            <tr><th>Soil Type</th><td id="c_soil">-</td></tr>
                            <tr><th>Isolation Distance</th><td id="c_isolation">-</td></tr>
                            <tr><th>Expected Yield</th><td id="c_yield">-</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ── Add / Edit Crop Modal ─────────────────────────────────── --}}
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">
                    <i class="ri-add-line me-1"></i>New Crop
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="cropForm" method="POST" action="{{ route('crops.store') }}">
                @csrf
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="cropFormTabs">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#basic">
                                <i class="ri-information-line me-1"></i>Basic
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#core">
                                <i class="ri-database-line me-1"></i>Core
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#classification">
                                <i class="ri-layout-grid-line me-1"></i>Classification
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#agronomy">
                                <i class="ri-seedling-line me-1"></i>Agronomy
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#seed">
                                <i class="ri-plant-line me-1"></i>Seed
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- Basic --}}
                        <div class="tab-pane fade show active" id="basic">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Crop Name <span class="text-danger">*</span></label>
                                    <input type="text" name="crop_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Crop Code <span class="text-danger">*</span></label>
                                    <input type="text" name="crop_code" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Crop Name (Elias)</label>
                                    <input type="text" name="crop_name_elias" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Code (Elias)</label>
                                    <input type="text" name="crop_code_elias" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Scientific Name</label>
                                    <input type="text" name="scientific_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Common Name</label>
                                    <input type="text" name="common_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Crop Type <span class="text-danger">*</span></label>
                                    <select name="crop_type_id" class="form-select" required>
                                        <option value="">Select type…</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="is_active" class="form-select" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Core --}}
                        <div class="tab-pane fade" id="core">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Vertical ID</label>
                                    <input type="text" name="vertical_id" class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Numeric Code</label>
                                    <input type="text" name="numeric_code" class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Effective Date</label>
                                    <input type="text" name="effective_date" class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Crop Flag</label>
                                    <input type="text" name="crop_flag" class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Focus Code</label>
                                    <input type="text" name="focus_code" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- Classification --}}
                        <div class="tab-pane fade" id="classification">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Family Name</label>
                                    <input type="text" name="family_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Genus</label>
                                    <input type="text" name="genus" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Species</label>
                                    <input type="text" name="species" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- Agronomy --}}
                        <div class="tab-pane fade" id="agronomy">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Season <span class="text-danger">*</span></label>
                                    <select name="season_id" id="season_id" class="form-select" required>
                                        <option value="">Select season…</option>
                                        @foreach($seasons as $season)
                                            <option value="{{ $season->id }}"
                                                data-start="{{ $season->start_month }}"
                                                data-end="{{ $season->end_month }}">
                                                {{ $season->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Start Month</label>
                                    <select name="season_start_month_id" id="season_start_month_id" class="form-select">
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}">{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">End Month</label>
                                    <select name="season_end_month_id" id="season_end_month_id" class="form-select">
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}">{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Duration (Days)</label>
                                    <input type="number" name="duration_days" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sowing Time</label>
                                    <input type="text" name="sowing_time" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Harvest Time</label>
                                    <input type="text" name="harvest_time" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Climate Requirement</label>
                                    <input type="text" name="climate_requirement" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Soil Type</label>
                                    <select name="soil_type_id" class="form-select">
                                        <option value="">Select soil type…</option>
                                        @foreach($soiltypes as $soil)
                                            <option value="{{ $soil->id }}">{{ $soil->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Seed --}}
                        <div class="tab-pane fade" id="seed">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Isolation Distance</label>
                                    <input type="number" name="isolation_distance" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Expected Yield</label>
                                    <input type="number" step="0.01" name="expected_yield" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label d-block mb-2">Update Status</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="update_status" id="update_yes" value="1">
                                        <label class="form-check-label" for="update_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="update_status" id="update_no" value="0">
                                        <label class="form-check-label" for="update_no">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="cropSubmitBtn">
                        <i class="ri-save-line me-1"></i>Save Crop
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

    const cropModalEl = document.getElementById('cropModal');
    const cropModal   = bootstrap.Modal.getOrCreateInstance(cropModalEl);

    // ── helpers ──────────────────────────────────────────────────────────
    function resetToBasicTab() {
        document.querySelector('#cropFormTabs .nav-link.active')?.classList.remove('active');
        document.querySelector('#cropFormTabs .nav-link[data-bs-target="#basic"]').classList.add('active');
        document.querySelectorAll('#cropModal .tab-pane').forEach(p => p.classList.remove('show','active'));
        document.getElementById('basic').classList.add('show','active');
    }

    function setVal(form, name, val) {
        const el = form.querySelector('[name="' + name + '"]');
        if (el) el.value = val ?? '';
    }

    // ── View Crop ─────────────────────────────────────────────────────────
    document.querySelectorAll('.viewCropBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const d = this.dataset;
            const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val || '-'; };
            set('c_name',         d.name);
            set('c_code',         d.code);
            set('c_scientific',   d.scientific);
            set('c_common',       d.common);
            set('c_type',         d.type);
            set('c_description',  d.description);
            set('c_vertical_id',  d.vertical_id);
            set('c_numeric_code', d.numeric_code);
            set('c_effective_date', d.effective_date);
            set('c_crop_flag',    d.crop_flag);
            set('c_focus_code',   d.focus_code);
            set('c_family',       d.family);
            set('c_genus',        d.genus);
            set('c_species',      d.species);
            set('c_season',       d.season);
            set('c_duration',     d.duration);
            set('c_sowing',       d.sowing);
            set('c_harvest',      d.harvest);
            set('c_climate',      d.climate);
            set('c_soil',         d.soil);
            set('c_isolation',    d.isolation);
            set('c_yield',        d.yield);

            // reset view modal to first tab
            const viewTabs = document.querySelectorAll('#viewCropTabs .nav-link');
            viewTabs.forEach(t => t.classList.remove('active'));
            viewTabs[0]?.classList.add('active');
            document.querySelectorAll('#viewCropModal .tab-pane').forEach(p => p.classList.remove('show','active'));
            document.getElementById('v_basic')?.classList.add('show','active');

            bootstrap.Modal.getOrCreateInstance(document.getElementById('viewCropModal')).show();
        });
    });

    // ── Edit Crop ─────────────────────────────────────────────────────────
    document.querySelectorAll('.editCropBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const d    = this.dataset;
            const form = document.getElementById('cropForm');

            resetToBasicTab();

            form.action = '{{ url("master/crop") }}/' + d.id;
            document.getElementById('cropModalLabel').innerHTML = '<i class="ri-edit-line me-1"></i>Edit Crop';
            document.getElementById('cropSubmitBtn').innerHTML  = '<i class="ri-save-line me-1"></i>Update Crop';

            // Basic
            setVal(form, 'crop_name',       d.name);
            setVal(form, 'crop_code',       d.code);
            setVal(form, 'crop_name_elias', d.eliasName);
            setVal(form, 'crop_code_elias', d.eliasCode);
            setVal(form, 'scientific_name', d.scientific);
            setVal(form, 'common_name',     d.common);
            setVal(form, 'is_active',       d.is_active);
            setVal(form, 'crop_type_id',    d.type);
            setVal(form, 'description',     d.description);
            // Core
            setVal(form, 'vertical_id',   d.vertical_id);
            setVal(form, 'numeric_code',  d.numeric_code);
            setVal(form, 'effective_date',d.effective_date);
            setVal(form, 'crop_flag',     d.crop_flag);
            setVal(form, 'focus_code',    d.focus_code);
            // Classification
            setVal(form, 'family_name', d.family);
            setVal(form, 'genus',       d.genus);
            setVal(form, 'species',     d.species);
            // Agronomy
            setVal(form, 'season_id',             d.season);
            setVal(form, 'season_start_month_id', d.start_month);
            setVal(form, 'season_end_month_id',   d.end_month);
            setVal(form, 'duration_days',         d.duration);
            setVal(form, 'sowing_time',           d.sowing);
            setVal(form, 'harvest_time',          d.harvest);
            setVal(form, 'climate_requirement',   d.climate);
            setVal(form, 'soil_type_id',          d.soil);
            // Seed
            setVal(form, 'isolation_distance', d.isolation);
            setVal(form, 'expected_yield',     d.yield);
            form.querySelectorAll('[name="update_status"]').forEach(r => {
                r.checked = (r.value === d.update_status);
            });

            // _method = PATCH
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PATCH';

            cropModal.show();
        });
    });

    // ── Add New Crop ──────────────────────────────────────────────────────
    function openAddModal() {
        const form = document.getElementById('cropForm');
        form.reset();
        form.action = '{{ route("crops.store") }}';
        document.getElementById('cropModalLabel').innerHTML = '<i class="ri-add-line me-1"></i>New Crop';
        document.getElementById('cropSubmitBtn').innerHTML  = '<i class="ri-save-line me-1"></i>Save Crop';
        const m = form.querySelector('input[name="_method"]');
        if (m) m.remove();
        resetToBasicTab();
        cropModal.show();
    }

    document.getElementById('addNewCropBtn')?.addEventListener('click', openAddModal);
    document.getElementById('addFirstCropBtn')?.addEventListener('click', openAddModal);

    // ── Season month auto-fill ────────────────────────────────────────────
    const seasonSelect = document.getElementById('season_id');
    if (seasonSelect) {
        seasonSelect.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt.value) {
                document.getElementById('season_start_month_id').value = opt.dataset.start || '';
                document.getElementById('season_end_month_id').value   = opt.dataset.end   || '';
            }
        });
    }

});
</script>
@endpush
