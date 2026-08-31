@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h3 class="mb-1">Company Master</h3>
            <p class="text-muted mb-0">Synced company data and custom company records.</p>
        </div>
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('master.company.sync') }}">@csrf<button class="btn btn-outline-primary btn-sm">Sync Company Data</button></form>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#companyModal">Add Company</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul class="nav nav-tabs mb-4" id="companyTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="synced-tab" data-bs-toggle="tab" data-bs-target="#syncedCompanies" type="button" role="tab" aria-controls="syncedCompanies" aria-selected="true">Company (Sync Data)</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="custom-tab" data-bs-toggle="tab" data-bs-target="#customCompanies" type="button" role="tab" aria-controls="customCompanies" aria-selected="false">Custom Company</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="syncedCompanies" role="tabpanel" aria-labelledby="synced-tab">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Company Name</th>
                                    <th>Code</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Website</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($syncedCompanies as $company)
                                    <tr>
                                        <td>{{ $company->company_name }}</td>
                                        <td>{{ $company->company_code ?? '-' }}</td>
                                        <td>{{ $company->email ?? '-' }}</td>
                                        <td>{{ $company->phone ?? '-' }}</td>
                                        <td>{{ $company->website ?? '-' }}</td>
                                        <td><span class="badge {{ $company->status == 1 ? 'bg-success' : 'bg-secondary' }}">{{ $company->status == 1 ? 'Active' : 'Inactive' }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No synced company data found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="customCompanies" role="tabpanel" aria-labelledby="custom-tab">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Main Company</th>
                                    <th>Alias Name</th>
                                    <th>Code</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customCompanies as $company)
                                    <tr>
                                        <td>{{ $company->com_main ?? '-' }}</td>
                                        <td>{{ $company->com_alias ?? '-' }}</td>
                                        <td>{{ $company->com_code ?? '-' }}</td>
                                        <td><span class="badge {{ $company->Sts == 'A' ? 'bg-success' : 'bg-secondary' }}">{{ $company->Sts == 'A' ? 'Active' : 'Inactive' }}</span></td>
                                        <td>{{ $company->cr_date ? \Carbon\Carbon::parse($company->cr_date)->format('d-m-Y') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No custom company records found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
<div class="modal fade" id="companyModal" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('master.company.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="companyModalLabel">Create Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company Code</label>
                        <input type="text" name="company_code" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Company</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
