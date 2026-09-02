<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AliasCompany extends Model
{
    protected $table = 'alias_company';

    protected $primaryKey = 'com_id';

    public $timestamps = false;

    protected $fillable = [
        'com_main',
        'com_alias',
        'com_code',
        'Sts',
        'cr_by',
        'cr_date',
    ];
}
