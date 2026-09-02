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
            <a href="{{ route('master.company.index', ['tab' => 'synced']) }}"
               class="nav-link {{ $tab === 'synced' ? 'active' : '' }}" role="tab">
                Company (Sync Data)
                @if(method_exists($syncedCompanies,'total'))
                    <span class="badge ms-1 {{ $tab==='synced' ? 'bg-primary' : 'bg-secondary' }}">{{ number_format($syncedCompanies->total()) }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{ route('master.company.index', ['tab' => 'custom']) }}"
               class="nav-link {{ $tab === 'custom' ? 'active' : '' }}" role="tab">
                Custom Company
                @if(method_exists($customCompanies,'total'))
                    <span class="badge ms-1 {{ $tab==='custom' ? 'bg-primary' : 'bg-secondary' }}">{{ number_format($customCompanies->total()) }}</span>
                @endif
            </a>
        </li>
    </ul>

    <div class="tab-content">

        {{-- Synced Companies --}}
        <div class="{{ $tab === 'synced' ? 'd-block' : 'd-none' }}">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width:50px">#</th>
                                    <th>Company Name</th>
                                    <th>Code</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Website</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($syncedCompanies as $company)
                                <tr>
                                    <td class="ps-3 text-muted">
                                        {{ ($syncedCompanies->currentPage()-1)*$syncedCompanies->perPage()+$loop->iteration }}
                                    </td>
                                    <td class="fw-semibold" style="color:#172b4d">{{ $company->company_name ?? '-' }}</td>
                                    <td><span class="badge bg-info">{{ $company->company_code ?? '-' }}</span></td>
                                    <td class="text-muted">
                                        @if($company->email)
                                            <a href="mailto:{{ $company->email }}" class="text-decoration-none">{{ $company->email }}</a>
                                        @else -
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $company->phone ?? '-' }}</td>
                                    <td class="text-muted">
                                        @if($company->website)
                                            <a href="{{ $company->website }}" target="_blank" class="text-decoration-none" rel="noopener">{{ $company->website }}</a>
                                        @else -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $company->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $company->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="ri-building-line" style="font-size:36px;color:#c8d6e5;display:block;margin-bottom:8px"></i>
                                        No synced company data found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($syncedCompanies->hasPages())
                <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">
                        Showing {{ $syncedCompanies->firstItem() }} to {{ $syncedCompanies->lastItem() }}
                        of {{ number_format($syncedCompanies->total()) }} companies
                    </small>
                    {{ $syncedCompanies->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- Custom Companies --}}
        <div class="{{ $tab === 'custom' ? 'd-block' : 'd-none' }}">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width:50px">#</th>
                                    <th>Main Company</th>
                                    <th>Alias Name</th>
                                    <th>Code</th>
                                    <th class="text-center">Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customCompanies as $company)
                                <tr>
                                    <td class="ps-3 text-muted">
                                        {{ ($customCompanies->currentPage()-1)*$customCompanies->perPage()+$loop->iteration }}
                                    </td>
                                    <td class="fw-semibold" style="color:#172b4d">{{ $company->com_main ?? '-' }}</td>
                                    <td class="text-muted">{{ $company->com_alias ?? '-' }}</td>
                                    <td><span class="badge bg-info">{{ $company->com_code ?? '-' }}</span></td>
                                    <td class="text-center">
                                        <span class="badge {{ $company->Sts === 'A' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $company->Sts === 'A' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        {{ $company->cr_date ? \Carbon\Carbon::parse($company->cr_date)->format('d-m-Y') : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="ri-building-4-line" style="font-size:36px;color:#c8d6e5;display:block;margin-bottom:8px"></i>
                                        No custom company records found.
                                        <div class="mt-3">
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#companyModal">
                                                <i class="ri-add-line me-1"></i>Add First Company
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($customCompanies->hasPages())
                <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <small class="text-muted">
                        Showing {{ $customCompanies->firstItem() }} to {{ $customCompanies->lastItem() }}
                        of {{ number_format($customCompanies->total()) }} companies
                    </small>
                    {{ $customCompanies->links() }}
                </div>
                @endif
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
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control">
                    </div>
                    <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3"></textarea>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Company</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
