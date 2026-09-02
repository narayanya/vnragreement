@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">Role Management</h3>
                <p class="mb-0" style="font-size:13px;color:#71809a">Create and manage user roles</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge rounded-pill px-3 py-2" style="background:#e6f4f3;color:#187b78;font-size:12px;font-weight:600;">
                    <i class="ri-shield-keyhole-line me-1"></i>{{ $roles->count() }} Roles
                </span>
                <button class="btn btn-sm btn-primary" id="addRoleBtn">
                    <i class="ri-add-line me-1"></i>Add Role
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

        {{-- Table --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:13px">
                            @forelse($roles as $i => $role)
                            <tr>
                                <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:32px;height:32px;border-radius:8px;background:#e6f4f3;color:#187b78;display:grid;place-items:center;font-size:13px;font-weight:800;flex-shrink:0;">
                                            {{ strtoupper(substr($role->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-semibold" style="color:#172b4d">{{ $role->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <code style="background:#f6f8fb;color:#3d5068;padding:2px 7px;border-radius:4px;font-size:12px;border:1px solid #e7ebf2">
                                        {{ $role->slug }}
                                    </code>
                                </td>
                                <td class="text-muted">{{ $role->description ?: '-' }}</td>
                                <td>
                                    @if($role->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    {{-- Edit --}}
                                    <button class="btn btn-sm btn-outline-warning editRoleBtn"
                                        data-id="{{ $role->id }}"
                                        data-name="{{ $role->name }}"
                                        data-slug="{{ $role->slug }}"
                                        data-description="{{ $role->description }}"
                                        data-is_active="{{ $role->is_active ? '1' : '0' }}"
                                        title="Edit">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    {{-- Toggle status --}}
                                    <form action="{{ route('settings.roles.toggle', $role) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $role->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                            title="{{ $role->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="ri-{{ $role->is_active ? 'forbid' : 'checkbox-circle' }}-line"></i>
                                        </button>
                                    </form>
                                    {{-- Delete --}}
                                    <form action="{{ route('settings.roles.destroy', $role) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete role \'{{ $role->name }}\'?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="ri-shield-keyhole-line" style="font-size:36px;opacity:.2;display:block;margin-bottom:8px"></i>
                                    No roles yet. Click <strong>Add Role</strong> to create one.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('modals')

{{-- ── Add / Edit Role Modal ─────────────────────────────── --}}
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">
                    <i class="ri-add-line me-1"></i>Add Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="roleForm" method="POST" action="{{ route('settings.roles.store') }}">
                @csrf
                <div class="modal-body">

                    @if($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3" style="font-size:13px">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="roleName" class="form-control"
                               placeholder="e.g. Admin, Manager, Viewer"
                               value="{{ old('name') }}" required
                               oninput="autoSlug(this.value)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug <span class="text-muted" style="font-size:11px">(auto-generated)</span></label>
                        <input type="text" name="slug" id="roleSlug" class="form-control"
                               placeholder="e.g. admin"
                               value="{{ old('slug') }}">
                        <small class="text-muted">Leave blank to auto-generate from name.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="roleDescription" class="form-control" rows="3"
                                  placeholder="What can this role do?">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-1" id="statusRow" style="display:none">
                        <label class="form-label d-block">Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" id="statusActive" value="1" checked>
                            <label class="form-check-label" for="statusActive">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" id="statusInactive" value="0">
                            <label class="form-check-label" for="statusInactive">Inactive</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="roleSubmitBtn">
                        <i class="ri-save-line me-1"></i>Save Role
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

    const modalEl   = document.getElementById('roleModal');
    const modal     = bootstrap.Modal.getOrCreateInstance(modalEl);
    const form      = document.getElementById('roleForm');
    const statusRow = document.getElementById('statusRow');

    // Auto-generate slug from name
    window.autoSlug = function (val) {
        const slugEl = document.getElementById('roleSlug');
        if (!slugEl.dataset.manual) {
            slugEl.value = val.toLowerCase()
                              .replace(/[^a-z0-9\s-]/g, '')
                              .trim()
                              .replace(/\s+/g, '-');
        }
    };

    document.getElementById('roleSlug').addEventListener('input', function () {
        this.dataset.manual = this.value ? '1' : '';
    });

    // ── Add Role ─────────────────────────────────────────────
    document.getElementById('addRoleBtn').addEventListener('click', function () {
        form.reset();
        form.action = '{{ route("settings.roles.store") }}';
        document.getElementById('roleModalLabel').innerHTML = '<i class="ri-add-line me-1"></i>Add Role';
        document.getElementById('roleSubmitBtn').innerHTML  = '<i class="ri-save-line me-1"></i>Save Role';
        document.getElementById('roleSlug').dataset.manual  = '';
        statusRow.style.display = 'none';

        const m = form.querySelector('input[name="_method"]');
        if (m) m.remove();

        modal.show();
    });

    // ── Edit Role ─────────────────────────────────────────────
    document.querySelectorAll('.editRoleBtn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const d = this.dataset;

            form.reset();
            form.action = '{{ url("settings/roles") }}/' + d.id;

            // Inject PATCH
            let mi = form.querySelector('input[name="_method"]');
            if (!mi) {
                mi = document.createElement('input');
                mi.type = 'hidden';
                mi.name = '_method';
                form.appendChild(mi);
            }
            mi.value = 'PATCH';

            document.getElementById('roleModalLabel').innerHTML = '<i class="ri-edit-line me-1"></i>Edit Role';
            document.getElementById('roleSubmitBtn').innerHTML  = '<i class="ri-save-line me-1"></i>Update Role';

            document.getElementById('roleName').value        = d.name;
            document.getElementById('roleSlug').value        = d.slug;
            document.getElementById('roleSlug').dataset.manual = '1';
            document.getElementById('roleDescription').value = d.description || '';

            // Show status toggle on edit
            statusRow.style.display = '';
            document.querySelectorAll('input[name="is_active"]').forEach(function (r) {
                r.checked = (r.value === d.is_active);
            });

            modal.show();
        });
    });

    // If there are validation errors, re-open modal with old input
    @if($errors->any())
        modal.show();
    @endif
});
</script>
@endpush
