<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AliasVariety extends Model
{
    protected $table = 'alias_veriety';

    protected $primaryKey = 'ver_id';

    public $timestamps = false;

    protected $fillable = [
        'catalogue_no',
        'ver_main',
        'ver_alias',
        'com_id',
        'com_name',
        'Sts',
        'cr_by',
        'cr_date',
    ];
}
