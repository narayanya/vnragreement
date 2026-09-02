@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">Old Location Master</h3>
                <p class="mb-0" style="font-size:13px;color:#71809a">Read-only view — State, District, Tahsil, Village</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge rounded-pill px-3 py-2" style="background:#e6f4f3;color:#187b78;font-size:12px;font-weight:600;">
                    <i class="ri-map-pin-line me-1"></i>
                    {{ $states->total() }} States &nbsp;·&nbsp;
                    {{ $districts->total() }} Districts &nbsp;·&nbsp;
                    {{ $tahsils->total() }} Tahsils &nbsp;·&nbsp;
                    {{ $villages->total() }} Villages
                </span>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-3" id="locationTabs">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-state">
                    <i class="ri-map-2-line me-1"></i>State
                    <span class="badge ms-1" style="background:#e6f4f3;color:#187b78;font-size:10px">{{ $states->total() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-district">
                    <i class="ri-map-pin-2-line me-1"></i>District
                    <span class="badge ms-1" style="background:#e6f4f3;color:#187b78;font-size:10px">{{ $districts->total() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-tahsil">
                    <i class="ri-map-pin-line me-1"></i>Tahsil
                    <span class="badge ms-1" style="background:#e6f4f3;color:#187b78;font-size:10px">{{ $tahsils->total() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-village">
                    <i class="ri-home-4-line me-1"></i>Village
                    <span class="badge ms-1" style="background:#e6f4f3;color:#187b78;font-size:10px">{{ $villages->total() }}</span>
                </button>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ── STATE ─────────────────────────────────── --}}
            <div class="tab-pane fade show active" id="tab-state">
                <div class="card mb-2">
                    <div class="card-body py-2">
                        <form method="GET" action="{{ route('master.old-location.index') }}">
                            <div class="row g-2 align-items-center">
                                <div class="col">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                                        <input type="text" name="state_search" class="form-control border-start-0 ps-0"
                                               placeholder="Search state name…" value="{{ request('state_search') }}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="ri-filter-line me-1"></i>Filter</button>
                                    @if(request('state_search'))
                                        <a href="{{ route('master.old-location.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="ri-close-line"></i></a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>State Name</th>
                                        <th>State Code</th>
                                        <th>sCode</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size:13px">
                                    @forelse($states as $i => $state)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ ($states->currentPage()-1)*$states->perPage()+$loop->iteration }}</td>
                                        <td class="fw-semibold" style="color:#172b4d">{{ $state->StateName }}</td>
                                        <td><code style="background:#f6f8fb;color:#3d5068;padding:2px 7px;border-radius:4px;font-size:12px;border:1px solid #e7ebf2">{{ $state->StateCode ?? '-' }}</code></td>
                                        <td class="text-muted">{{ $state->sCode ?? '-' }}</td>
                                        <td>
                                            @if($state->StateStatus == 'A' || $state->StateStatus == '1')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $state->StateStatus ?? '-' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No states found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($states->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center py-2">
                        <small class="text-muted">Showing {{ $states->firstItem() }}–{{ $states->lastItem() }} of {{ $states->total() }}</small>
                        {{ $states->links() }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── DISTRICT ──────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-district">
                <div class="card mb-2">
                    <div class="card-body py-2">
                        <form method="GET" action="{{ route('master.old-location.index') }}">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                                        <input type="text" name="district_search" class="form-control border-start-0 ps-0"
                                               placeholder="Search district name…" value="{{ request('district_search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="filter_state" class="form-select form-select-sm">
                                        <option value="">All States</option>
                                        @foreach($allStates as $s)
                                            <option value="{{ $s->StateId }}" {{ request('filter_state') == $s->StateId ? 'selected' : '' }}>
                                                {{ $s->StateName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="ri-filter-line me-1"></i>Filter</button>
                                    @if(request('district_search') || request('filter_state'))
                                        <a href="{{ route('master.old-location.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="ri-close-line"></i></a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>District Name</th>
                                        <th>State</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size:13px">
                                    @forelse($districts as $district)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ ($districts->currentPage()-1)*$districts->perPage()+$loop->iteration }}</td>
                                        <td class="fw-semibold" style="color:#172b4d">{{ $district->DictrictName }}</td>
                                        <td class="text-muted">
                                            {{ $allStates->firstWhere('StateId', $district->StateId)->StateName ?? $district->StateId }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center py-4 text-muted">No districts found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($districts->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center py-2">
                        <small class="text-muted">Showing {{ $districts->firstItem() }}–{{ $districts->lastItem() }} of {{ $districts->total() }}</small>
                        {{ $districts->links() }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── TAHSIL ────────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-tahsil">
                <div class="card mb-2">
                    <div class="card-body py-2">
                        <form method="GET" action="{{ route('master.old-location.index') }}">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                                        <input type="text" name="tahsil_search" class="form-control border-start-0 ps-0"
                                               placeholder="Search tahsil name…" value="{{ request('tahsil_search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="filter_district" class="form-select form-select-sm">
                                        <option value="">All Districts</option>
                                        @foreach($allDistricts as $d)
                                            <option value="{{ $d->DictrictId }}" {{ request('filter_district') == $d->DictrictId ? 'selected' : '' }}>
                                                {{ $d->DictrictName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="ri-filter-line me-1"></i>Filter</button>
                                    @if(request('tahsil_search') || request('filter_district'))
                                        <a href="{{ route('master.old-location.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="ri-close-line"></i></a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>Tahsil Name</th>
                                        <th>Tahsil Code</th>
                                        <th>District</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size:13px">
                                    @forelse($tahsils as $tahsil)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ ($tahsils->currentPage()-1)*$tahsils->perPage()+$loop->iteration }}</td>
                                        <td class="fw-semibold" style="color:#172b4d">{{ $tahsil->TahsilName }}</td>
                                        <td><code style="background:#f6f8fb;color:#3d5068;padding:2px 7px;border-radius:4px;font-size:12px;border:1px solid #e7ebf2">{{ $tahsil->TahsilCode ?? '-' }}</code></td>
                                        <td class="text-muted">
                                            {{ $allDistricts->firstWhere('DictrictId', $tahsil->DistrictId)->DictrictName ?? $tahsil->DistrictId }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted">No tahsils found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($tahsils->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center py-2">
                        <small class="text-muted">Showing {{ $tahsils->firstItem() }}–{{ $tahsils->lastItem() }} of {{ $tahsils->total() }}</small>
                        {{ $tahsils->links() }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── VILLAGE ───────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-village">
                <div class="card mb-2">
                    <div class="card-body py-2">
                        <form method="GET" action="{{ route('master.old-location.index') }}">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0"><i class="ri-search-line text-muted"></i></span>
                                        <input type="text" name="village_search" class="form-control border-start-0 ps-0"
                                               placeholder="Search village name…" value="{{ request('village_search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="filter_tahsil" class="form-select form-select-sm">
                                        <option value="">All Tahsils</option>
                                        @foreach($allTahsils as $t)
                                            <option value="{{ $t->TahsilId }}" {{ request('filter_tahsil') == $t->TahsilId ? 'selected' : '' }}>
                                                {{ $t->TahsilName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="ri-filter-line me-1"></i>Filter</button>
                                    @if(request('village_search') || request('filter_tahsil'))
                                        <a href="{{ route('master.old-location.index') }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="ri-close-line"></i></a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>Village Name</th>
                                        <th>Pincode</th>
                                        <th>Tahsil</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size:13px">
                                    @forelse($villages as $village)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ ($villages->currentPage()-1)*$villages->perPage()+$loop->iteration }}</td>
                                        <td class="fw-semibold" style="color:#172b4d">{{ $village->VillageName }}</td>
                                        <td class="text-muted">{{ $village->PinCode ?? '-' }}</td>
                                        <td class="text-muted">
                                            {{ $allTahsils->firstWhere('TahsilId', $village->TahsilId)->TahsilName ?? $village->TahsilId }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted">No villages found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($villages->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center py-2">
                        <small class="text-muted">Showing {{ $villages->firstItem() }}–{{ $villages->lastItem() }} of {{ $villages->total() }}</small>
                        {{ $villages->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>{{-- end tab-content --}}
    </div>
</div>
@endsection

@push('scripts')
<script>
// Keep the active tab after filter form submit
document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);

    // Determine which tab to activate based on which filter was used
    let target = '#tab-state';
    if (params.has('district_search') || params.has('filter_state'))   target = '#tab-district';
    if (params.has('tahsil_search')   || params.has('filter_district')) target = '#tab-tahsil';
    if (params.has('village_search')  || params.has('filter_tahsil'))   target = '#tab-village';

    const tabBtn = document.querySelector(`[data-bs-target="${target}"]`);
    if (tabBtn) bootstrap.Tab.getOrCreateInstance(tabBtn).show();
});
</script>
@endpush
