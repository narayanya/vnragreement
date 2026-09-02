@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <div>
                <h3 class="mb-1">Custom Variety</h3>
                <small class="text-muted">Other company variety management</small>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#varietyModal">Add Variety</button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <ul class="nav nav-tabs mb-3" id="varietyTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="{{ route('master.variety.index', ['tab' => 'custom']) }}"
                   class="nav-link active"
                   role="tab">
                   Custom Variety
                   @if(method_exists($customVarieties,'total'))
                       <span class="badge ms-1 bg-primary">{{ number_format($customVarieties->total()) }}</span>
                   @endif
                </a>
            </li>
        </ul>

        <div class="tab-content" id="varietyTabsContent">

            {{-- Custom Variety --}}
            <div class="d-block">
                @if (method_exists($customVarieties,'isEmpty') ? $customVarieties->isEmpty() : $customVarieties->count() === 0)
                    <div class="card">
                        <div class="card-body text-center text-muted py-5">
                            <i class="ri-plant-line" style="font-size:36px;color:#c8d6e5;display:block;margin-bottom:8px"></i>
                            No custom variety data available.
                            <div class="mt-3">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#varietyModal">
                                    <i class="ri-add-line me-1"></i>Add First Variety
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3" style="width:50px">#</th>
                                            <th>Name</th>
                                            <th>Catalogue No</th>
                                            <th>Company</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th class="text-end pe-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customVarieties as $variety)
                                        <tr>
                                            <td class="ps-3 text-muted">
                                                {{ ($customVarieties->currentPage()-1)*$customVarieties->perPage()+$loop->iteration }}
                                            </td>
                                            <td class="fw-semibold" style="color:#172b4d">
                                                {{ $variety->ver_main ?: ($variety->ver_alias ?: '-') }}
                                            </td>
                                            <td><span class="badge bg-info">{{ $variety->catalogue_no ?? '-' }}</span></td>
                                            <td class="text-muted">{{ $variety->company?->com_main ?: ($variety->com_name ?? '-') }}</td>
                                            <td>
                                                @if(($variety->Sts ?? 'A') === 'A')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-muted">
                                                {{ $variety->cr_date ? \Carbon\Carbon::parse($variety->cr_date)->format('d M Y') : '-' }}
                                            </td>
                                            <td class="text-end pe-3">
                                                <button class="btn btn-sm btn-outline-warning editVarietyBtn"
                                                    data-id="{{ $variety->ver_id }}"
                                                    data-ver_main="{{ $variety->ver_main }}"
                                                    data-ver_alias="{{ $variety->ver_alias }}"
                                                    data-catalogue_no="{{ $variety->catalogue_no }}"
                                                    data-com_id="{{ $variety->com_id }}"
                                                    data-sts="{{ $variety->Sts }}"
                                                    title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                <form action="{{ route('master.variety.destroy', $variety->ver_id) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Delete this variety?')">
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
                        @if($customVarieties->hasPages())
                        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <small class="text-muted">
                                Showing {{ $customVarieties->firstItem() }} to {{ $customVarieties->lastItem() }}
                                of {{ number_format($customVarieties->total()) }} varieties
                            </small>
                            {{ $customVarieties->links() }}
                        </div>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>

    <div class="modal fade" id="varietyModal" tabindex="-1" aria-labelledby="varietyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="varietyForm" action="{{ route('master.variety.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="varietyModalLabel">Add Variety</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Variety Name</label>
                            <input type="text" name="ver_main" id="ver_main" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Variety Alias</label>
                            <input type="text" name="ver_alias" id="ver_alias" class="form-control">
                        </div>
                        <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Catalogue No</label>
                            <input type="text" name="catalogue_no" id="catalogue_no" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="Sts" id="Sts" class="form-select">
                                <option value="A">Active</option>
                                <option value="I">Inactive</option>
                            </select>
                        </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Company</label>
                            <select name="com_id" id="com_id" class="form-select">
                                <option value="">-- Select Company --</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="varietySubmitBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('varietyForm');
        const modal = new bootstrap.Modal(document.getElementById('varietyModal'));

        // Add Variety Button
        document.querySelector('[data-bs-target="#varietyModal"]').addEventListener('click', function () {
            form.reset();
            form.action = '{{ route("master.variety.store") }}';
            form.querySelector('input[name="_method"]')?.remove();
            document.getElementById('varietyModalLabel').textContent = 'Add Variety';
            document.getElementById('varietySubmitBtn').textContent = 'Save';
        });

        // Edit Variety Button
        document.querySelectorAll('.editVarietyBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                const d = this.dataset;
                form.action = '{{ url("master/variety") }}/' + d.id;
                document.getElementById('varietyModalLabel').textContent = 'Edit Variety';
                document.getElementById('varietySubmitBtn').textContent = 'Update';

                document.getElementById('ver_main').value = d.ver_main || '';
                document.getElementById('ver_alias').value = d.ver_alias || '';
                document.getElementById('catalogue_no').value = d.catalogue_no || '';
                document.getElementById('com_id').value = d.com_id || '';
                document.getElementById('Sts').value = d.sts || 'A';

                // Add PATCH method
                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.appendChild(methodInput);
                }
                methodInput.value = 'PATCH';

                modal.show();
            });
        });

        // Load companies
        fetch('{{ route("master.variety.companies") }}')
            .then(r => r.json())
            .then(companies => {
                const select = document.getElementById('com_id');
                companies.forEach(c => {
                    const option = document.createElement('option');
                    option.value = c.id;
                    option.textContent = c.com_main;
                    select.appendChild(option);
                });
            });
    });
    </script>
@endsection