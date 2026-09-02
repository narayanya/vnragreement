<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'employee_id', 'emp_code', 'mobile_number',
        'emp_reporting', 'role_id',
        'status', 'is_external', 'can_download_pdf',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $attributes = [
        'status'           => 1,
        'is_external'      => 0,
        'can_download_pdf' => 0,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'can_download_pdf'  => 'boolean',
            'is_external'       => 'boolean',
        ];
    }

    /* ── Relationships ──────────────────────────────────────── */

    /** Single role (role_id FK) */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * roles() — returns a collection containing the single role
     * so existing @foreach($user->roles) in views doesn't crash.
     */
    public function getRolesAttribute()
    {
        return $this->role ? collect([$this->role]) : collect();
    }

    /** Employee record linked via employee_id */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
