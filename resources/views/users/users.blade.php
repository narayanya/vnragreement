@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-sage-muted/20 pb-2">
                <div class="items-center gap-3">
                    <h3 class="mb-0 fw-bold" style="color:#172b4d">
                       User Management
                    </h3>
                    <p class="mb-0" style="font-size:13px;color:#71809a">Create and manage system users, roles, and permissions</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge rounded-pill px-3 py-2" style="background:#e6f4f3;color:#187b78;font-size:12px;font-weight:600;">
                        <i class="ri-group-line me-1"></i>{{ $totalCount }} Users
                    </span>
                    <a href="#addUserForm" class="btn btn-sm btn-primary" onclick="document.getElementById('addUserForm').scrollIntoView({behavior:'smooth'})">
                        <i class="ri-user-add-line me-1"></i>Add User
                    </a>
                </div>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Two Column Layout -->
            <div class="row g-4">
                <!-- Left Column: Add User Form -->
                <div class="col-lg-4" id="addUserForm">
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0" id="formCardTitle">
                                <i class="ri-add-line me-2"></i>Add New Internal User
                            </h5>
                            <button type="button" id="addExternalBtn" class="btn btn-sm btn-primary">
                                <i class="ri-add-line me-1"></i> External User
                            </button>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="is_external" id="is_external" value="0">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error creating user:</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                                <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Departments <span class="text-danger">*</span></label>
                                    <select name="departments" id="department" class="form-select" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept }}">{{ $dept }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Employees<span class="text-danger">*</span></label>
                                    <select name="employees" id="employee" class="form-select" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $emp)
                                            <option 
                                                value="{{ $emp->employee_id }}"
                                                data-name="{{ $emp->emp_name }}"
                                                data-email="{{ $emp->emp_email }}"
                                                data-dept="{{ $emp->emp_department }}"
                                                data-code="{{ $emp->emp_code }}"
                                                data-reporting="{{ $emp->emp_reporting }}"
                                                data-empId="{{ $emp->employee_id }}"
                                                data-mobile="{{ $emp->emp_contact }}"
                                            >
                                                {{ $emp->emp_name }}({{ $emp->emp_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Employee Code</label>
                                    <input type="text" id="emp_code" class="form-control" name="emp_code"
                                           value="{{ old('emp_code') }}" placeholder="Auto-filled"
                                           style="background:#f6f8fb;" tabindex="-1">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" id="mobile_number" class="form-control" name="mobile_number"
                                           value="{{ old('mobile_number') }}" placeholder="Auto-filled"
                                           style="background:#f6f8fb;" tabindex="-1">
                                </div>
                            </div>

                           
                            <div id="reportingManagerCard" class="card border bg-light mb-3 d-none">
                                <div class="card-header py-2 bg-white d-flex align-items-center gap-2">
                                    <i class="ri-user-star-line text-primary"></i>
                                    <strong class="small">Reporting Manager</strong>
                                </div>
                                <div class="card-body py-2 small">
                                    <div class="row g-1">
                                        <div class="col-6"><span class="text-muted">Name:</span> <span id="rm_name">—</span></div>
                                        <div class="col-6"><span class="text-muted">Code:</span> <span id="rm_code">—</span></div>
                                        <div class="col-6"><span class="text-muted">Email:</span> <span id="rm_email">—</span></div>
                                        <div class="col-6"><span class="text-muted">Mobile:</span> <span id="rm_mobile">—</span></div>
                                        <div class="col-6"><span class="text-muted">Dept:</span> <span id="rm_dept">—</span></div>
                                        <div class="col-6"><span class="text-muted">Designation:</span> <span id="rm_desig">—</span></div>
                                        <div class="col-12 mt-1">
                                            <span id="rm_user_badge"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
                                {{-- Hidden fields auto-filled by JS on employee select --}}
                                <input type="hidden" id="name"          name="name"          value="{{ old('name') }}">
                                <input type="hidden" id="emp_reporting" name="emp_reporting" value="{{ old('emp_reporting') }}">
                                <input type="hidden" id="employee_id"   name="employee_id"   value="{{ old('employee_id') }}">

                                {{-- Visible name field shown only for external users --}}
                                <div class="mb-3 d-none" id="externalNameDiv">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" id="external_name" class="form-control" placeholder="Enter full name">
                                    <small class="text-muted">Name is auto-filled for internal employees.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}" required placeholder="rohit@example.com"
                                           style="background:#f6f8fb;" tabindex="-1">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                

                                <div class="mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Minimum 8 characters">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Assign Roles <small class="text-muted">(Select at least one)</small></label>
                                    <div class="border rounded p-3 bg-light">
                                        @forelse($roles as $role)
                                            <div class="mb-1 pb-3 @if(!$loop->last) border-bottom @endif">
                                                <div class="form-check">
                                                    <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role{{ $role->id }}" {{ old('roles') && in_array($role->id, old('roles')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="role{{ $role->id }}">
                                                        <strong>{{ $role->name }}</strong>
                                                    </label>
                                                </div>
                                                <small class="text-muted d-block ms-4 mt-1">{{ $role->description }}</small>
                                            </div>
                                        @empty
                                            <p class="text-muted mb-0">No roles available</p>
                                        @endforelse
                                    </div>
                                </div>

                                

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-add-line me-1"></i>Create User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Users Table -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="ri-group-line me-2"></i>Users List
                            </h5>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style="min-height:400px;">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Roles</th>
                                            <th>PDF Download</th>
                                            <th>Created Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    @if($is_external = $user->is_external == 1)
                                                        <span class="avatar-title rounded-circle bg-danger-subtle text-danger">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    @else
                                                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="fw-semibold">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }} <br> 
                                            <small class="text-success">Dept.: {{ $user->employee->emp_department ?? 'N/A' }}</small></td>
                                        <td>
                                            @if($user->role)
                                                <span class="badge bg-primary">{{ $user->role->name }}</span>
                                            @else
                                                <span class="text-muted">No role assigned</span>
                                            @endif
                                        </td>
                                        {{-- PDF Download toggle --}}
                                        <td>
                                            <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
                                                <input
                                                    class="form-check-input pdf-toggle"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="pdfToggle{{ $user->id }}"
                                                    data-user-id="{{ $user->id }}"
                                                    
                                                    style="width:2.2em;height:1.2em;cursor:pointer;"
                                                    {{ $user->can_download_pdf ? 'checked' : '' }}>
                                                <label class="form-check-label small fw-semibold" for="pdfToggle{{ $user->id }}" id="pdfLabel{{ $user->id }}">
                                                    @if($user->can_download_pdf)
                                                        <span class="text-success">Yes</span>
                                                    @else
                                                        <span class="text-danger">No</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            @if($user->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editRolesModal{{ $user->id }}">
                                                        <i class="ri-shield-line me-2"></i>Manage Roles
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                                        <i class="ri-edit-line me-2"></i>Edit
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                                <i class="ri-delete-bin-line me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Roles Modal for this user -->
                                    <div class="modal fade" id="editRolesModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Manage Roles - {{ $user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <h6>Current Roles</h6>
                                                        <div class="mb-3">
                                                            @forelse($user->roles ?? [] as $role)
                                                                <div class="d-flex justify-content-between align-items-center p-2 border rounded mb-2">
                                                                    <span>{{ $role->name }}</span>
                                                                    <form action="{{ route('users.removeRole', $user->id) }}" method="POST" style="display: inline;">
                                                                        @csrf
                                                                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                                                    </form>
                                                                </div>
                                                            @empty
                                                                <p class="text-muted">No roles assigned</p>
                                                            @endforelse
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <h6>Assign Role</h6>

                                                        <form action="{{ route('users.assignRole', $user->id) }}" method="POST">
                                                            @csrf

                                                            <div class="mb-3">
                                                                <select class="form-select" name="role_id" required>
                                                                    <option value="">-- Select Role --</option>

                                                                    @foreach($roles as $role)
                                                                        <option value="{{ $role->id }}"
                                                                            {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                                            {{ $role->name }}
                                                                        </option>
                                                                    @endforeach

                                                                </select>
                                                            </div>

                                                            <button type="submit" class="btn btn-primary">
                                                                Update Role
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit User Modal for this user -->
                                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit User - {{ $user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('users.update', $user) }}" method="POST">
                                                    <div class="modal-body">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                                            <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                                        </div>
                                                        <div class="row g-2">
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">Emp Code</label>
                                                                <input type="text" class="form-control" name="emp_code" value="{{ $user->emp_code }}">
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">Employee ID</label>
                                                                <input type="text" class="form-control" name="employee_id" value="{{ $user->employee_id }}">
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">Mobile Number</label>
                                                                <input type="text" class="form-control" name="mobile_number" value="{{ $user->mobile_number }}">
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label class="form-label">Reporting ID</label>
                                                                <input type="text" class="form-control" name="emp_reporting" value="{{ $user->emp_reporting }}">
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Role</label>
                                                            <select name="role_id" class="form-select">
                                                                <option value="">-- No Role --</option>
                                                                @foreach($roles as $role)
                                                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                                        {{ $role->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                                                            <input type="password" class="form-control" name="password" placeholder="Min 8 characters">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Confirm Password</label>
                                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Repeat new password">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update User</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No users found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3 px-1">
                        <small class="text-muted">
                            Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users
                        </small>
                        {{ $users->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
<style>
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    .dropdown-menu {
        min-width: 180px;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const department = document.getElementById('department');
    const employee   = document.getElementById('employee');
    const card       = document.getElementById('reportingManagerCard');

    // Store all employee options (original)
    const allOptions = Array.from(employee.querySelectorAll('option'));

    // ---------- RESET FUNCTION ----------
    function resetEmployeeDetails() {
        document.getElementById('name').value          = '';
        document.getElementById('email').value         = '';
        document.getElementById('mobile_number').value = '';
        document.getElementById('emp_code').value      = '';
        document.getElementById('emp_reporting').value = '';
        document.getElementById('employee_id').value   = '';
        document.getElementById('password').value      = '';
        card.classList.add('d-none');
    }

    // ---------- FILTER EMPLOYEES ----------
    function filterEmployees(dept, keepSelected = false) {
        let selectedValue = keepSelected ? employee.value : '';

        employee.innerHTML = '<option value="">Select Employee</option>';

        allOptions.forEach(option => {
            if (!option.value) return;

            let empDept = option.getAttribute('data-dept');

            if (dept === '' || empDept === dept) {
                let clone = option.cloneNode(true);

                if (keepSelected && option.value === selectedValue) {
                    clone.selected = true;
                }

                employee.appendChild(clone);
            }
        });

        if (!keepSelected) {
            employee.value = '';
            resetEmployeeDetails();
        }
    }

    // ---------- CASE 1 ----------
    // First load → show all employees
    filterEmployees('');

    // ---------- CASE 2 ----------
    // Department change → filter employees + reset
    department.addEventListener('change', function () {
        filterEmployees(this.value);
    });

    // ---------- CASE 3 ----------
    // Employee selected → auto-select department + fill details
    employee.addEventListener('change', function () {
        let selected = this.options[this.selectedIndex];
        if (!selected.value) return;

        // Capture all data BEFORE filterEmployees re-renders the dropdown
        const empData = {
            name:      selected.dataset.name      || '',
            email:     selected.dataset.email     || '',
            mobile:    selected.dataset.mobile    || '',
            code:      selected.dataset.code      || '',
            reporting: selected.dataset.reporting || '',
            empid:     selected.dataset.empid     || selected.value || '',
            dept:      selected.getAttribute('data-dept') || '',
        };

        // Auto select department and re-filter (keeps selection)
        if (empData.dept && department.value !== empData.dept) {
            department.value = empData.dept;
            filterEmployees(empData.dept, true);
        }

        // Fill ALL fields AFTER potential re-render
        document.getElementById('name').value          = empData.name;
        document.getElementById('email').value         = empData.email;
        document.getElementById('mobile_number').value = empData.mobile;
        document.getElementById('emp_code').value      = empData.code;
        document.getElementById('emp_reporting').value = empData.reporting;
        document.getElementById('employee_id').value   = empData.empid;
        document.getElementById('password').value      = empData.mobile;

        // Reporting manager card
        if (empData.reporting && empData.reporting !== '0') {
            fetch(`/employee/${empData.reporting}`)
                .then(r => r.ok ? r.json() : null)
                .then(m => {
                    if (!m) return card.classList.add('d-none');
                    document.getElementById('rm_name').textContent   = m.emp_name       || '—';
                    document.getElementById('rm_code').textContent   = m.emp_code       || '—';
                    document.getElementById('rm_email').textContent  = m.emp_email      || '—';
                    document.getElementById('rm_mobile').textContent = m.emp_contact    || '—';
                    document.getElementById('rm_dept').textContent   = m.emp_department || '—';
                    document.getElementById('rm_desig').textContent  = m.emp_designation|| '—';
                    fetch(`/check-user?emp_code=${m.emp_code}`)
                        .then(r => r.json())
                        .then(u => {
                            document.getElementById('rm_user_badge').innerHTML = u.exists
                                ? '<span class="badge bg-success">Already a User</span>'
                                : '<span class="badge bg-warning text-dark">Not yet a user</span>';
                        }).catch(() => {});
                    card.classList.remove('d-none');
                })
                .catch(() => card.classList.add('d-none'));
        } else {
            card.classList.add('d-none');
        }
    });

    // ---------- CASE 4 ----------
    // If department changes AFTER selecting employee → reset everything handled in filterEmployees()
    const externalBtn  = document.getElementById('addExternalBtn');
    const isExternal   = document.getElementById('is_external');
    const formCardTitle = document.getElementById('formCardTitle');

    const departmentDiv = document.getElementById('department').closest('.mb-3');
    const employeeDiv   = document.getElementById('employee').closest('.mb-3');

    const empCodeDiv = document.getElementById('emp_code').closest('.mb-3');

    const reportingCard = document.getElementById('reportingManagerCard');

    const emailField    = document.getElementById('email');
    const mobileField   = document.getElementById('mobile_number');
    const empCodeField  = document.getElementById('emp_code');
    const departmentField = document.getElementById('department');
    const employeeField   = document.getElementById('employee');

    let externalMode = false;

    externalBtn.addEventListener('click', function () {

        externalMode = !externalMode;

        if (externalMode) {

            isExternal.value = 1;

            departmentField.required = false;
            employeeField.required   = false;

            externalBtn.innerHTML = '<i class="ri-add-line me-1"></i> Internal User';
            if (formCardTitle) formCardTitle.innerHTML = '<i class="ri-add-line me-2"></i>Add New External User';

            departmentDiv.classList.add('d-none');
            employeeDiv.classList.add('d-none');
            empCodeDiv.classList.add('d-none');
            reportingCard.classList.add('d-none');

            // Show visible name input, unlock email for typing
            document.getElementById('externalNameDiv').classList.remove('d-none');
            emailField.style.background = '';
            emailField.removeAttribute('tabindex');
            mobileField.style.background = '';

            // Clear all fields
            document.getElementById('name').value          = '';
            document.getElementById('external_name').value = '';
            emailField.value    = '';
            mobileField.value   = '';
            empCodeField.value  = '';

        } else {

            isExternal.value = 0;

            externalBtn.innerHTML = '<i class="ri-add-line me-1"></i> External User';
            if (formCardTitle) formCardTitle.innerHTML = '<i class="ri-add-line me-2"></i>Add New Internal User';

            departmentDiv.classList.remove('d-none');
            employeeDiv.classList.remove('d-none');
            empCodeDiv.classList.remove('d-none');

            // Hide external name, lock email back to auto-fill only
            document.getElementById('externalNameDiv').classList.add('d-none');
            emailField.style.background = '#f6f8fb';
            emailField.setAttribute('tabindex', '-1');
            mobileField.style.background = '#f6f8fb';

            departmentField.required = true;
            employeeField.required   = true;

            document.getElementById('name').value = '';
            emailField.value    = '';
            mobileField.value   = '';
            empCodeField.value  = '';
        }
    });

    // Sync visible external name field → hidden name field
    const externalNameInput = document.getElementById('external_name');
    if (externalNameInput) {
        externalNameInput.addEventListener('input', function () {
            document.getElementById('name').value = this.value;
        });
    }

    // ── PDF Download toggle ───────────────────────────────────
    document.querySelectorAll('.pdf-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const userId  = this.dataset.userId;
            const url     = this.dataset.url;
            const checked = this.checked;
            const label   = document.getElementById('pdfLabel' + userId);

            // Optimistic UI update
            label.innerHTML = checked
                ? '<span class="text-success">Yes</span>'
                : '<span class="text-danger">No</span>';

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                 || '{{ csrf_token() }}',
                    'Accept'      : 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(function (res) {
                if (!res.ok) throw new Error('Request failed');
                return res.json();
            })
            .then(function (data) {
                // Sync checkbox with server truth
                toggle.checked = data.can_download_pdf;
                label.innerHTML = data.can_download_pdf
                    ? '<span class="text-success">Yes</span>'
                    : '<span class="text-danger">No</span>';
            })
            .catch(function () {
                // Revert on error
                toggle.checked = !checked;
                label.innerHTML = !checked
                    ? '<span class="text-success">Yes</span>'
                    : '<span class="text-danger">No</span>';
                alert('Could not update PDF download permission. Please try again.');
            });
        });
    });

});
</script>
@endsection
