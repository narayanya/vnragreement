@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-1">
            <div>
                <h3 class="text-xl font-bold">Employee Master</h3>
                <p class="text-muted mb-0" style="font-size:13px">Manage and view all employee records</p>
            </div>
            {{-- Status Summary Badges --}}
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a href="{{ route('master.employees.index') }}"
                   class="badge text-decoration-none fs-6 {{ !request()->hasAny(['status','department','designation','search']) ? 'bg-secondary' : 'bg-light text-dark border' }}">
                    All &nbsp;<span class="badge bg-white text-dark">{{ $totalCount }}</span>
                </a>
                <a href="{{ route('master.employees.index', array_merge(request()->except('status','page'), ['status'=>'A'])) }}"
                   class="badge text-decoration-none fs-6 {{ request('status')==='A' ? 'bg-success' : 'bg-light text-dark border' }}">
                    <i class="ri-checkbox-circle-line me-1"></i>Active &nbsp;<span class="badge bg-white text-dark">{{ $activeCount }}</span>
                </a>
                <a href="{{ route('master.employees.index', array_merge(request()->except('status','page'), ['status'=>'inactive'])) }}"
                   class="badge text-decoration-none fs-6 {{ request('status')==='inactive' ? 'bg-danger' : 'bg-light text-dark border' }}">
                    <i class="ri-close-circle-line me-1"></i>Inactive &nbsp;<span class="badge bg-white text-dark">{{ $inactiveCount }}</span>
                </a>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body py-2">
                <form method="GET" action="{{ route('master.employees.index') }}" class="row g-2 align-items-end" id="empFilterForm">

                    {{-- Search --}}
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Search</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm"
                                   placeholder="Name, ID, Code, Email..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm" id="statusSelect">
                            <option value="">All Status</option>
                            <option value="A"        {{ request('status')==='A'        ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    {{-- Department --}}
                    <div class="col-md-3">
                        <label class="form-label small mb-1">Department</label>
                        <select name="department" class="form-select form-select-sm" id="deptSelect">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department')===$dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Designation --}}
                    <div class="col-md-2">
                        <label class="form-label small mb-1">Designation</label>
                        <select name="designation" class="form-select form-select-sm" id="desigSelect">
                            <option value="">All Designations</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig }}" {{ request('designation')===$desig ? 'selected' : '' }}>
                                    {{ $desig }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="ri-search-line me-1"></i>Filter
                        </button>
                        @if(request()->hasAny(['search','status','department','designation']))
                        <a href="{{ route('master.employees.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ri-refresh-line"></i> Reset
                        </a>
                        @endif
                    </div>

                </form>
            </div>
        </div>

        {{-- Results info --}}
        @if(request()->hasAny(['search','status','department','designation']))
        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            <small class="text-muted">
                <i class="ri-filter-3-line me-1"></i>
                Showing <strong>{{ $employees->total() }}</strong> result(s)
            </small>
            @if(request('status'))
                <span class="badge {{ request('status')==='A' ? 'bg-success' : 'bg-danger' }}">
                    Status: {{ request('status')==='A' ? 'Active' : 'Inactive' }}
                    <a href="{{ route('master.employees.index', request()->except('status','page')) }}" class="text-white ms-1">×</a>
                </span>
            @endif
            @if(request('department'))
                <span class="badge bg-primary">
                    Dept: {{ request('department') }}
                    <a href="{{ route('master.employees.index', request()->except('department','page')) }}" class="text-white ms-1">×</a>
                </span>
            @endif
            @if(request('designation'))
                <span class="badge bg-info text-dark">
                    Designation: {{ request('designation') }}
                    <a href="{{ route('master.employees.index', request()->except('designation','page')) }}" class="text-white ms-1">×</a>
                </span>
            @endif
            @if(request('search'))
                <span class="badge bg-secondary">
                    Search: "{{ request('search') }}"
                    <a href="{{ route('master.employees.index', request()->except('search','page')) }}" class="text-white ms-1">×</a>
                </span>
            @endif
        </div>
        @endif

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="font-size:12px;">#</th>
                                <th style="font-size:12px;">Name</th>
                                <th style="font-size:12px;">Emp ID</th>
                                <th style="font-size:12px;">Code</th>
                                <th style="font-size:12px;">Department</th>
                                <th style="font-size:12px;">Designation</th>
                                <th style="font-size:12px;">Email</th>
                                <th style="font-size:12px;">Contact</th>
                                <th style="font-size:12px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $emp)
                            <tr>
                                <td class="text-muted" style="font-size:12px;">
                                    {{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}
                                </td>
                                <td style="font-size:12px;">
                                    <div class="fw-semibold">{{ $emp->emp_name }}</div>
                                    @if($emp->company_name)
                                    <div class="text-muted" style="font-size:10px;">{{ $emp->company_name }}</div>
                                    @endif
                                </td>
                                <td style="font-size:12px;"><code>{{ $emp->employee_id }}</code></td>
                                <td style="font-size:12px;">{{ $emp->emp_code ?? '—' }}</td>
                                <td style="font-size:12px;">{{ $emp->emp_department ?? '—' }}</td>
                                <td style="font-size:12px;">{{ $emp->emp_designation ?? '—' }}</td>
                                <td style="font-size:12px;">
                                    @if($emp->emp_email)
                                        <a href="mailto:{{ $emp->emp_email }}" class="text-decoration-none">{{ $emp->emp_email }}</a>
                                    @else —
                                    @endif
                                </td>
                                <td style="font-size:12px;">{{ $emp->emp_contact ?? '—' }}</td>
                                <td>
                                    @if($emp->emp_status === 'A')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($emp->emp_status === 'D')
                                        <span class="badge bg-danger">Deactivated</span>
                                    @elseif($emp->emp_status === 'I')
                                        <span class="badge bg-warning">Inactive</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $emp->emp_status ?? '—' }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="ri-user-search-line fs-2 d-block mb-2 opacity-25"></i>
                                    No employees found matching your filters
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    @if($employees->total() > 0)
                        Showing {{ $employees->firstItem() }} – {{ $employees->lastItem() }} of {{ number_format($employees->total()) }} employees
                    @else
                        No results
                    @endif
                </small>
                {{ $employees->links() }}
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-submit on dropdown change
    ['statusSelect','deptSelect','desigSelect'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', function() {
            document.getElementById('empFilterForm').submit();
        });
    });
});
</script>
@endsection
