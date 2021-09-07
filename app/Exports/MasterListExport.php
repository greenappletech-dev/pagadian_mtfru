<?php

namespace App\Exports;

use App\Models\MtopApplication;
use App\Models\MtopApplicationCharge;
use App\Models\Tricycle;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MasterListExport implements FromView, WithStyles,  WithColumnFormatting
{
    private $mtop_applications;

    public function __construct()
    {
        $this->mtop_applications = new MtopApplication();
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {

        $arr = array();

        $master_list = Tricycle::leftJoin('taxpayer','taxpayer.id', 'tricycles.operator_id')
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
                'colhdr.trnx_date',
                'tricycles.body_number',
                'tricycles.make_type',
                'tricycles.engine_motor_no',
                'tricycles.chassis_no',
                'tricycles.plate_no',
                'taxpayer.full_name',
                'taxpayer.address1',
                'taxpayer.mobile',
                'mtop_applications.approve_date',
                'colhdr.or_number as or_no',
                'colhdr.or_code',
                DB::raw('SUM(collne2.ln_amnt) as amount')
            )
            ->groupBy(
                'tricycles.body_number',
                'mtop_applications.mtfrb_case_no',
                'mtop_applications.transact_date',
                'mtop_applications.validity_date',
                'mtop_applications.transact_type',
                'mtop_applications.approve_date',
                'colhdr.trnx_date',
                'tricycles.body_number',
                'tricycles.make_type',
                'tricycles.engine_motor_no',
                'tricycles.chassis_no',
                'tricycles.plate_no',
                'taxpayer.full_name',
                'taxpayer.address1',
                'taxpayer.mobile',
                'mtop_applications.approve_date',
                'colhdr.or_number',
                'colhdr.or_code',
            )
            ->get();



        $arr_count = 0;


        foreach($master_list as $data)
        {

            $transact_type = $this->mtop_applications->getApplicationType(explode(',', $data->transact_type));
            $get_charges = DB::table('collne2')->where('or_code', $data->or_code)->select('inc_desc')->get()->implode('inc_desc', '|');

            array_push($arr,
                [
                    'mtfrb_case_no' => $data->mtfrb_case_no,
                    'transact_date' => $data->transact_date,
                    'validity_date' => $data->validity_date,
                    'approve_date' => $data->approve_date,
                    'trnx_date' => $data->trnx_date,
                    'body_number' => $data->body_number,
                    'make_type' => $data->make_type,
                    'engine_motor_no' => $data->engine_motor_no,
                    'chassis_no' => $data->chassis_no,
                    'plate_no' => $data->plate_no,
                    'full_name' => $data->full_name,
                    'address1' => $data->address1,
                    'mobile' => $data->mobile,
                    'or_no' => $data->or_no,
                    'amount' => $data->amount,
                    'transact_type' => !empty($data->transact_type) ? $transact_type : '',
                    'charges' => $get_charges
                ]);

        }




        return view('excel.xls_master_list', ['applications' => $arr]);
    }

    public function columnFormats(): array
    {
        return [
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }


    public function styles(Worksheet $sheet)
    {
        $cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S'];

        foreach ($cells as $cell) {
            $sheet->getColumnDimension($cell)->setAutoSize('true');
        }

        $sheet->getStyle('A1:S1')->getAlignment()->applyFromArray(array('horizontal' => 'center', 'vertical' => 'center'));
        $sheet->getStyle('A2:S2')->getAlignment()->applyFromArray(array('horizontal' => 'center', 'vertical' => 'center'));

    }
}
