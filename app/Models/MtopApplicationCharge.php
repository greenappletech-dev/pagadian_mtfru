<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtopApplicationCharge extends Model
{
    use HasFactory;

    public function fetchDataById($id) {
        return MTOPApplicationCharge::leftJoin('otherinc', 'otherinc.id' , 'mtop_application_charges.otherinc_id')
            ->select('mtop_application_charges.*', 'otherinc.inc_desc as name')->where('mtop_application_id', $id)->orderBy('mtop_application_charges.id')->get();
    }

    public function fetchData() {
        return MTOPApplicationCharge::orderBy('mtop_application_id')->get();
    }

    public function fetchDataForReport($barangay_id, $from, $to) {
        return MTOPApplicationCharge::leftJoin('mtop_applications', 'mtop_applications.id', 'mtop_application_charges.mtop_application_id')
            ->leftJoin('barangay', 'barangay.id', 'mtop_applications.barangay_id')
            ->leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
            ->leftJoin('otherinc', 'otherinc.id', 'mtop_application_charges.otherinc_id')
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
            ->select('mtop_applications.*', 'mtop_applications.id as application_id', 'otherinc.inc_desc as charges','mtop_application_charges.*', 'taxpayer.full_name' ,'barangay.brgy_code as brgy_code', 'barangay.brgy_desc as brgy_desc')
            ->orderBy('mtop_applications.status', 'DESC')
            ->get();
    }

}
