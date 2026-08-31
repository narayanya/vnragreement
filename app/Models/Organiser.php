<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organiser extends Model
{
    protected $table      = 'organiser';
    protected $primaryKey = 'oid';
    public    $timestamps = false;

    protected $fillable = [
        'tmp_oid', 'oname', 'nature_type_id',
        'mobile_1', 'mobile_2', 'email',
        'dob', 'age', 'fname',
        'address', 'state_id', 'district_id', 'tahsil_id', 'village_id',
        'city', 'pincode', 'aadhar_no', 'pan_no',
        'bank_name', 'account_no', 'branch_name', 'ifsc_code', 'bank_add',
        'authorized_signatory',
        'doc_aadhar', 'doc_pan', 'doc_passbook',
        'cr_by', 'cr_date',
    ];
}
