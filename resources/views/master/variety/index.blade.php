@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <div>
                <h3 class="mb-1">Variety</h3>
                <small class="text-muted">Core and custom variety management</small>
            </div>
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('master.variety.sync') }}">@csrf<button class="btn btn-outline-primary btn-sm">Sync Variety Data</button></form>
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
                <a href="{{ route('master.variety.index', ['tab' => 'core']) }}"
                   class="nav-link {{ $tab === 'core' ? 'active' : '' }}"
                   role="tab">
                   Core Variety
                   @if(method_exists($syncedVarieties,'total'))
                       <span class="badge ms-1 {{ $tab==='core' ? 'bg-primary' : 'bg-secondary' }}">{{ number_format($syncedVarieties->total()) }}</span>
                   @endif
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('master.variety.index', ['tab' => 'custom']) }}"
                   class="nav-link {{ $tab === 'custom' ? 'active' : '' }}"
                   role="tab">
                   Custom Variety
                   @if(method_exists($customVarieties,'total'))
                       <span class="badge ms-1 {{ $tab==='custom' ? 'bg-primary' : 'bg-secondary' }}">{{ number_format($customVarieties->total()) }}</span>
                   @endif
                </a>
            </li>
        </ul>

        <div class="tab-content" id="varietyTabsContent">

            {{-- Core Variety --}}
            <div class="{{ $tab === 'core' ? 'd-block' : 'd-none' }}">
                @if (method_exists($syncedVarieties,'isEmpty') ? $syncedVarieties->isEmpty() : $syncedVarieties->count() === 0)
                    <div class="card">
                        <div class="card-body text-center text-muted py-5">
                            <i class="ri-leaf-line" style="font-size:36px;color:#c8d6e5;display:block;margin-bottom:8px"></i>
                            No core variety data available.
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
                                            <th>Code</th>
                                            <th>Status</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($syncedVarieties as $variety)
                                        <tr>
                                            <td class="ps-3 text-muted">
                                                {{ ($syncedVarieties->currentPage()-1)*$syncedVarieties->perPage()+$loop->iteration }}
                                            </td>
                                            <td class="fw-semibold" style="color:#172b4d">{{ $variety->name ?? '-' }}</td>
                                            <td><span class="badge bg-info">{{ $variety->code ?? '-' }}</span></td>
                                            <td>
                                                @if(($variety->status ?? 0) == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-muted">{{ $variety->remark ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($syncedVarieties->hasPages())
                        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <small class="text-muted">
                                Showing {{ $syncedVarieties->firstItem() }} to {{ $syncedVarieties->lastItem() }}
                                of {{ number_format($syncedVarieties->total()) }} varieties
                            </small>
                            {{ $syncedVarieties->links() }}
                        </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Custom Variety --}}
            <div class="{{ $tab === 'custom' ? 'd-block' : 'd-none' }}">
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
                                            <td class="text-muted">{{ $variety->com_name ?? '-' }}</td>
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
                <form method="POST" action="{{ route('master.variety.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="varietyModalLabel">Add Variety</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Variety Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remark</label>
                            <input type="text" name="remark" class="form-control" value="custom">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection