<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Block extends Model
{
    protected $table = 'core_block';

    protected $fillable = [
        'state_id',
        'district_id',
        'block_name',
        'block_code',
        'is_active',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'block_id');
    }
}
