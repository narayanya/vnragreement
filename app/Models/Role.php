<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    // Auto-generate slug from name if not provided
    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = \Str::slug($role->name);
            }
        });
    }
}
