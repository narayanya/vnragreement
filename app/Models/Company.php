<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'core_company';

    public $timestamps = false;

    protected $fillable = [
        'company_name',
        'company_code',
        'email',
        'phone',
        'website',
        'remark',
        'status',
        'address',
    ];

    protected $casts = [
        'phone' => 'string',
    ];
}
