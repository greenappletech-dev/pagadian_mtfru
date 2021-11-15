<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tricycle extends Model
{
    use HasFactory;

    public $timestamps = true;

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

    public function masterList() {
        return Tricycle::leftJoin('taxpayer','taxpayer.id', 'tricycles.operator_id')
        ->leftJoin('mtop_applications', 'mtop_applications.id', 'tricycles.mtop_application_id')
        ->leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
        ->leftJoin('collne2', 'collne2.or_code', 'colhdr.or_code')
        ->orderBy('tricycles.body_number')
        ->select(
            'mtop_applications.mtfrb_case_no',
            'mtop_applications.transact_date',
            'mtop_applications.validity_date',
            'mtop_applications.transact_type',
            'mtop_applications.approve_date',
            DB::raw("(mtop_applications.validity_date + INTERVAL '-2 years') as payment_date"),
            'tricycles.id',
            'tricycles.body_number',
            'tricycles.make_type',
            'tricycles.engine_motor_no',
            'tricycles.chassis_no',
            'tricycles.plate_no',
            'tricycles.created_at as date_registered',
            'taxpayer.full_name',
            'taxpayer.address1',
            'taxpayer.mobile',
            'mtop_applications.approve_date',
            'colhdr.or_number as or_no',
            'colhdr.or_code',
            DB::raw('SUM(collne2.ln_amnt) as amount')
        )
        ->groupBy(
            'mtop_applications.mtfrb_case_no',
            'mtop_applications.transact_date',
            'mtop_applications.validity_date',
            'mtop_applications.transact_type',
            'mtop_applications.approve_date',
            'payment_date',
            'colhdr.trnx_date',
            'tricycles.id',
            'tricycles.body_number',
            'tricycles.make_type',
            'tricycles.engine_motor_no',
            'tricycles.chassis_no',
            'tricycles.plate_no',
            'tricycles.created_at',
            'taxpayer.full_name',
            'taxpayer.address1',
            'taxpayer.mobile',
            'mtop_applications.approve_date',
            'colhdr.or_number',
            'colhdr.or_code',
        )
        ->whereNull('colhdr.canc_date')
        ->get();
    }

    public const VALIDATION_RULE =
    [
        'operator_id' => ['required'],
        'engine_motor_no' => ['unique:tricycles','nullable'],
        'chassis_no' => ['unique:tricycles','nullable'],
        'plate_no' => ['unique:tricycles','nullable'],
        'created_at' => ['nullable'],
    ];

}
