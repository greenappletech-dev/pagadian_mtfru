<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Taxpayer extends Model
{
    use HasFactory;

    protected $table = 'taxpayer';

    public function fetchDataPaginated() {
        return Taxpayer::leftJoin('barangay', 'barangay.brgy_code' , 'taxpayer.brgy_code')
            ->select('taxpayer.*', 'barangay.brgy_desc as brgy_desc')
            ->orderBy('full_name')->paginate(10);
    }

    public function fetchSearchedData($string) {
        return Taxpayer::leftJoin('barangay', 'barangay.brgy_code', 'taxpayer.brgy_code')
            ->leftJoin('tricycles' , 'tricycles.operator_id', 'taxpayer.id')
            ->where('tricycles.body_number' , 'like' , '%' . $string . '%')
            ->select(
                'taxpayer.*',
                'barangay.id as barangay_id',
                'barangay.brgy_desc as brgy_desc',
                'taxpayer.id as taxpayer_id',
                'full_name as operator',
                'tricycles.*',
                'tricycles.id as tricycle_id')
            ->orderBy('full_name')
            ->get();
    }

    public function fetchSearchedDataByName($string) {
        return Taxpayer::leftJoin('barangay', 'barangay.brgy_code', 'taxpayer.brgy_code')
            ->whereRaw('CONCAT(taxpayer.last_name,taxpayer.first_name,taxpayer.tax_id) LIKE ?', '%'.$string.'%')
            ->groupBy('barangay.id', 'taxpayer.id')
            ->select(
                'barangay.id as barangay_id',
                'barangay.brgy_desc as brgy_desc',
                'barangay.brgy_code as brgy_code',
                'taxpayer.id as taxpayer_id',
                'taxpayer.address1 as address',
                'taxpayer.full_name as operator',
            )
            ->orderBy('full_name')
            ->get();
    }

    public function getDataByIdTricycle($id) {
        return Taxpayer::where('id', $id)->select('id as taxpayer_id', 'full_name as operator')->first();
    }

    public function getDataById($id) {
        return Taxpayer::where('id' , $id)->first();
    }

    public function fetchSearchedDataPaginated($string) {
        return Taxpayer::leftJoin('barangay', 'barangay.brgy_code', 'taxpayer.brgy_code')
            ->whereRaw('CONCAT(taxpayer.last_name,taxpayer.first_name,taxpayer.tax_id) LIKE ?', '%'.$string.'%')
            ->select(
                'taxpayer.*',
                'barangay.id as barangay_id',
                'barangay.brgy_desc as brgy_desc',
                'taxpayer.id as taxpayer_id',
                'full_name as operator')
            ->orderBy('full_name')
            ->paginate(10);
    }
}
