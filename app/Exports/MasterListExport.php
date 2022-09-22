<?php

namespace App\Exports;

use App\Models\Driver;
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
    private $tricycles;
    public $sort;
    public $order;

    public function __construct($sort, $order)
    {
        $this->sort = $sort;
        $this->order = $order;
        $this->mtop_applications = new MtopApplication();
        $this->tricycles = new Tricycle();
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {

        $arr = array();


//        $master_list = $this->tricycles->masterList();

        $master_list = Tricycle::leftJoin('taxpayer','taxpayer.id', 'tricycles.operator_id')
            ->leftJoin('barangay', 'barangay.brgy_code', 'taxpayer.brgy_code')
            ->leftJoin('mtop_applications', 'mtop_applications.id', 'tricycles.mtop_application_id')
            ->leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
            ->leftJoin('collne2', 'collne2.or_code', 'colhdr.or_code')
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
                'barangay.brgy_desc as barangay',
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
                'barangay.brgy_desc'
            )
            ->whereNull('colhdr.canc_date')
            ->orderBy($this->sort, $this->order)
            ->get();

        $arr_count = 0;


        foreach($master_list as $data)
        {

            $transact_type = $this->mtop_applications->getApplicationType(explode(',', $data->transact_type));
            $get_charges = DB::table('collne2')->where('or_code', $data->or_code)->select('inc_desc')->get()->implode('inc_desc', '|');
            $driver_details = Driver::where('tricycle_id', $data->id)->select('full_name as driver', 'driver_license_no')->orderBy('created_at', 'DESC')->first();


            array_push($arr,
                [
                    'mtfrb_case_no' => $data->mtfrb_case_no,
                    'transact_date' => $data->transact_date != null ? date('m/d/Y', strtotime($data->transact_date)) : '',
                    'validity_date' => $data->validity_date != null ? date('m/d/Y', strtotime($data->validity_date)) : '',
                    'approve_date' => $data->approve_date != null ? date('m/d/Y', strtotime($data->approve_date)) : '',
                    'payment_date' => $data->payment_date != null ? date('m/d/Y', strtotime($data->payment_date)) : '',
                    'body_number' => $data->body_number,
                    'make_type' => $data->make_type,
                    'engine_motor_no' => $data->engine_motor_no,
                    'chassis_no' => $data->chassis_no,
                    'date_registered' => $data->date_registered != null ? date('m/d/Y', strtotime($data->date_registered)) : '',
                    'plate_no' => $data->plate_no,
                    'full_name' => $data->full_name,
                    'address1' => $data->address1,
                    'mobile' => $data->mobile,
                    'or_no' => $data->or_no,
                    'amount' => $data->amount,
                    'transact_type' => !empty($data->transact_type) ? $transact_type : '',
                    'charges' => $get_charges,
                    'driver' => $driver_details['driver'] ?? '',
                    'driver_license_no' => $driver_details['driver_license_no'] ?? '',
                    'barangay' => $data->barangay,
                ]);
        }


        return view('excel.xls_master_list', ['applications' => $arr]);
    }

    public function columnFormats(): array
    {
        return [
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
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
