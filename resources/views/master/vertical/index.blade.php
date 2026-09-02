@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">Vertical Master</h3>
                <p class="mb-0 text-muted" style="font-size:13px">Core synced vertical/crop type records &mdash; Total: {{ $total }}</p>
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
                <form method="GET" action="{{ route('master.vertical.index') }}">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Search by name, code, or description…"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">-- All Status --</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-filter-line me-1"></i>Filter
                            </button>
                            @if(request('search') || request('status'))
                                <a href="{{ route('master.vertical.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="ri-close-line me-1"></i>Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        @if($verticals->count())
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Total Crops</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:13px">
                                @foreach($verticals as $vertical)
                                <tr>
                                    <td class="ps-3 text-muted">
                                        {{ ($verticals->currentPage() - 1) * $verticals->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="fw-semibold" style="color:#172b4d">{{ $vertical->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info text-white">{{ $vertical->code ?? '-' }}</span>
                                    </td>
                                    <td class="text-muted">
                                        {{ Str::limit($vertical->description ?? '-', 50) }}
                                    </td>
                                    <td>
                                        @if($vertical->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        {{ $vertical->crops ? $vertical->crops->count() : 0 }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($verticals->hasPages())
                <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
                    <small class="text-muted">
                        Showing {{ $verticals->firstItem() }} to {{ $verticals->lastItem() }} of {{ $verticals->total() }} results
                    </small>
                    {{ $verticals->links() }}
                </div>
                @endif
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-leaf-line" style="font-size:40px;color:#c8d6e5"></i>
                    <p class="text-muted mt-2 mb-0">No vertical data found.</p>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
