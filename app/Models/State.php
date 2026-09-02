<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $table      = 'state';
    protected $primaryKey = 'StateId';
    public    $timestamps = false;

    protected $fillable = [
        'StateId', 'StateName', 'StateCode',
        'CountryId', 'StateStatus',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'CountryId');
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'StateId');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class, 'StateId');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'StateId');
    }
}
