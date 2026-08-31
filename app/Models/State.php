<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $table = 'core_state';

    protected $fillable = [
        'country_id',
        'state_name',
        'state_code',
        'state_type',
        'is_active',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'state_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class, 'state_id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'state_id');
    }
}
