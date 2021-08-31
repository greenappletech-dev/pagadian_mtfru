<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatType extends Model
{
    use HasFactory;

    public function fetchData() {
        return BoatType::orderBy('id')->get();
    }

    public function fetchDataById($id) {
        return BoatType::where('id', $id)->first();
    }
}
