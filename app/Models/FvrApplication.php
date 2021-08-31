<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FvrApplication extends Model
{
    use HasFactory;

    public function fetchFilteredData($from, $to, $barangay_id) {
        return FvrApplication::leftJoin('taxpayer', 'taxpayer.id', 'fvr_applications.taxpayer_id')
            ->leftJoin('barangay', 'barangay.id','fvr_applications.barangay_id')
            ->leftJoin('bancas', 'bancas.id','fvr_applications.banca_id')
            ->where(function($query) use ($barangay_id) {
                if($barangay_id !== 'null') {
                    $query->where('fvr_applications.barangay_id', $barangay_id);
                }
            })
            ->where(function($query) use ($from, $to) {
                if($from !== 'null' && $to !== 'null') {
                    $query->whereBetween('transact_date', [$from, $to]);
                }
            })
            ->orderBy('status')
            ->select(
                'fvr_applications.*',
                'taxpayer.*',
                'barangay.*' ,
                'bancas.*',
                'fvr_applications.body_number as body_number',
                'fvr_applications.id as application_id',
                'fvr_applications.created_at as created_at',
                'fvr_applications.updated_at as updated_at')
            ->orderBy('status', 'DESC')
            ->orderBy('fvr_applications.id', 'DESC')
            ->get();
    }

    public function fetchDataById($id) {
        return FvrApplication::leftJoin('taxpayer', 'taxpayer.id', 'fvr_applications.taxpayer_id')
            ->leftJoin('barangay', 'barangay.id', 'fvr_applications.barangay_id')
            ->select(
                'fvr_applications.*',
                'taxpayer.full_name as operator',
                'fvr_applications.name as boat_name',
                'fvr_applications.color as boat_color',
                'fvr_applications.length as boat_length',
                'barangay.brgy_code',
                'barangay.brgy_desc',
            )
            ->where('fvr_applications.id', $id)
            ->first();
    }

    public function fetchDataForPrinting($id) {
        return FvrApplication::leftJoin('barangay', 'barangay.id','fvr_applications.barangay_id')
            ->leftJoin('bancas', 'bancas.id', 'fvr_applications.banca_id')
            ->leftJoin('boat_types', 'boat_types.id', 'bancas.boat_type_id')
            ->leftJoin('taxpayer', 'taxpayer.id', 'fvr_applications.taxpayer_id')
            ->select(
                'fvr_applications.*',
                'boat_types.name as boat_type',
                'boat_types.with_engine',
                'taxpayer.full_name',
                'taxpayer.first_name',
                'taxpayer.last_name',
                'taxpayer.mid_name',
                'taxpayer.address1 as address',
                'taxpayer.civ_stat',
                'taxpayer.birth_date',
                'barangay.brgy_desc',
            )
            ->where('fvr_applications.id', $id)
            ->first();
    }

    public function fetchFilteredDataRenewal($from, $to, $barangay_id) {
        return Banca::leftJoin('fvr_applications', 'fvr_applications.id', 'bancas.fvr_application_id')
            ->leftJoin('barangay', 'barangay.id', 'fvr_applications.barangay_id')
            ->leftJoin('taxpayer', 'taxpayer.id' , 'fvr_applications.taxpayer_id')
            ->leftJoin('fvr_application_charges' , 'fvr_application_charges.fvr_application_id', 'fvr_applications.id')
            ->groupBy('fvr_applications.id', 'taxpayer.id', 'fvr_application_charges.fvr_application_id')
            ->select(
                'fvr_applications.*',
                'taxpayer.full_name as operator',
                DB::raw("CONCAT(fvr_applications.name,' / ', fvr_applications.color) as boat_desc"),
                DB::raw('SUM(fvr_application_charges.price) as amount'),
            )->where(function($query) use ($barangay_id) {
                if($barangay_id !== 'null') {
                    $query->where('fvr_applications.barangay_id', $barangay_id);
                }
            })
            ->where(function($query) use ($from, $to) {
                if($from !== 'null' && $to !== 'null') {
                    $query->whereBetween(DB::raw('fvr_applications.validity_date'), [$from, $to]);
                }
            })
            ->where('bancas.fvr_application_id' , '<>' , null)
            ->get();
    }

    public function fetchFilteredToExport($from, $to, $barangay_id) {
        return Banca::leftJoin('fvr_applications', 'fvr_applications.id', 'bancas.fvr_application_id')
            ->leftJoin('barangay', 'barangay.id', 'fvr_applications.barangay_id')
            ->leftJoin('taxpayer', 'taxpayer.id' , 'fvr_applications.taxpayer_id')
            ->leftJoin('fvr_application_charges' , 'fvr_application_charges.fvr_application_id', 'fvr_applications.id')
            ->groupBy('fvr_applications.id', 'taxpayer.id', 'barangay.id','fvr_application_charges.fvr_application_id')
            ->select(
                'fvr_applications.transact_date',
                'barangay.brgy_desc',
                'taxpayer.full_name as operator',
                'fvr_applications.body_number',
                DB::raw("CONCAT(fvr_applications.name,' / ', fvr_applications.color) as boat_desc"),
                'fvr_applications.or_date',
                'fvr_applications.validity_date',
                'fvr_applications.approve_date',
                'fvr_applications.or_number',
                DB::raw('SUM(fvr_application_charges.price) as amount'),
            )->where(function($query) use ($barangay_id) {
                if($barangay_id !== 'null') {
                    $query->where('fvr_applications.barangay_id', $barangay_id);
                }
            })
            ->where(function($query) use ($from, $to) {
                if($from !== 'null' && $to !== 'null') {
                    $query->whereBetween(DB::raw('fvr_applications.validity_date'), [$from, $to]);
                }
            })
            ->where('bancas.fvr_application_id' , '<>' , null)
            ->get();
    }
}
