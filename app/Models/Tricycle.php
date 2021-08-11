<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tricycle extends Model
{
    use HasFactory;

    public function fetchData() {
        return Tricycle::leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
            ->select('tricycles.*', 'taxpayer.full_name as operator')
            ->orderBy('tricycles.body_number','DESC')
            ->get();
    }

    public function fetchDataByBodyNumber($string) {
        return Tricycle::leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
            ->where('tricycles.body_number', $string)
            ->select('tricycles.*', 'taxpayer.id as taxpayer_id', 'taxpayer.full_name as operator', 'taxpayer.address1', 'taxpayer.mobile')
            ->first();
    }

    public function fetchDataById($id) {
        return Tricycle::leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
            ->leftJoin('barangay', 'barangay.brgy_code', 'taxpayer.brgy_code')
            ->where('tricycles.id', $id)
            ->select('tricycles.*', 'taxpayer.id as taxpayer_id', 'taxpayer.full_name as operator', 'taxpayer.address1 as address', 'taxpayer.mobile', 'barangay.id as barangay_id')
            ->first();
    }

    public function fetchDataForRenewal($id) {
        return Tricycle::where('id', $id)->get();
    }

    public function fetchDataByOperator($id) {
        return Tricycle::where('operator_id', $id)->get();
    }

    public const VALIDATION_RULE =
    [
        'operator_id' => ['required'],
        'engine_motor_no' => ['unique:tricycles','nullable'],
        'chassis_no' => ['unique:tricycles','nullable'],
        'plate_no' => ['unique:tricycles','nullable'],
    ];

}
