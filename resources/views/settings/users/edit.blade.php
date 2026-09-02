@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">

        {{-- Header --}}
        <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-2">
            <a href="{{ route('users.index') }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="ri-arrow-left-line"></i>
            </a>
            <div>
                <h3 class="mb-0 fw-bold" style="color:#172b4d">Edit User</h3>
                <p class="mb-0" style="font-size:13px;color:#71809a">Update name, email or reset password</p>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-1"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li style="font-size:13px">{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- User card --}}
        <div class="card mb-3">
            <div class="card-body d-flex align-items-center gap-3" style="padding:18px 20px">
                <div style="width:48px;height:48px;border-radius:50%;background:#e6f4f3;color:#187b78;display:grid;place-items:center;font-size:18px;font-weight:800;flex-shrink:0;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-bold" style="color:#172b4d;font-size:15px">{{ $user->name }}</div>
                    <div style="font-size:12px;color:#71809a">{{ $user->email }}</div>
                    <div style="font-size:11px;margin-top:3px">
                        @if($user->email_verified_at)
                            <span class="badge bg-success">Verified</span>
                        @else
                            <span class="badge bg-warning">Not Verified</span>
                        @endif
                        <span class="text-muted ms-2">Joined {{ $user->created_at?->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit form --}}
        <div class="card">
            <div class="card-header" style="background:#f6f8fb;border-bottom:1px solid #e7ebf2;padding:12px 20px">
                <span style="font-size:13px;font-weight:700;color:#172b4d">
                    <i class="ri-edit-line me-1"></i>Update Details
                </span>
            </div>
            <div class="card-body" style="padding:24px 20px">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Name --}}
                    <div class="mb-3">
                        <label class="form-label">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}"
                               placeholder="Enter full name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="form-label">
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}"
                               placeholder="Enter email address" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password divider --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <hr style="flex:1;border-color:#e7ebf2">
                        <span style="font-size:11px;color:#71809a;font-weight:600;white-space:nowrap">
                            CHANGE PASSWORD (optional)
                        </span>
                        <hr style="flex:1;border-color:#e7ebf2">
                    </div>

                    {{-- New password --}}
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="newPassword"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Leave blank to keep current password">
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePwd('newPassword', this)" tabindex="-1">
                                <i class="ri-eye-line"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Minimum 8 characters.</small>
                    </div>

                    {{-- Confirm password --}}
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="confirmPassword"
                                   class="form-control"
                                   placeholder="Repeat new password">
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePwd('confirmPassword', this)" tabindex="-1">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>Save Changes
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}"
                              method="POST" class="ms-auto"
                              onsubmit="return confirm('Delete this user permanently?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="ri-delete-bin-line me-1"></i>Delete User
                            </button>
                        </form>
                        @endif
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ri-eye-off-line';
    } else {
        input.type = 'password';
        icon.className = 'ri-eye-line';
    }
}
</script>
@endpush
