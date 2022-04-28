<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Driver extends Model
{
    use HasFactory;

    public function fetchData() {
        return Driver::leftJoin('tricycles', 'tricycles.id', 'drivers.tricycle_id')
            ->leftJoin('tricycle_associations', 'drivers.tricycle_association_id', 'tricycle_associations.id')
            ->select('drivers.*', 'tricycles.body_number','tricycle_associations.name as association')
            ->orderBy('tricycles.body_number')
            ->get();
    }

    public function fetchDataById($id) {
        return Driver::leftJoin('tricycles', 'tricycles.id', 'drivers.tricycle_id')
            ->leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
            ->select(
                'drivers.id as driver_id',
                'drivers.driver_license_no',
                'drivers.last_name',
                'drivers.first_name',
                'drivers.middle_name',
                'drivers.address',
                'drivers.mobile_number',
                'drivers.gcash',
                'drivers.tricycle_association_id',
                'tricycles.id as tricycle_id',
                'tricycles.body_number',
                'tricycles.make_type',
                'tricycles.engine_motor_no',
                'tricycles.chassis_no',
                'tricycles.plate_no',
                'taxpayer.full_name',
                'drivers.created_at',
                'drivers.updated_at')
            ->where('drivers.id', $id)
            ->orderBy('tricycles.body_number')
            ->first();
    }

    public function fetchDataByIdForChanges($id) {
        return Driver::where('id', $id)->first();
    }

    public function fetchDataForReport() {
        return Driver::leftJoin('tricycles', 'tricycles.id', 'drivers.tricycle_id')
            ->leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
            ->select(
                'tricycles.body_number',
                'drivers.driver_license_no',
                'drivers.full_name as drivers_name',
                'taxpayer.full_name as operator',
                'tricycles.make_type',
                'tricycles.engine_motor_no',
                'tricycles.chassis_no',
                'tricycles.plate_no',
                DB::raw("TO_CHAR(drivers.created_at, 'MM/DD/YYYY') AS date_added"))
            ->orderBy('tricycles.body_number')
            ->orderBy('drivers.created_at', 'DESC')
            ->get();
    }

    public const VALIDATION_RULE = [
        'tricycle_id' => ['required'],
        'driver_license_no' => ['required'],
    ];
}
