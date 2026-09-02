@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 border-bottom pb-2 gap-2">
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">User List</h3>
                <p class="mb-0" style="font-size:13px;color:#71809a">Manage system users and their access</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge rounded-pill px-3 py-2" style="background:#e6f4f3;color:#187b78;font-size:12px;font-weight:600;">
                    <i class="ri-group-line me-1"></i>{{ $totalCount }} Users
                </span>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">
                    <i class="ri-user-add-line me-1"></i>Add User
                </a>
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
                <form method="GET" action="{{ route('settings.users.index') }}">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 ps-0"
                                    placeholder="Search by name or email…"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-filter-line me-1"></i>Filter
                            </button>
                            @if(request('search'))
                                <a href="{{ route('settings.users.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="ri-close-line me-1"></i>Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light" style="font-size:12px;text-transform:uppercase;letter-spacing:.5px">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Verified</th>
                                <th>Joined</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody style="font-size:13px">
                            @forelse($users as $user)
                            <tr>
                                <td class="ps-3 text-muted">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:32px;height:32px;border-radius:50%;background:#e6f4f3;color:#187b78;display:grid;place-items:center;font-size:12px;font-weight:800;flex-shrink:0;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="color:#172b4d">{{ $user->name }}</div>
                                            @if($user->id === auth()->id())
                                                <span style="font-size:10px;color:#187b78;font-weight:600;">You</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Verified</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="ri-group-line" style="font-size:36px;opacity:.2;display:block;margin-bottom:8px"></i>
                                    No users found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center py-2">
                <small class="text-muted">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </small>
                {{ $users->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
