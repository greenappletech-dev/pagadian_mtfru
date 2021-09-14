<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $table = 'barangay';

    public function fetchData() {
        return Barangay::orderBy('id')->get();
    }

    public function fetchDataById($id) {
        return Barangay::where('id', $id)->first();
    }
}
