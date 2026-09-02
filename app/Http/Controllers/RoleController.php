<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /* ── List + create form ─────────────────────────────────── */
    public function index()
    {
        $roles = Role::latest()->get();
        return view('settings.role', compact('roles'));
    }

    /* ── Store ──────────────────────────────────────────────── */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:roles,name',
            'slug'        => 'nullable|string|max:100|unique:roles,slug',
            'description' => 'nullable|string|max:255',
        ]);

        Role::create([
            'name'        => $request->name,
            'slug'        => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'is_active'   => true,
        ]);

        return redirect()->route('settings.roles.index')
                         ->with('success', 'Role created successfully.');
    }

    /* ── Update (inline modal) ──────────────────────────────── */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:roles,name,' . $role->id,
            'slug'        => 'nullable|string|max:100|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string|max:255',
            'is_active'   => 'boolean',
        ]);

        $role->update([
            'name'        => $request->name,
            'slug'        => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('settings.roles.index')
                         ->with('success', 'Role updated successfully.');
    }

    /* ── Toggle active status ───────────────────────────────── */
    public function toggle(Role $role)
    {
        $role->update(['is_active' => !$role->is_active]);

        return redirect()->route('settings.roles.index')
                         ->with('success', 'Role status updated.');
    }

    /* ── Destroy ────────────────────────────────────────────── */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('settings.roles.index')
                         ->with('success', 'Role deleted.');
    }
}
