@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">Organiser Agreements</h3>
                <p class="mb-0" style="font-size:13px;color:#71809a">Manage seed production agreements with organisers</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge rounded-pill px-3 py-2" style="background:#e6f4f3;color:#187b78;font-size:12px;font-weight:600;">
                    <i class="ri-file-list-3-line me-1"></i>{{ $total }} Agreements
                </span>
                <a href="{{ route('organiser-agreements.create') }}" class="btn btn-sm btn-primary">
                    <i class="ri-add-line me-1"></i>New Agreement
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Search & Filter --}}
        <div class="card mb-3">
            <div class="card-body py-2">
                <form method="GET" action="{{ route('organiser-agreements.index') }}">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Search agreement no, organiser, company…"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <label class="form-label mb-0" style="font-size:12px;color:#71809a">From</label>
                        </div>
                        <div class="col-auto">
                            <input type="date" name="from_date" class="form-control form-control-sm"
                                value="{{ request('from_date') }}">
                        </div>
                        <div class="col-auto">
                            <label class="form-label mb-0" style="font-size:12px;color:#71809a">To</label>
                        </div>
                        <div class="col-auto">
                            <input type="date" name="to_date" class="form-control form-control-sm"
                                value="{{ request('to_date') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-filter-line me-1"></i>Filter
                            </button>
                            @if(request('search') || request('from_date') || request('to_date'))
                                <a href="{{ route('organiser-agreements.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="ri-close-line"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table / Empty state --}}
        <div class="card">
            <div class="card-body p-0">
                @if($total > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Agree No</th>
                                    <th>First Party</th>
                                    <th>Organiser</th>
                                    <th>Agreement Date</th>
                                    <th>Period</th>
                                    <th>Season</th>
                                    <th>State</th>
                                    <th>Prod. Area (Ac)</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:13px">
                                @foreach($agreements as $i => $ag)
                                <tr>
                                    <td class="ps-3 text-muted">{{ ($agreements->currentPage() - 1) * $agreements->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <span class="badge bg-info text-white">{{ $ag->org_agree_no }}</span>
                                    </td>
                                    <td class="fw-semibold" style="color:#172b4d">{{ $ag->first_party_name ?? '-' }}</td>
                                    <td>{{ $ag->organiser_name ?? '-' }}</td>
                                    <td class="text-muted">
                                        {{ $ag->agree_date ? \Carbon\Carbon::parse($ag->agree_date)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td class="text-muted" style="white-space:nowrap">
                                        {{ $ag->start_date ? \Carbon\Carbon::parse($ag->start_date)->format('d-m-Y') : '-' }}
                                        &ndash;
                                        {{ $ag->end_date ? \Carbon\Carbon::parse($ag->end_date)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td>
                                        @foreach(explode(',', $ag->season ?? '') as $s)
                                            @if(trim($s))
                                                <span class="badge rounded-pill"
                                                    style="background:{{ trim($s) === 'Kharif' ? '#fff3cd' : '#d1ecf1' }};
                                                           color:{{ trim($s) === 'Kharif' ? '#856404' : '#0c5460' }};
                                                           font-size:11px">
                                                    {{ trim($s) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="text-muted">{{ $ag->state_name ?? '-' }}</td>
                                    <td class="text-muted">{{ $ag->production_area ? number_format($ag->production_area, 2) : '-' }}</td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('organiser-agreements.show', $ag->agree_id) }}"
                                           class="btn btn-sm btn-outline-info" title="Preview">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('organiser-agreements.show', $ag->agree_id) }}?print=1"
                                           target="_blank" class="btn btn-sm btn-outline-secondary" title="Print">
                                            <i class="ri-printer-line"></i>
                                        </a>
                                        <a href="{{ route('organiser-agreements.edit', $ag->agree_id) }}"
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('organiser-agreements.destroy', $ag->agree_id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete agreement {{ $ag->org_agree_no }}?')">
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
                    @if($agreements->hasPages())
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
                            <small class="text-muted">
                                Showing {{ $agreements->firstItem() }} to {{ $agreements->lastItem() }} of {{ $agreements->total() }} results
                            </small>
                            {{ $agreements->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="ri-file-list-3-line" style="font-size:48px;color:#c8d6e5;display:block;margin-bottom:12px"></i>
                        <p class="fw-semibold mb-1" style="color:#172b4d">No organiser agreements yet</p>
                        <p class="text-muted mb-3" style="font-size:13px">Create your first organiser agreement to get started.</p>
                        <a href="{{ route('organiser-agreements.create') }}" class="btn btn-sm btn-primary">
                            <i class="ri-add-line me-1"></i>New Agreement
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
