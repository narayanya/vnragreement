@extends('layouts.app')

@section('content')
<div class="col-12">

    {{-- ── Page Header ──────────────────────────────────────────── --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-2">
        <div>
            <h3 class="mb-0">Location Master</h3>
            <p class="mb-0 text-muted">
                View synced location data &mdash;
                <span class="text-muted">Country / State / District / City</span>
            </p>
        </div>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addLocationModal">
            <i class="ri-add-line me-1"></i>Add Location
        </button>
    </div>

    {{-- ── Alerts ───────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-checkbox-circle-line me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Tabs ─────────────────────────────────────────────────── --}}
    <ul class="nav loc-tabs mb-3" role="tablist">
        @foreach([
            ['key'=>'countries', 'icon'=>'ri-earth-line',     'label'=>'Countries'],
            ['key'=>'states',    'icon'=>'ri-map-line',        'label'=>'States'],
            ['key'=>'districts', 'icon'=>'ri-road-map-line',   'label'=>'Districts'],
            ['key'=>'blocks',    'icon'=>'ri-layout-grid-line','label'=>'Blocks'],
            ['key'=>'cities',    'icon'=>'ri-building-line',   'label'=>'Cities'],
        ] as $t)
        <li class="nav-item">
            <a href="{{ route('core.location.index', array_merge(request()->except(['tab','page']), ['tab' => $t['key']])) }}"
               class="loc-tab-link {{ $tab === $t['key'] ? 'is-active' : '' }}"
               role="tab">
                <i class="{{ $t['icon'] }} me-1"></i>{{ $t['label'] }}
                <span class="loc-tab-count">{{ number_format($counts[$t['key']]) }}</span>
            </a>
        </li>
        @endforeach
    </ul>

    {{-- ── Search / Filter Bar ──────────────────────────────────── --}}
    <form method="GET" action="{{ route('core.location.index') }}" id="locFilterForm">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="loc-toolbar mb-3">
            <div class="input-group input-group-sm" style="max-width:380px">
                <span class="input-group-text"><i class="ri-search-line"></i></span>
                <input type="text" name="search" class="form-control"
                    placeholder="Search by name or code…"
                    value="{{ $search }}">
                @if($search)
                    <a href="{{ route('core.location.index', ['tab'=>$tab]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ri-close-line"></i>
                    </a>
                @endif
            </div>
            <select name="status" class="form-select form-select-sm" style="width:140px" onchange="this.form.submit()">
                <option value=""  {{ $status===''  ? 'selected' : '' }}>All Status</option>
                <option value="1" {{ $status==='1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $status==='0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="ri-filter-line me-1"></i>Filter
            </button>
            @if($search || $status !== '')
                <a href="{{ route('core.location.index', ['tab'=>$tab]) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="ri-refresh-line me-1"></i>Reset
                </a>
            @endif
            <span class="ms-auto text-muted" style="font-size:0.78rem;white-space:nowrap">
                @php
                    $active = match($tab) {
                        'states'    => $states,
                        'districts' => $districts,
                        'blocks'    => $blocks,
                        'cities'    => $cities,
                        default     => $countries,
                    };
                @endphp
                @if(method_exists($active,'total'))
                    Total: <strong>{{ number_format($active->total()) }}</strong> {{ $tab }}
                @endif
            </span>
        </div>
    </form>

    {{-- ── Table Card ───────────────────────────────────────────── --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">

                {{-- Countries --}}
                @if($tab === 'countries')
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:50px">#</th>
                            <th>Country Name</th>
                            <th>Country Code</th>
                            <th>Global Region</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($countries as $row)
                        <tr>
                            <td class="ps-3 text-muted">{{ ($countries->currentPage()-1)*$countries->perPage()+$loop->iteration }}</td>
                            <td class="fw-semibold" style="color:#187b78">{{ $row->country_name ?? '-' }}</td>
                            <td><span class="badge bg-info">{{ $row->country_code ?? '-' }}</span></td>
                            <td class="text-muted">{{ $row->global_region ?? '-' }}</td>
                            <td class="text-center">@include('core.location._status', ['val'=>$row->is_active])</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-5">
                            <i class="ri-earth-line" style="font-size:32px;color:#c8d6e5;display:block;margin-bottom:8px"></i>
                            No countries found.
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
                @endif

                {{-- States --}}
                @if($tab === 'states')
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:50px">#</th>
                            <th>State Name</th>
                            <th>State Code</th>
                            <th>Short Code</th>
                            <th>Country</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($states as $row)
                        <tr>
                            <td class="ps-3 text-muted">{{ ($states->currentPage()-1)*$states->perPage()+$loop->iteration }}</td>
                            <td class="fw-semibold" style="color:#187b78">{{ $row->state_name ?? '-' }}</td>
                            <td><span class="badge bg-info">{{ $row->state_code ?? '-' }}</span></td>
                            <td class="text-muted">{{ $row->short_code ?? '-' }}</td>
                            <td class="text-muted">{{ $row->country->country_name ?? '-' }}</td>
                            <td class="text-center">@include('core.location._status', ['val'=>$row->is_active])</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">
                            <i class="ri-map-line" style="font-size:32px;color:#c8d6e5;display:block;margin-bottom:8px"></i>
                            No states found.
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
                @endif

                {{-- Districts --}}
                @if($tab === 'districts')
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:50px">#</th>
                            <th>District Name</th>
                            <th>District Code</th>
                            <th>Numeric Code</th>
                            <th>State</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($districts as $row)
                        <tr>
                            <td class="ps-3 text-muted">{{ ($districts->currentPage()-1)*$districts->perPage()+$loop->iteration }}</td>
                            <td class="fw-semibold" style="color:#187b78">{{ $row->district_name ?? '-' }}</td>
                            <td><span class="badge bg-info">{{ $row->district_code ?? '-' }}</span></td>
                            <td class="text-muted">{{ $row->numeric_code ?? '-' }}</td>
                            <td class="text-muted">{{ $row->state->state_name ?? '-' }}</td>
                            <td class="text-center">@include('core.location._status', ['val'=>$row->is_active])</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">
                            <i class="ri-road-map-line" style="font-size:32px;color:#c8d6e5;display:block;margin-bottom:8px"></i>
                            No districts found.
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
                @endif

                {{-- Blocks --}}
                @if($tab === 'blocks')
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:50px">#</th>
                            <th>Block Name</th>
                            <th>Block Code</th>
                            <th>Numeric Code</th>
                            <th>District</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blocks as $row)
                        <tr>
                            <td class="ps-3 text-muted">{{ ($blocks->currentPage()-1)*$blocks->perPage()+$loop->iteration }}</td>
                            <td class="fw-semibold" style="color:#187b78">{{ $row->block_name ?? '-' }}</td>
                            <td><span class="badge bg-info">{{ $row->block_code ?? '-' }}</span></td>
                            <td class="text-muted">{{ $row->numeric_code ?? '-' }}</td>
                            <td class="text-muted">{{ $row->district->district_name ?? '-' }}</td>
                            <td class="text-center">@include('core.location._status', ['val'=>$row->is_active])</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">
                            <i class="ri-layout-grid-line" style="font-size:32px;color:#c8d6e5;display:block;margin-bottom:8px"></i>
                            No blocks found.
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
                @endif

                {{-- Cities --}}
                @if($tab === 'cities')
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:50px">#</th>
                            <th>City / Village Name</th>
                            <th>Code</th>
                            <th>Division</th>
                            <th>District</th>
                            <th>State</th>
                            <th>Pincode</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $row)
                        <tr>
                            <td class="ps-3 text-muted">{{ ($cities->currentPage()-1)*$cities->perPage()+$loop->iteration }}</td>
                            <td class="fw-semibold" style="color:#187b78">{{ $row->city_village_name ?? '-' }}</td>
                            <td><span class="badge bg-info">{{ $row->city_village_code ?? '-' }}</span></td>
                            <td class="text-muted">{{ $row->division_name ?? '-' }}</td>
                            <td class="text-muted">{{ $row->district->district_name ?? '-' }}</td>
                            <td class="text-muted">{{ $row->state->state_name ?? '-' }}</td>
                            <td class="text-muted">{{ $row->pincode ?? '-' }}</td>
                            <td class="text-center">@include('core.location._status', ['val'=>$row->is_active])</td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-5">
                            <i class="ri-building-line" style="font-size:32px;color:#c8d6e5;display:block;margin-bottom:8px"></i>
                            No cities found.
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
                @endif

            </div>
        </div>

        {{-- Pagination --}}
        @php $pg = match($tab) {
            'states'    => $states,
            'districts' => $districts,
            'blocks'    => $blocks,
            'cities'    => $cities,
            default     => $countries,
        }; @endphp
        @if(method_exists($pg,'hasPages') && $pg->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                Showing {{ $pg->firstItem() }} to {{ $pg->lastItem() }} of {{ number_format($pg->total()) }} records
            </small>
            {{ $pg->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@section('modals')

{{-- ── Add Location Modal ───────────────────────────────────── --}}
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="POST" action="{{ route('core.location.store') }}" id="addLocationForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addLocationModalLabel">
                        <i class="ri-map-pin-add-line me-1"></i>Add Location
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Location Type <span class="text-danger">*</span></label>
                        <select name="type" id="locType" class="form-select" required>
                            <option value="">Select type…</option>
                            <option value="country" {{ $tab==='countries' ? 'selected':'' }}>Country</option>
                            <option value="state"   {{ $tab==='states'    ? 'selected':'' }}>State</option>
                            <option value="district"{{ $tab==='districts' ? 'selected':'' }}>District</option>
                            <option value="block"   {{ $tab==='blocks'    ? 'selected':'' }}>Block</option>
                            <option value="city"    {{ $tab==='cities'    ? 'selected':'' }}>City / Village</option>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control">
                        </div>

                        {{-- Country fields --}}
                        <div class="col-md-6 loc-field loc-country">
                            <label class="form-label">Global Region</label>
                            <input type="text" name="region" class="form-control" placeholder="e.g. Asia">
                        </div>

                        {{-- State fields --}}
                        <div class="col-12 loc-field loc-state loc-district loc-block loc-city">
                            <label class="form-label">Country</label>
                            <select name="country_id" class="form-select">
                                <option value="">Select country…</option>
                                @foreach($allCountries as $c)
                                    <option value="{{ $c->id }}">{{ $c->country_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- District / Block / City fields --}}
                        <div class="col-12 loc-field loc-district loc-block loc-city">
                            <label class="form-label">State</label>
                            <select name="state_id" id="stateSelect" class="form-select">
                                <option value="">Select state…</option>
                                @foreach($allStates as $s)
                                    <option value="{{ $s->id }}" data-country="{{ $s->country_id }}">{{ $s->state_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Block / City fields --}}
                        <div class="col-12 loc-field loc-block loc-city">
                            <label class="form-label">District</label>
                            <select name="district_id" id="districtSelect" class="form-select">
                                <option value="">Select district…</option>
                                @foreach($allDistricts as $d)
                                    <option value="{{ $d->id }}" data-state="{{ $d->state_id }}">{{ $d->district_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- City-only --}}
                        <div class="col-md-6 loc-field loc-city">
                            <label class="form-label">Division Name</label>
                            <input type="text" name="division_name" class="form-control">
                        </div>
                        <div class="col-md-6 loc-field loc-city">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="pincode" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ── Location tabs ──────────────────────────────────────────── */
.loc-tabs {
    display: flex;
    gap: 4px;
    border-bottom: 2px solid #e7ebf2;
    margin-bottom: 0;
    flex-wrap: wrap;
}
.loc-tab-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 6px 6px 0 0;
    border: 1px solid transparent;
    border-bottom: 2px solid transparent;
    font-size: 0.8rem;
    font-weight: 600;
    color: #71809a;
    text-decoration: none;
    background: transparent;
    white-space: nowrap;
    margin-bottom: -2px;
    transition: color .15s, background .15s;
}
.loc-tab-link:hover {
    color: #187b78;
    background: #f0f7f7;
    border-color: #e7ebf2;
}
.loc-tab-link.is-active {
    color: #187b78;
    background: #fff;
    border-color: #e7ebf2;
    border-bottom-color: #fff;
}
.loc-tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    padding: 1px 5px;
    border-radius: 10px;
    background: #e6f4f3;
    color: #187b78;
    font-size: 0.68rem;
    font-weight: 700;
}
.loc-tab-link.is-active .loc-tab-count {
    background: #187b78;
    color: #fff;
}

/* ── Toolbar ────────────────────────────────────────────────── */
.loc-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    padding: 10px 0;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Modal: show/hide fields based on type ───────────────── */
    const typeSelect = document.getElementById('locType');

    function syncFields() {
        const val = typeSelect.value;
        document.querySelectorAll('.loc-field').forEach(el => el.style.display = 'none');
        if (val) {
            document.querySelectorAll('.loc-' + val).forEach(el => el.style.display = '');
        }
    }

    typeSelect.addEventListener('change', syncFields);
    syncFields(); // run on load (pre-selected tab type)

    /* ── State → filter districts in modal ──────────────────── */
    const stateSelect    = document.getElementById('stateSelect');
    const districtSelect = document.getElementById('districtSelect');

    if (stateSelect && districtSelect) {
        const allDistrictOptions = Array.from(districtSelect.options).slice(1);

        stateSelect.addEventListener('change', function () {
            const sid = this.value;
            districtSelect.innerHTML = '<option value="">Select district…</option>';
            allDistrictOptions.forEach(opt => {
                if (!sid || opt.dataset.state === sid) {
                    districtSelect.appendChild(opt.cloneNode(true));
                }
            });
        });
    }
});
</script>
@endpush
