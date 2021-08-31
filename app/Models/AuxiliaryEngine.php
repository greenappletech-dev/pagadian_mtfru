<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuxiliaryEngine extends Model
{
    use HasFactory;

    public function fetchDataByForeignKey($id) {
        return AuxiliaryEngine::where('banca_id', $id)->get();
    }
}
