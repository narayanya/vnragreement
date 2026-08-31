<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formar extends Model
{
    protected $table      = 'farmers';
    protected $primaryKey = 'fid';
    public    $timestamps = false;

    protected $fillable = [
        'tem_fid', 'fname', 'contact_1', 'contact_2', 'email',
        'dob', 'age', 'father_name', 'father_contact', 'oid',
        'address', 'state_id', 'distric_id', 'tahsil_id', 'village_id',
        'pincode', 'aadhar_no', 'pan_no',
        'idproof_name', 'idproof_no', 'addproof_name', 'addproof_no',
        'bank_name', 'account_no', 'branch_name', 'ifsc_code', 'bank_add',
        'doc_photo', 'doc_aadhar', 'doc_aadharback', 'doc_pan',
        'doc_passbook', 'doc_passback', 'doc_idproof', 'doc_addproof',
        'total_land', 'cr_by', 'cr_date',
    ];
}
