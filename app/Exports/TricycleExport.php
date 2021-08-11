<?php

namespace App\Exports;

use App\Models\Tricycle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TricycleExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tricycle::leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
            ->select(
                'taxpayer.full_name as operator',
                'tricycles.body_number',
                'tricycles.make_type',
                'tricycles.engine_motor_no',
                'tricycles.chassis_no',
                'tricycles.plate_no'
            )->get();
    }

    public function headings(): array
    {
        return [
            'Operator',
            'Body Number',
            'Make Type',
            'Engine Motor No',
            'Chassis No',
            'Plate No',
        ];
    }
}
