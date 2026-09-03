@extends('layouts.app')

@section('content')
{{-- Page Loader --}}
<div id="pageLoader" class="page-loader">
    <div class="spinner-container">
        <div class="spinner"></div>
        <p class="loader-text">Loading organisers data...</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">Organiser Master</h3>
                <p class="mb-0 text-muted" style="font-size:13px">View and manage organiser records &mdash; Total: {{ $total }}</p>
            </div>
            <button class="btn btn-sm btn-primary" id="addOrganiserBtn">
                <i class="ri-add-line me-1"></i>New Organiser
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
                <form method="GET" action="{{ route('master.organiser.index') }}">
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Search by name, ID, mobile, email or Aadhar…"
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
                                <a href="{{ route('master.organiser.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="ri-close-line me-1"></i>Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        @if($organisers->count())
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Org ID</th>
                                    <th>Name</th>
                                    <th>Father Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th>Aadhar</th>
                                    <th>Created</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:13px">
                                @foreach($organisers as $org)
                                <tr>
                                    <td class="ps-3 text-muted">
                                        {{ ($organisers->currentPage() - 1) * $organisers->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-white">{{ $org->tmp_oid ?? $org->oid }}</span>
                                    </td>
                                    <td class="fw-semibold" style="color:#172b4d">{{ $org->oname ?? '-' }}</td>
                                    <td class="text-muted">{{ $org->fname ?? '-' }}</td>
                                    <td>
                                        {{ $org->mobile_1 ?? '-' }}
                                        @if($org->mobile_2)
                                            <br><small class="text-muted">{{ $org->mobile_2 }}</small>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        @if($org->email)
                                            <a href="mailto:{{ $org->email }}" class="text-decoration-none">{{ $org->email }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $org->city ?? '-' }}</td>
                                    <td class="text-muted">{{ $org->aadhar_no ?? '-' }}</td>
                                    <td class="text-muted">
                                        {{ $org->cr_date ? \Carbon\Carbon::parse($org->cr_date)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td class="text-end pe-3">
                                        {{-- Edit --}}
                                        <button class="btn btn-sm btn-outline-warning editOrganiserBtn"
                                            data-id="{{ $org->oid }}"
                                            data-tmp_oid="{{ $org->tmp_oid }}"
                                            data-oname="{{ $org->oname }}"
                                            data-fname="{{ $org->fname }}"
                                            data-mobile_1="{{ $org->mobile_1 }}"
                                            data-mobile_2="{{ $org->mobile_2 }}"
                                            data-email="{{ $org->email }}"
                                            data-dob="{{ $org->dob }}"
                                            data-age="{{ $org->age }}"
                                            data-address="{{ $org->address }}"
                                            data-city="{{ $org->city }}"
                                            data-pincode="{{ $org->pincode }}"
                                            data-state_id="{{ $org->state_id }}"
                                            data-district_id="{{ $org->district_id }}"
                                            data-tahsil_id="{{ $org->tahsil_id }}"
                                            data-village_id="{{ $org->village_id }}"
                                            data-aadhar_no="{{ $org->aadhar_no }}"
                                            data-pan_no="{{ $org->pan_no }}"
                                            data-bank_name="{{ $org->bank_name }}"
                                            data-account_no="{{ $org->account_no }}"
                                            data-branch_name="{{ $org->branch_name }}"
                                            data-ifsc_code="{{ $org->ifsc_code }}"
                                            data-authorized_signatory="{{ $org->authorized_signatory }}"
                                            title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info viewOrganiserBtn"
                                            data-oname="{{ $org->oname }}"
                                            data-fname="{{ $org->fname }}"
                                            data-mobile_1="{{ $org->mobile_1 }}"
                                            data-email="{{ $org->email }}"
                                            data-address="{{ $org->address }}"
                                            data-state_id="{{ $org->state_id }}"
                                            data-district_id="{{ $org->district_id }}"
                                            data-tahsil_id="{{ $org->tahsil_id }}"
                                            data-village_id="{{ $org->village_id }}"
                                            data-city="{{ $org->city }}"
                                            data-pincode="{{ $org->pincode }}"
                                            title="View details">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        {{-- Delete --}}
                                        <form action="{{ route('master.organiser.destroy', $org->oid) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this organiser?')">
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
                @if($organisers->hasPages())
                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
                    <small class="text-muted">
                        Showing {{ $organisers->firstItem() }} to {{ $organisers->lastItem() }} of {{ $organisers->total() }} results
                    </small>
                    {{ $organisers->links() }}
                </div>
                @endif
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-group-line" style="font-size:40px;color:#c8d6e5"></i>
                    <p class="text-muted mt-2 mb-3">No organisers found.</p>
                    <button class="btn btn-sm btn-primary" id="addFirstOrganiserBtn">
                        <i class="ri-add-line me-1"></i>Add First Organiser
                    </button>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@section('modals')

{{-- ── Add / Edit Organiser Modal ───────────────────────────── --}}
<div class="modal fade" id="organiserModal" tabindex="-1" aria-labelledby="organiserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="organiserModalLabel">
                    <i class="ri-add-line me-1"></i>New Organiser
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="organiserForm" method="POST" action="{{ route('master.organiser.store') }}">
                @csrf
                <div class="modal-body">

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs mb-3" id="organiserTabs">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#org_basic">
                                <i class="ri-user-line me-1"></i>Basic
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#org_address">
                                <i class="ri-map-pin-line me-1"></i>Address
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#org_bank">
                                <i class="ri-bank-line me-1"></i>Bank
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- Basic --}}
                        <div class="tab-pane fade show active" id="org_basic">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Org ID (Temp)</label>
                                    <input type="text" name="tmp_oid" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Organiser Name <span class="text-danger">*</span></label>
                                    <input type="text" name="oname" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Father Name</label>
                                    <input type="text" name="fname" class="form-control">
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
                                    <label class="form-label">Mobile 1 <span class="text-danger">*</span></label>
                                    <input type="text" name="mobile_1" class="form-control" required maxlength="20">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mobile 2</label>
                                    <input type="text" name="mobile_2" class="form-control" maxlength="20">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Authorized Signatory</label>
                                    <input type="text" name="authorized_signatory" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Aadhar No</label>
                                    <input type="text" name="aadhar_no" class="form-control" maxlength="15">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">PAN No</label>
                                    <input type="text" name="pan_no" class="form-control" maxlength="15">
                                </div>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="tab-pane fade" id="org_address">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" maxlength="30">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">State</label>
                                    <select name="state_id" id="org_state_id" class="form-select">
                                        <option value="">-- Select State --</option>
                                        @foreach($oldStates as $state)
                                            <option value="{{ $state->StateId }}">{{ $state->StateName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">District</label>
                                    <select name="district_id" id="org_district_id" class="form-select">
                                        <option value="">-- Select District --</option>
                                        @foreach($oldDistricts as $district)
                                            <option value="{{ $district->DictrictId }}" data-state="{{ $district->StateId }}">{{ $district->DictrictName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tahsil</label>
                                    <select name="tahsil_id" id="org_tahsil_id" class="form-select">
                                        <option value="">-- Select Tahsil --</option>
                                        @foreach($oldTahsils as $tahsil)
                                            <option value="{{ $tahsil->TahsilId }}" data-district="{{ $tahsil->DistrictId }}">{{ $tahsil->TahsilName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Village</label>
                                    <select name="village_id" id="org_village_id" class="form-select">
                                        <option value="">-- Select Village --</option>
                                        @foreach($oldVillages as $village)
                                            <option value="{{ $village->VillageId }}" data-tahsil="{{ $village->TahsilId }}">{{ $village->VillageName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pincode</label>
                                    <input type="text" name="pincode" class="form-control" maxlength="10">
                                </div>
                            </div>
                        </div>

                        {{-- Bank --}}
                        <div class="tab-pane fade" id="org_bank">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" maxlength="30">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Account No</label>
                                    <input type="text" name="account_no" class="form-control" maxlength="30">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Branch Name</label>
                                    <input type="text" name="branch_name" class="form-control" maxlength="30">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="form-control" maxlength="15">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Bank Address</label>
                                    <input type="text" name="bank_add" class="form-control" maxlength="100">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="organiserSubmitBtn">
                        <i class="ri-save-line me-1"></i>Save Organiser
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="organiserDetailsModal" tabindex="-1" aria-labelledby="organiserDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="organiserDetailsModalLabel"><i class="ri-eye-line me-1"></i>Organiser Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><strong>Name</strong><div id="detail_oname" class="text-muted"></div></div>
                    <div class="col-md-6"><strong>Father Name</strong><div id="detail_fname" class="text-muted"></div></div>
                    <div class="col-md-6"><strong>Mobile</strong><div id="detail_mobile_1" class="text-muted"></div></div>
                    <div class="col-md-6"><strong>Email</strong><div id="detail_email" class="text-muted"></div></div>
                    <div class="col-12"><strong>Address</strong><div id="detail_address" class="text-muted"></div></div>
                    <div class="col-md-6"><strong>State</strong><div id="detail_state" class="text-muted"></div></div>
                    <div class="col-md-6"><strong>District</strong><div id="detail_district" class="text-muted"></div></div>
                    <div class="col-md-6"><strong>Tahsil</strong><div id="detail_tahsil" class="text-muted"></div></div>
                    <div class="col-md-6"><strong>Village</strong><div id="detail_village" class="text-muted"></div></div>
                    <div class="col-md-6"><strong>City</strong><div id="detail_city" class="text-muted"></div></div>
                    <div class="col-md-6"><strong>Pincode</strong><div id="detail_pincode" class="text-muted"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalEl = document.getElementById('organiserModal');
    const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
    const detailsModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('organiserDetailsModal'));

    const stateEl = document.getElementById('org_state_id');
    const districtEl = document.getElementById('org_district_id');
    const tahsilEl = document.getElementById('org_tahsil_id');
    const villageEl = document.getElementById('org_village_id');
    const allDistrictOpts = Array.from(districtEl.options);
    const allTahsilOpts = Array.from(tahsilEl.options);
    const allVillageOpts = Array.from(villageEl.options);

    function filterSelect(selectEl, allOpts, filterAttr, filterVal, selectedVal) {
        selectEl.innerHTML = '<option value="">-- Select --</option>';
        allOpts.forEach(option => {
            if (!option.value || (filterVal && option.dataset[filterAttr] != filterVal)) return;
            const clone = option.cloneNode(true);
            if (selectedVal && clone.value == selectedVal) clone.selected = true;
            selectEl.appendChild(clone);
        });
    }

    stateEl.addEventListener('change', function () {
        filterSelect(districtEl, allDistrictOpts, 'state', this.value, '');
        filterSelect(tahsilEl, allTahsilOpts, 'district', '', '');
        filterSelect(villageEl, allVillageOpts, 'tahsil', '', '');
    });
    districtEl.addEventListener('change', function () {
        filterSelect(tahsilEl, allTahsilOpts, 'district', this.value, '');
        filterSelect(villageEl, allVillageOpts, 'tahsil', '', '');
    });
    tahsilEl.addEventListener('change', function () {
        filterSelect(villageEl, allVillageOpts, 'tahsil', this.value, '');
    });

    function resetToFirstTab() {
        document.querySelector('#organiserTabs .nav-link.active')?.classList.remove('active');
        document.querySelector('#organiserTabs .nav-link[data-bs-target="#org_basic"]').classList.add('active');
        document.querySelectorAll('#organiserModal .tab-pane').forEach(p => p.classList.remove('show','active'));
        document.getElementById('org_basic').classList.add('show','active');
    }

    function setVal(form, name, val) {
        const el = form.querySelector('[name="' + name + '"]');
        if (el) el.value = val ?? '';
    }

    function openAdd() {
        const form = document.getElementById('organiserForm');
        form.reset();
        filterSelect(districtEl, allDistrictOpts, 'state', '', '');
        filterSelect(tahsilEl, allTahsilOpts, 'district', '', '');
        filterSelect(villageEl, allVillageOpts, 'tahsil', '', '');
        form.action = '{{ route("master.organiser.store") }}';
        document.getElementById('organiserModalLabel').innerHTML = '<i class="ri-add-line me-1"></i>New Organiser';
        document.getElementById('organiserSubmitBtn').innerHTML  = '<i class="ri-save-line me-1"></i>Save Organiser';
        const m = form.querySelector('input[name="_method"]');
        if (m) m.remove();
        resetToFirstTab();
        modal.show();
    }

    document.getElementById('addOrganiserBtn')?.addEventListener('click', openAdd);
    document.getElementById('addFirstOrganiserBtn')?.addEventListener('click', openAdd);

    document.querySelectorAll('.editOrganiserBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const d    = this.dataset;
            const form = document.getElementById('organiserForm');

            form.action = '{{ url("master/organiser") }}/' + d.id;
            document.getElementById('organiserModalLabel').innerHTML = '<i class="ri-edit-line me-1"></i>Edit Organiser';
            document.getElementById('organiserSubmitBtn').innerHTML  = '<i class="ri-save-line me-1"></i>Update Organiser';

            // Basic
            setVal(form, 'tmp_oid',               d.tmp_oid);
            setVal(form, 'oname',                 d.oname);
            setVal(form, 'fname',                 d.fname);
            setVal(form, 'dob',                   d.dob);
            setVal(form, 'age',                   d.age);
            setVal(form, 'mobile_1',              d.mobile_1);
            setVal(form, 'mobile_2',              d.mobile_2);
            setVal(form, 'email',                 d.email);
            setVal(form, 'authorized_signatory',  d.authorized_signatory);
            setVal(form, 'aadhar_no',             d.aadhar_no);
            setVal(form, 'pan_no',                d.pan_no);
            // Address
            setVal(form, 'address', d.address);
            setVal(form, 'city',    d.city);
            setVal(form, 'pincode', d.pincode);
            stateEl.value = d.state_id || '';
            filterSelect(districtEl, allDistrictOpts, 'state', d.state_id, d.district_id);
            filterSelect(tahsilEl, allTahsilOpts, 'district', d.district_id, d.tahsil_id);
            filterSelect(villageEl, allVillageOpts, 'tahsil', d.tahsil_id, d.village_id);
            // Bank
            setVal(form, 'bank_name',   d.bank_name);
            setVal(form, 'account_no',  d.account_no);
            setVal(form, 'branch_name', d.branch_name);
            setVal(form, 'ifsc_code',   d.ifsc_code);

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

    document.querySelectorAll('.viewOrganiserBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const d = this.dataset;
            const labels = {
                state: stateEl,
                district: districtEl,
                tahsil: tahsilEl,
                village: villageEl
            };
            Object.entries({
                oname: d.oname, fname: d.fname, mobile_1: d.mobile_1, email: d.email,
                address: d.address, city: d.city, pincode: d.pincode
            }).forEach(([name, value]) => {
                document.getElementById('detail_' + name).textContent = value || '-';
            });
            Object.entries(labels).forEach(([name, select]) => {
                const id = d[name + '_id'];
                document.getElementById('detail_' + name).textContent =
                    id ? (select.querySelector('option[value="' + id + '"]')?.textContent.trim() || '-') : '-';
            });
            detailsModal.show();
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
