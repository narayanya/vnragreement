<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoreDistrict extends Model
{
    protected $table = 'core_district';
    protected $fillable = [
        'state_id', 'district_name', 'district_code', 'numeric_code', 'effective_date', 'is_active',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class, 'district_id');
    }
}
