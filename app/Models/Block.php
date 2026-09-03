<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Block extends Model
{
    protected $table = 'core_block';
    public    $timestamps = false;

    protected $fillable = [
        'district_id', 'block_name', 'block_code',
        'numeric_code', 'effective_date', 'is_active',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(CoreDistrict::class, 'district_id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'district_id', 'district_id');
    }
}
