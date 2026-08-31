<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variety extends Model
{
    protected $table = 'core_variety';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'remark',
        'status',
    ];
}
