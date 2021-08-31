<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatCaptain extends Model
{
    use HasFactory;

    public function fetchData() {
        return BoatCaptain::leftJoin('bancas', 'bancas.id', 'boat_captains.banca_id')
            ->select('bancas.body_number', 'boat_captains.*')
            ->get();
    }

    public function fetchDataById($id) {
        return BoatCaptain::leftJoin('bancas', 'bancas.id', 'boat_captains.banca_id')
            ->leftJoin('taxpayer', 'taxpayer.id', 'bancas.operator_id')
            ->select('bancas.body_number', 'bancas.name', 'taxpayer.full_name as operator', 'boat_captains.*')
            ->where('boat_captains.id', $id)
            ->first();
    }

    public function fetchDataByBanca($id) {
        return BoatCaptain::where('banca_id', $id)->orderBy('id', 'DESC')->first();
    }

    public function fetchDataToExport() {
        return BoatCaptain::leftJoin('bancas', 'bancas.id', 'boat_captains.banca_id')
            ->leftJoin('taxpayer', 'taxpayer.id', 'bancas.operator_id')
            ->select('bancas.body_number', 'bancas.name', 'taxpayer.full_name as operator', 'boat_captains.full_name')
            ->get();
    }
}
