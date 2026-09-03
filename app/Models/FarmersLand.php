<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmersLand extends Model
{
    protected $table      = 'farmers_land';
    protected $primaryKey = 'flandid';
    public    $timestamps = false;

    protected $fillable = [
        'fid', 'plot_no', 'khasra_no', 'land_area',
        'latitude', 'longitude',
        'StateId', 'DictrictId', 'TahsilId', 'VillageId',
    ];

    /* ── Relationships ──────────────────────────────────────── */
    public function farmer()
    {
        return $this->belongsTo(Formar::class, 'fid', 'fid');
    }

    public function state()
    {
        return $this->belongsTo(\App\Models\State::class, 'StateId', 'StateId');
    }
}
