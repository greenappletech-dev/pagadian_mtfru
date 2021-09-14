<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FvrCharges extends Model
{
    use HasFactory;

    protected $table = 'otherinc';

    public function fetchData() {
        return Charge::where('banca','Y')->select('inc_desc as name', 'price', 'id')->orderBy('id')->get();
    }

    public function fetchDataById($id) {
        return Charge::where('id', $id)->where('banca','Y')->first();
    }
}
