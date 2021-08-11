<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorImage extends Model
{
    use HasFactory;

    public function getDataCount($id) {
        return OperatorImage::where('taxpayer_id', $id)->count();
    }

    public function fetchDataById($id) {
        return OperatorImage::where('taxpayer_id', $id)->orderBy('id', 'DESC')->first();
    }
}
