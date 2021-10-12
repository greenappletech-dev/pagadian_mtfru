<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FvrApplicationCharge extends Model
{
    use HasFactory;

    public function fetchDataByForeignId($id) {
        return FvrApplicationCharge::leftJoin('otherinc', 'otherinc.id', 'fvr_application_charges.otherinc_id')
            ->select('fvr_application_charges.*', 'otherinc.inc_desc as name')
            ->where('fvr_application_charges.fvr_application_id' , $id)
            ->get();
    }

    public function fetchDataByForeignIdForRenewal($id) {
        return FvrApplicationCharge::leftJoin('otherinc', 'otherinc.id', 'fvr_application_charges.otherinc_id')
            ->select('otherinc.inc_desc as name', 'fvr_application_charges.otherinc_id as id', 'otherinc.price', 'fvr_application_charges.qty', 'fvr_application_charges.tot_amnt')
            ->where('fvr_application_charges.fvr_application_id' , $id)
            ->get()
            ->each(function($item, $index) {
                $item->price = $item->price == 0 ? ($item->tot_amnt / $item->qty) : $item->price;
            });
    }

    public function fetchDataForReport($barangay_id, $from, $to) {
        return FvrApplicationCharge::leftJoin('fvr_applications', 'fvr_applications.id', 'fvr_application_charges.fvr_application_id')
            ->leftJoin('barangay', 'barangay.id', 'fvr_applications.barangay_id')
            ->leftJoin('taxpayer', 'taxpayer.id', 'fvr_applications.taxpayer_id')
            ->leftJoin('otherinc', 'otherinc.id', 'fvr_application_charges.otherinc_id')
            ->where(function($query) use ($barangay_id) {
                if($barangay_id !== 'null') {
                    $query->where('fvr_applications.barangay_id', $barangay_id);
                }
            })
            ->where(function($query) use ($from, $to) {
                if($from !== 'null' && $to !== 'null') {
                    $query->whereBetween('fvr_applications.transact_date', [$from, $to]);
                }
            })
            ->select('fvr_applications.*', 'fvr_applications.id as application_id', 'otherinc.inc_desc as charges','fvr_application_charges.*', 'taxpayer.full_name' ,'barangay.brgy_code as brgy_code', 'barangay.brgy_desc as brgy_desc')
            ->orderBy('fvr_applications.status', 'DESC')
            ->get();
    }

}
