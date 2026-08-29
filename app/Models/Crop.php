<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    protected $table = 'core_crop';    
    protected $guarded = [];
    protected $fillable = [
        'crop_name',
        'crop_code',
        'crop_name_elias',
        'crop_code_elias',
        'vertical_id',
        'numeric_code',
        'effective_date',
        'crop_flag',
        'focus_code',
        'scientific_name',
        'common_name',
        'crop_type_id',
        'season_id',
        'description',
        'family_name',
        'genus',
        'species',
        'duration_days',
        'sowing_time',
        'harvest_time',
        'climate_requirement',
        'soil_type_id',
        'isolation_distance',
        'expected_yield',
        'is_active',
        'update_status',
        'season_start_month_id',
        'season_end_month_id'
    ];

    /**
     * Accessor so $crop->name maps to crop_name column.
     */
    public function getNameAttribute(): string
    {
        return $this->crop_name ?? '';
    }



public function cropType()
{
    return $this->belongsTo(CropType::class);
}

public function season()
{
    return $this->belongsTo(Season::class);
}


    public function soilType()
    {
        return $this->belongsTo(SoilType::class);
    }



}
