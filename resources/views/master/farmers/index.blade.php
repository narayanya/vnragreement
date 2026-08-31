@extends('layouts.app')

@section('content')
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
                    <div class="row g-2 align-items-center">
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
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-filter-line me-1"></i>Filter
                            </button>
                            @if(request('search'))
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
                                            data-address="{{ $farmer->address }}"
                                            data-pincode="{{ $farmer->pincode }}"
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
                    </ul>

                    <div class="tab-content">

                        {{-- Basic --}}
                        <div class="tab-pane fade show active" id="f_basic">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Farmer ID (Temp)</label>
                                    <input type="text" name="tem_fid" class="form-control" maxlength="30">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Farmer Name <span class="text-danger">*</span></label>
                                    <input type="text" name="fname" class="form-control" required maxlength="50">
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
                                    <textarea name="address" class="form-control" rows="3" maxlength="150"></textarea>
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
                                    <label class="form-label">Address Proof Name</label>
                                    <input type="text" name="addproof_name" class="form-control" maxlength="30">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Address Proof No</label>
                                    <input type="text" name="addproof_no" class="form-control" maxlength="20">
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

            // Basic
            setVal(form, 'tem_fid',       d.tem_fid);
            setVal(form, 'fname',          d.fname);
            setVal(form, 'father_name',    d.father_name);
            setVal(form, 'father_contact', d.father_contact);
            setVal(form, 'dob',            d.dob);
            setVal(form, 'age',            d.age);
            setVal(form, 'contact_1',      d.contact_1);
            setVal(form, 'contact_2',      d.contact_2);
            setVal(form, 'email',          d.email);
            setVal(form, 'total_land',     d.total_land);
            // Address
            setVal(form, 'address', d.address);
            setVal(form, 'pincode', d.pincode);
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
</script>
@endpush
