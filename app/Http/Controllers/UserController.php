<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /* ── List + create form ─────────────────────────────────── */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',  'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $totalCount = User::count();
        $users      = $query->latest()->paginate(20)->withQueryString();

        try {
            $departments = Employee::where('emp_status', 'A')
                                   ->whereNotNull('emp_department')
                                   ->distinct()
                                   ->orderBy('emp_department')
                                   ->pluck('emp_department');

            $employees = Employee::where('emp_status', 'A')
                                 ->whereNotNull('emp_name')
                                 ->orderBy('emp_name')
                                 ->get();
        } catch (\Exception $e) {
            $departments = collect();
            $employees   = collect();
        }

        try {
            $roles = \App\Models\Role::orderBy('name')->get();
        } catch (\Exception $e) {
            $roles = collect();
        }

        return view('users.users', compact('users', 'totalCount', 'departments', 'employees', 'roles'));
    }

    /* ── Store ──────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate([
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => ['required', Password::min(8)],
            'employee_id'   => 'nullable|string|max:50',
            'emp_code'      => 'nullable|string|max:30',
            'mobile_number' => 'nullable|string|max:15',
        ]);

        // roles[] is a checkbox array — use first value as role_id
        $roleId = null;
        if (!empty($request->roles) && is_array($request->roles)) {
            $roleId = $request->roles[0];
        }

        // name is filled by JS; fall back to email prefix if blank
        $name = trim($request->input('name', ''));
        if (empty($name)) {
            $name = explode('@', $request->email)[0];
        }

        User::create([
            'name'             => $name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'role_id'          => $roleId,
            'employee_id'      => $request->employee_id,
            'emp_code'         => $request->emp_code,
            'mobile_number'    => $request->mobile_number,
            'emp_reporting'    => $request->emp_reporting,
            'status'           => 1,
            'is_external'      => $request->input('is_external', 0) ? 1 : 0,
            'can_download_pdf' => 0,
        ]);

        return redirect()->route('users.index')
                         ->with('success', 'User created successfully.');
    }

    /* ── Edit form ──────────────────────────────────────────── */
    public function edit(User $user)
    {
        return view('settings.users.edit', compact('user'));
    }

    /* ── Update ─────────────────────────────────────────────── */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'role_id'       => 'nullable|exists:roles,id',
            'emp_code'      => 'nullable|string|max:30',
            'employee_id'   => 'nullable|string|max:50',
            'mobile_number' => 'nullable|string|max:15',
            'emp_reporting' => 'nullable|string|max:50',
            'status'        => 'required|in:0,1',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)],
            ]);
        }

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'role_id'       => $request->role_id ?: null,
            'emp_code'      => $request->emp_code,
            'employee_id'   => $request->employee_id,
            'mobile_number' => $request->mobile_number,
            'emp_reporting' => $request->emp_reporting,
            'status'        => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
                         ->with('success', 'User updated successfully.');
    }

    /* ── Destroy ────────────────────────────────────────────── */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'User deleted.');
    }

    /* ── Assign role ────────────────────────────────────────── */
    public function assignRole(Request $request, User $user)
    {
        $request->validate(['role_id' => 'required|exists:roles,id']);
        $user->update(['role_id' => $request->role_id]);

        return redirect()->route('users.index')
                         ->with('success', 'Role assigned.');
    }

    /* ── Remove role ────────────────────────────────────────── */
    public function removeRole(Request $request, User $user)
    {
        $user->update(['role_id' => null]);

        return redirect()->route('users.index')
                         ->with('success', 'Role removed.');
    }

    /* ── Toggle PDF download ────────────────────────────────── */
    public function togglePdfDownload(User $user)
    {
        $user->update(['can_download_pdf' => !$user->can_download_pdf]);

        return response()->json(['can_download_pdf' => $user->fresh()->can_download_pdf]);
    }

    /* ── Sync (stub) ────────────────────────────────────────── */
    public function sync()
    {
        return redirect()->route('users.index')
                         ->with('success', 'Sync not yet implemented.');
    }
}
