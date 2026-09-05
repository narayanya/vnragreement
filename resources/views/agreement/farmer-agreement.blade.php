@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">Farmer Agreements</h3>
                <p class="mb-0" style="font-size:13px;color:#71809a">Manage seed production agreements with farmers</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge rounded-pill px-3 py-2" style="background:#e6f4f3;color:#187b78;font-size:12px;font-weight:600;">
                    <i class="ri-file-list-3-line me-1"></i>{{ $total }} Agreements
                </span>
                <a href="{{ route('farmer-agreements.create') }}" class="btn btn-sm btn-primary">
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
                <form method="GET" action="{{ route('farmer-agreements.index') }}">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Search farmer, crop, organiser…"
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
                                <a href="{{ route('farmer-agreements.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
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
                                    <th>Farmer</th>
                                    <th>Crop</th>
                                    <th>Organiser</th>
                                    <th>Agreement Date</th>
                                    <th>Period</th>
                                    <th>Status</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:13px">
                                @foreach($agreements as $i => $agreement)
                                <tr>
                                    <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                                    <td class="fw-semibold" style="color:#172b4d">{{ $agreement->farmer_name ?? '-' }}</td>
                                    <td>{{ $agreement->crop_name ?? '-' }}</td>
                                    <td class="text-muted">{{ $agreement->organiser_name ?? '-' }}</td>
                                    <td class="text-muted">{{ $agreement->agreement_date ?? '-' }}</td>
                                    <td class="text-muted">{{ $agreement->period_from ?? '-' }} – {{ $agreement->period_to ?? '-' }}</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td class="text-end pe-3">
                                        <a href="#" class="btn btn-sm btn-outline-info" title="View"><i class="ri-eye-line"></i></a>
                        <a href="{{ route('farmer-agreements.edit', $agreement->id) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="ri-edit-line"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ri-file-list-3-line" style="font-size:48px;color:#c8d6e5;display:block;margin-bottom:12px"></i>
                        <p class="fw-semibold mb-1" style="color:#172b4d">No agreements yet</p>
                        <p class="text-muted mb-3" style="font-size:13px">Create your first seed production agreement to get started.</p>
                        <a href="{{ route('farmer-agreements.create') }}" class="btn btn-sm btn-primary">
                            <i class="ri-add-line me-1"></i>New Agreement
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
