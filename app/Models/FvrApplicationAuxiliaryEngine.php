<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FvrApplicationAuxiliaryEngine extends Model
{
    use HasFactory;

    public function fetchDataByForeignId($id) {
        return FvrApplicationAuxiliaryEngine::where('fvr_application_id', $id)->orderBy('id')->get();
    }
}
