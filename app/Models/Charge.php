<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;

    protected $table = 'otherinc';

//    public const VALIDATION_RULE =
//    [
//        'name' => ['required', 'unique:charges'],
//        'price' => ['required']
//    ];

    public function fetchData() {
        return Charge::where('tricycle','Y')->select('inc_desc as name', 'price', 'id')->orderBy('id')->get();
    }

    public function fetchDataById($id) {
        return Charge::where('id', $id)->where('tricycle','Y')->first();
    }


}
