<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableCharge extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_type_id',
    ];
}
