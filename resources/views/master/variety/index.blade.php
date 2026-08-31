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
                <button class="nav-link active" id="core-tab" data-bs-toggle="tab" data-bs-target="#core-variety-tab" type="button" role="tab" aria-controls="core-variety-tab" aria-selected="true">Core Variety</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="custom-tab" data-bs-toggle="tab" data-bs-target="#custom-variety-tab" type="button" role="tab" aria-controls="custom-variety-tab" aria-selected="false">Custom Variety</button>
            </li>
        </ul>

        <div class="tab-content" id="varietyTabsContent">
            <div class="tab-pane fade show active" id="core-variety-tab" role="tabpanel" aria-labelledby="core-tab">
                @if ($syncedVarieties->isEmpty())
                    <div class="card">
                        <div class="card-body text-center text-muted py-5">No core variety data available.</div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Status</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($syncedVarieties as $variety)
                                            <tr>
                                                <td>{{ $variety->name ?? '-' }}</td>
                                                <td>{{ $variety->code ?? '-' }}</td>
                                                <td>
                                                    @if (($variety->status ?? 0) == 1)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ $variety->remark ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="tab-pane fade" id="custom-variety-tab" role="tabpanel" aria-labelledby="custom-tab">
                @if ($customVarieties->isEmpty())
                    <div class="card">
                        <div class="card-body text-center text-muted py-5">No custom variety data available.</div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
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
                                                <td>{{ $variety->ver_main ?: ($variety->ver_alias ?: '-') }}</td>
                                                <td>{{ $variety->catalogue_no ?? '-' }}</td>
                                                <td>{{ $variety->com_name ?? '-' }}</td>
                                                <td>
                                                    @if (($variety->Sts ?? 'A') === 'A')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ $variety->cr_date ? \Carbon\Carbon::parse($variety->cr_date)->format('d M Y') : '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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