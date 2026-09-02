@extends('layouts.app')

@section('content')
<div class="col-12">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-1">
        <div>
            <h3 class="mb-0">Permission Management</h3>
            <p class="text-muted mb-0" style="font-size:13px">Configure module access per role — toggle switches to grant or revoke permissions, then Save</p>
        </div>
        <a href="{{ route('settings') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i>Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $groupMeta = [
            'core'     => ['label' => 'Core',     'icon' => 'ri-settings-3-line',  'color' => 'primary'],
            'data'     => ['label' => 'Data',     'icon' => 'ri-database-2-line',  'color' => 'info'],
            'workflow' => ['label' => 'Workflow',  'icon' => 'ri-flow-chart',       'color' => 'warning'],
            'access'   => ['label' => 'Access',   'icon' => 'ri-key-line',         'color' => 'success'],
            'admin'    => ['label' => 'Admin',    'icon' => 'ri-shield-user-line', 'color' => 'danger'],
            'menu'     => ['label' => 'Menu',     'icon' => 'ri-menu-line',        'color' => 'secondary'],
        ];

        $moduleIcons = [
            'field-books'      => 'ri-book-open-line',
            'book-entry'       => 'ri-file-text-line',
            'crops'            => 'ri-plant-line',
            'crop-categories'  => 'ri-folder-line',
            'crop-types'       => 'ri-seedling-line',
            'seasons'          => 'ri-sun-line',
            'soil-types'       => 'ri-landscape-line',
            'storage'          => 'ri-archive-line',
            'sub-locations'    => 'ri-building-3-line',
            'floors'           => 'ri-stack-line',
            'cabinets'         => 'ri-inbox-line',
            'shelves'          => 'ri-layout-row-line',
            'inter-transfer'   => 'ri-arrow-left-right-line',
            'reports'          => 'ri-bar-chart-line',
            'users'            => 'ri-user-line',
            'roles'            => 'ri-shield-star-line',
            'permissions'      => 'ri-lock-unlock-line',
            'menu'             => 'ri-menu-2-line',
        ];
    @endphp

    <form method="POST" action="{{ route('settings.permission.save') }}" id="permForm">
        @csrf

        <div class="row g-3">

            {{-- ══════════════════════════════════════════════
                 LEFT — Role list (sticky)
            ══════════════════════════════════════════════ --}}
            <div class="col-lg-3 col-md-4" style="z-index:0;">
                <div class="card sticky-top" style="top:72px;">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0"><i class="ri-shield-user-line me-1"></i>Roles</h6>
                    </div>
                    <div class="list-group list-group-flush" id="roleList">
                        @foreach($roles as $role)
                        <button type="button"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center role-tab {{ $loop->first ? 'active' : '' }}"
                            data-role="{{ $role->id }}">
                            <div class="text-start">
                                <div class="fw-semibold">{{ $role->name }}</div>
                                <small class="opacity-75 font-monospace">{{ $role->slug }}</small>
                            </div>
                            @if($role->slug === 'super-admin')
                                <span class="badge bg-danger flex-shrink-0">All</span>
                            @else
                                <span class="badge bg-primary rounded-pill flex-shrink-0 perm-count-badge"
                                      data-role="{{ $role->id }}">
                                    {{ $role->permissions->count() }}
                                </span>
                            @endif
                        </button>
                        @endforeach
                    </div>
                    <div class="card-footer d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>Save Permissions
                        </button>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════
                 RIGHT — Permission panels per role
            ══════════════════════════════════════════════ --}}
            <div class="col-lg-9 col-md-8">
                @foreach($roles as $role)
                @php
                    $rolePermSlugs = $role->permissions->pluck('slug')->toArray();
                    $isSuperAdmin  = ($role->slug === 'super-admin');
                @endphp

                <div class="role-panel {{ $loop->first ? '' : 'd-none' }}" data-role="{{ $role->id }}">

                    {{-- ── Super-admin banner ───────────────── --}}
                    @if($isSuperAdmin)
                    <div class="alert alert-danger d-flex align-items-center gap-3 mb-3">
                        <i class="ri-shield-star-line fs-3"></i>
                        <div>
                            <strong>Super Admin — Full Access</strong><br>
                            <small>This role has unrestricted access to everything. Permissions are not configurable.</small>
                        </div>
                    </div>
                    {{-- Hidden inputs so POST still saves all for super-admin --}}
                    @foreach($permissions as $perm)
                        <input type="hidden" name="permissions[{{ $role->id }}][]" value="{{ $perm->slug }}">
                    @endforeach
                    @endif

                    {{-- ── Normal role header ───────────────── --}}
                    @if(!$isSuperAdmin)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="ri-settings-3-line me-1 text-primary"></i>{{ $role->name }}
                        </h6>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-success select-all-role" data-role="{{ $role->id }}">
                                <i class="ri-check-double-line me-1"></i>Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger clear-role" data-role="{{ $role->id }}">
                                <i class="ri-close-line me-1"></i>Clear All
                            </button>
                        </div>
                    </div>
                    @endif

                    {{-- ── Module cards ─────────────────────── --}}
                    @foreach($modules as $module => $groups)
                    @php
                        // All slugs in this module
                        $allSlugs = collect($groups)->flatten()->toArray();
                        $checkedCount = count(array_intersect($allSlugs, $rolePermSlugs));
                        $totalCount   = count($allSlugs);
                        $allChecked   = $totalCount > 0 && $checkedCount === $totalCount;
                        $partial      = $checkedCount > 0 && $checkedCount < $totalCount;
                        $icon         = $moduleIcons[$module] ?? 'ri-apps-line';
                        $moduleLabel  = ucwords(str_replace(['-','_'], ' ', $module));
                    @endphp

                    <div class="card mb-3 module-card {{ $isSuperAdmin ? 'opacity-75' : '' }}"
                         data-role="{{ $role->id }}" data-module="{{ $module }}">

                        {{-- Module header --}}
                        <div class="card-header d-flex align-items-center gap-2 py-2 bg-light">
                            <i class="{{ $icon }} text-primary"></i>
                            <span class="fw-semibold flex-grow-1">{{ $moduleLabel }}</span>

                            @if($partial && !$isSuperAdmin)
                                <span class="badge bg-warning text-dark module-partial-badge" style="font-size:10px;">Partial</span>
                            @endif

                            {{-- Module-level master toggle --}}
                            @if(!$isSuperAdmin)
                            <div class="d-flex align-items-center gap-1 ms-1">
                                <small class="text-muted" style="font-size:11px;">All</small>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input module-toggle"
                                        type="checkbox"
                                        data-role="{{ $role->id }}"
                                        data-module="{{ $module }}"
                                        style="width:2.2em;height:1.2em;"
                                        {{ $allChecked ? 'checked' : '' }}>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Permission toggles grouped by group --}}
                        <div class="card-body py-3">
                            @foreach($groups as $group => $slugs)
                            @php $gm = $groupMeta[$group] ?? ['label'=>$group,'icon'=>'ri-circle-line','color'=>'secondary']; @endphp

                            <div class="mb-3">
                                {{-- Group label --}}
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="{{ $gm['icon'] }} text-{{ $gm['color'] }} small"></i>
                                    <small class="fw-semibold text-muted text-uppercase" style="font-size:10px;letter-spacing:.5px;">
                                        {{ $gm['label'] }}
                                    </small>
                                    <hr class="flex-grow-1 my-0 ms-1">
                                </div>

                                {{-- Toggles --}}
                                <div class="d-flex flex-wrap gap-3 ps-2">
                                    @foreach($slugs as $slug)
                                    @php
                                        $perm    = $permissions->firstWhere('slug', $slug);
                                        $checked = in_array($slug, $rolePermSlugs);
                                        // Human-readable action label from slug suffix
                                        $actionLabel = ucwords(str_replace('_', ' ', explode('.', $slug, 2)[1] ?? $slug));
                                    @endphp

                                    <div class="d-flex flex-column align-items-center gap-1" style="min-width:64px;">
                                        <small class="text-muted text-center lh-sm" style="font-size:10px;">{{ $actionLabel }}</small>
                                        @if($perm && !$isSuperAdmin)
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input perm-check action-toggle"
                                                type="checkbox"
                                                name="permissions[{{ $role->id }}][]"
                                                value="{{ $slug }}"
                                                data-role="{{ $role->id }}"
                                                data-module="{{ $module }}"
                                                style="width:2.2em;height:1.2em;"
                                                {{ $checked ? 'checked' : '' }}>
                                        </div>
                                        @else
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox"
                                                style="width:2.2em;height:1.2em;"
                                                {{ ($isSuperAdmin || $checked) ? 'checked' : '' }}
                                                disabled>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                </div>{{-- /role-panel --}}
                @endforeach
            </div>
        </div>
    </form>
</div>

<style>
.role-tab.active {
    background-color: #0d6efd !important;
    color: #fff !important;
    border-color: #0d6efd !important;
}
.role-tab.active small,
.role-tab.active .opacity-75 { color: rgba(255,255,255,.75) !important; }
.form-check-input:checked:not(:disabled) { background-color: #198754; border-color: #198754; }
.module-card { transition: box-shadow .15s; }
.module-card:hover { box-shadow: 0 0 0 2px rgba(13,110,253,.2) !important; }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Role tab switching ─────────────────────────────────────
    document.querySelectorAll('.role-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const roleId = this.dataset.role;
            document.querySelectorAll('.role-panel').forEach(p => p.classList.add('d-none'));
            document.querySelector(`.role-panel[data-role="${roleId}"]`).classList.remove('d-none');
        });
    });

    // ── Module master toggle ────────────────────────────────────
    document.querySelectorAll('.module-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const { role, module } = this.dataset;
            document.querySelectorAll(`.action-toggle[data-role="${role}"][data-module="${module}"]`)
                    .forEach(cb => cb.checked = this.checked);
            updateBadge(role);
            updatePartialBadge(role, module);
        });
    });

    // ── Individual action toggle ────────────────────────────────
    document.querySelectorAll('.action-toggle').forEach(function (cb) {
        cb.addEventListener('change', function () {
            const { role, module } = this.dataset;
            syncModuleToggle(role, module);
            updateBadge(role);
            updatePartialBadge(role, module);
        });
    });

    // ── Select All ─────────────────────────────────────────────
    document.querySelectorAll('.select-all-role').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const role = this.dataset.role;
            document.querySelectorAll(`.action-toggle[data-role="${role}"]`).forEach(cb => cb.checked = true);
            document.querySelectorAll(`.module-toggle[data-role="${role}"]`).forEach(t => t.checked = true);
            document.querySelectorAll(`.role-panel[data-role="${role}"] .module-partial-badge`).forEach(b => b.classList.add('d-none'));
            updateBadge(role);
        });
    });

    // ── Clear All ──────────────────────────────────────────────
    document.querySelectorAll('.clear-role').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const role = this.dataset.role;
            document.querySelectorAll(`.action-toggle[data-role="${role}"]`).forEach(cb => cb.checked = false);
            document.querySelectorAll(`.module-toggle[data-role="${role}"]`).forEach(t => t.checked = false);
            document.querySelectorAll(`.role-panel[data-role="${role}"] .module-partial-badge`).forEach(b => b.classList.add('d-none'));
            updateBadge(role);
        });
    });

    // ── Helpers ────────────────────────────────────────────────
    function syncModuleToggle(role, module) {
        const actions = document.querySelectorAll(`.action-toggle[data-role="${role}"][data-module="${module}"]`);
        const allOk   = [...actions].every(cb => cb.checked);
        const tog     = document.querySelector(`.module-toggle[data-role="${role}"][data-module="${module}"]`);
        if (tog) tog.checked = allOk;
    }

    function updateBadge(role) {
        const n     = document.querySelectorAll(`.action-toggle[data-role="${role}"]:checked`).length;
        const badge = document.querySelector(`.perm-count-badge[data-role="${role}"]`);
        if (badge) badge.textContent = n;
    }

    function updatePartialBadge(role, module) {
        const card    = document.querySelector(`.module-card[data-role="${role}"][data-module="${module}"]`);
        if (!card) return;
        const all     = card.querySelectorAll('.action-toggle');
        const checked = card.querySelectorAll('.action-toggle:checked');
        let badge     = card.querySelector('.module-partial-badge');

        if (checked.length > 0 && checked.length < all.length) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'badge bg-warning text-dark module-partial-badge me-2';
                badge.style.fontSize = '10px';
                badge.textContent = 'Partial';
                card.querySelector('.card-header .flex-grow-1').after(badge);
            }
            badge.classList.remove('d-none');
        } else if (badge) {
            badge.classList.add('d-none');
        }
    }

});
</script>
@endpush
