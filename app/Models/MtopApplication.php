<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MtopApplication extends Model
{
    use HasFactory;

    public const VALIDATION_RULE = [
        'mtfrb_case_no' => ['required', 'regex:/^[A-Z]{3}(-?)\d{4}(-?)\d{4}$/'],
        'make_type' => ['required'],
        'engine_motor_no' => ['required'],
        'chassis_no' => ['required'],
    ];

    public function fetchData() {
        return MtopApplication::leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
                ->leftJoin('barangay', 'barangay.id','mtop_applications.barangay_id')
                ->leftJoin('tricycles', 'tricycles.id','mtop_applications.tricycle_id')
                ->orderBy('mtop_applications.status', 'DESC')
                ->get();
    }

    public function fetchDataById($id) {
        return MtopApplication::leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
            ->leftJoin('barangay', 'barangay.id','mtop_applications.barangay_id')
            ->leftJoin('tricycles', 'tricycles.id','mtop_applications.tricycle_id')
            ->select(
                'mtop_applications.*',
                'taxpayer.full_name',
                'taxpayer.id as taxpayer_id',
                'tricycles.id as tricycle_id',
                'barangay.brgy_desc as brgy_desc',
                'barangay.brgy_code as brgy_code',
                'barangay.id as brgy_id'
            )
            ->where('mtop_applications.id', $id)
            ->first();
    }

    public function fetchDataForPrinting($id) {
        return MtopApplication::leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
            ->leftJoin('barangay', 'barangay.id','mtop_applications.barangay_id')
            ->leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
            ->where('mtop_applications.id', $id)
            ->first();
    }

    public function fetchFilteredData($from, $to, $barangay_id) {
        return MtopApplication::leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
            ->leftJoin('barangay', 'barangay.id','mtop_applications.barangay_id')
            ->leftJoin('tricycles', 'tricycles.id','mtop_applications.tricycle_id')
            ->where(function($query) use ($barangay_id) {
                if($barangay_id !== 'null') {
                    $query->where('mtop_applications.barangay_id', $barangay_id);
                }
            })
            ->where(function($query) use ($from, $to) {
                if($from !== 'null' && $to !== 'null') {
                    $query->whereBetween('transact_date', [$from, $to]);
                }
            })
            ->orderBy('status')
            ->select(
                'mtop_applications.*',
                'taxpayer.*',
                'barangay.*' ,
                'tricycles.*',
                'mtop_applications.id as application_id',
                'mtop_applications.created_at as created_at',
                'mtop_applications.updated_at as updated_at')
            ->orderBy('status', 'DESC')
            ->orderBy('mtop_applications.id', 'DESC')
            ->get();
    }

    public function fetchFilteredDataRenewal($from, $to, $barangay_id) {
        return Tricycle::leftJoin('mtop_applications','mtop_applications.id','tricycles.mtop_application_id')
            ->leftJoin('barangay', 'barangay.id', 'mtop_applications.barangay_id')
            ->leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
            ->leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
            ->leftJoin('mtop_application_charges', 'mtop_application_charges.mtop_application_id', 'mtop_applications.id')
            ->groupBy('mtop_applications.id', 'taxpayer.id', 'barangay.id', 'colhdr.id')
            ->select(
                'mtop_applications.id as application_id',
                'mtop_applications.mtfrb_case_no',
                'mtop_applications.transact_date',
                'taxpayer.full_name',
                'mtop_applications.address',
                DB::raw("CONCAT(barangay.brgy_code , '-' , barangay.brgy_desc) as barangay"),
                'mtop_applications.body_number',
                'mtop_applications.make_type',
                'mtop_applications.engine_motor_no',
                'mtop_applications.chassis_no',
                'mtop_applications.plate_no',
                'mtop_applications.approve_date',
                DB::raw("mtop_applications.validity_date as valid_until"),
                'colhdr.or_number as or_no',
                DB::raw('SUM(mtop_application_charges.price) as amount'))
            ->where(function($query) use ($barangay_id) {
                if($barangay_id !== 'null') {
                    $query->where('mtop_applications.barangay_id', $barangay_id);
                }
            })
            ->where(function($query) use ($from, $to) {
                if($from !== 'null' && $to !== 'null') {
                    $query->whereBetween(DB::raw('mtop_applications.validity_date'), [$from, $to]);
                }
            })
            ->where('tricycles.mtop_application_id', '<>', null)
            ->get();
    }

    public function getApplicationType(array $transaction_type, $body_number, $id): string
    {
        $application_type = '';

        if(count($transaction_type) === 1) {

            $application_type = 'N';

            if((int)$transaction_type[0] === 1) {
                $application_type = 'R';
            }

            if((int)$transaction_type[0] == 2) {
                $application_type = 'T';
            }

            if((int)$transaction_type[0] == 3) {
                $application_type = 'CU';
            }

        } else {

            /* check if the transaction is multiple */

            foreach($transaction_type as $type) {

                if((int)$type == 1) {
                    $application_type = $application_type . 'R | ';
                }

                if((int)$type == 2) {
                    $application_type = $application_type . 'T | ';
                }

                if((int)$type == 3) {
                    $application_type = $application_type . 'CU ';
                }
            }
        }

        return $application_type;
    }

}
