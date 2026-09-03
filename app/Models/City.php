<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $table = 'core_city_village';
    public    $timestamps = false;

    protected $fillable = [
        'state_id', 'district_id',
        'division_name', 'city_village_name', 'city_village_code',
        'pincode', 'longitude', 'latitude',
        'is_active', 'effective_date',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(CoreDistrict::class, 'district_id');
    }
}
