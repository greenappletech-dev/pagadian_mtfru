<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banca extends Model
{
    use HasFactory;

    const VALIDATION_RULE = [
        'operator_id' => ['required'],
        'length' => ['required'],
        'width' => ['required'],
        'dept' => ['required'],
        'boat_type_id' => ['required'],
    ];

    public function fetchAllData() {
        return Banca::leftJoin('taxpayer', 'taxpayer.id', 'bancas.operator_id')
            ->leftJoin('boat_types' , 'boat_types.id', 'bancas.boat_type_id')
            ->select('bancas.*' , 'boat_types.name as boat_type','taxpayer.full_name as operator', 'taxpayer.brgy_desc')
            ->get();
    }

    public function fetchDataById($id) {
        return Banca::leftJoin('taxpayer', 'taxpayer.id', 'bancas.operator_id')
            ->leftJoin('barangay', 'barangay.brgy_code' , 'taxpayer.brgy_code')
            ->leftJoin('boat_types' , 'boat_types.id', 'bancas.boat_type_id')
            ->select(
                'bancas.*' ,
                'boat_types.name as boat_type',
                'taxpayer.full_name as operator',
                'taxpayer.address1 as address',
                'barangay.id as barangay_id',
            )
            ->where('bancas.id', $id)
            ->first();
    }

    public function fetchDataByName($string) {
        return Banca::leftJoin('taxpayer', 'taxpayer.id', 'bancas.operator_id')
            ->leftJoin('boat_types' , 'boat_types.id', 'bancas.boat_type_id')
            ->select('bancas.*' , 'boat_types.name as boat_type','taxpayer.full_name as operator')
            ->where('bancas.body_number', $string)
            ->first();
    }

    public function fetchDataByBodyNumber($string) {
        return Banca::leftJoin('taxpayer', 'taxpayer.id', 'bancas.operator_id')
            ->leftJoin('boat_types' , 'boat_types.id', 'bancas.boat_type_id')
            ->leftJoin('barangay', 'barangay.brgy_code' , 'taxpayer.brgy_code')
            ->select(
                'bancas.*' ,
                'boat_types.name as boat_type',
                'barangay.id as barangay_id',
                'barangay.brgy_desc as brgy_desc',
                'barangay.brgy_code as brgy_code',
                'barangay.banca_code',
                'taxpayer.id as taxpayer_id',
                'taxpayer.address1 as address',
                'taxpayer.full_name as operator')
            ->where('bancas.body_number', $string)
            ->get();
    }



}
