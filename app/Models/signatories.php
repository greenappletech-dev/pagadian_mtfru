<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class signatories extends Model
{
    use HasFactory;

    protected $fillable = [
        'ac_officer',
        'ac_verified',
        'ac_noted',
        'ac_approved',
        'cn_recommending',
        'cn_noted',
        'cn_approved',
        'sc_inspected',
        'sc_noted',
        'sc_approved',
        'mo_recommending',
        'mo_approved',
        'bc_recommending',
        'bc_approved',
        'cf_recommending',
        'cf_approved',
        'sf_recommending',
        'sf_verified',
        'sf_approved',
        'pb_certified',
    ];
}
