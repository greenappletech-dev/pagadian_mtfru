<?php

namespace App\Http\Controllers;

use App\Exports\MasterListExport;
use App\Exports\MTOPReportExport;
use App\Models\Barangay;
use App\Models\MtopApplication;
use App\Models\Tricycle;
use Illuminate\Support\Facades\DB;
use Excel;

class ReportController extends Controller
{

    private $mtop_applications;
    private $barangay;

    public function __construct()
    {
        $this->mtop_applications = new MtopApplication();
        $this->barangay = new Barangay();
    }

    public function master_list() {
        return view('report.master_list');
    }

    public function master_list_getdata() {

        /*
            Create a array
            to iterate body numbers from
            1 to the last latest body number
            based on the system parameters
        */
        $body_numbers = array();
        $applications = array();

        $system_parameters = DB::table('m99')->select('body_number_to')->first();

        $start_number = 1;

        /* get length of the string to count the zeroes will be added at the beginning of the number when it is less that the length of the current setting */
        $get_fix_length = strlen($system_parameters->body_number_to);

        /* incase of number has 0 on the beginning */
        $last_number_as_int = (int)$system_parameters->body_number_to;

        /* loop the number so we can get the iteration per number. so we can get the record one by one and insert it on a array */

        for($i = $start_number; $i <= $last_number_as_int; $i++) {

            /* get the length of the $i so we can put how many zeros is needed to matched the current settings */
            $get_length = strlen($i);
            $zeros = '';

            for($length = $get_length; $length < $get_fix_length; $length++) {
                $zeros = $zeros . '0';
            }

            /* we get the exact the format of the body number that will be matched to the database */
            $body_number = $zeros . $i;

            $mtop_application = Tricycle::leftJoin('mtop_applications', 'mtop_applications.id', 'tricycles.mtop_application_id')
            ->leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
            ->leftJoin('mtop_application_charges', 'mtop_application_charges.mtop_application_id', 'mtop_applications.id')
            ->leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
            ->where('tricycles.body_number', $body_number)
            ->where('tricycles.mtop_application_id', '<>', null)
            ->groupBy('mtop_applications.id', 'taxpayer.id', 'colhdr.or_number', 'mtop_application_charges.mtop_application_id', 'colhdr.id')
            ->select(
                'mtop_applications.*',
                'taxpayer.full_name',
                'taxpayer.address1',
                'taxpayer.mobile',
                'colhdr.or_number as or_no',
                'colhdr.trnx_date',
                DB::raw('SUM(mtop_application_charges.price) as amount')
            )
            ->first();

            /* get transaction type */

            $transaction_type = explode(',', $mtop_application['transact_type']);
            $application_type = $this->mtop_applications->getApplicationType($transaction_type, $body_number, $mtop_application['id']);

            $data = [
                'body_number' => $body_number,
                'mtfrb_case_no' => $mtop_application['mtfrb_case_no'],
                'full_name' => $mtop_application['full_name'],
                'address' => $mtop_application['address1'],
                'mobile' => $mtop_application['mobile'],
                'date_issue_day' => date('d', strtotime($mtop_application['trnx_date'])),
                'date_issue_month_year' => date('F', strtotime($mtop_application['trnx_date'])) . ', ' . date('Y', strtotime($mtop_application['trnx_date'])),
                'date_issue' => date('m-d-Y', strtotime($mtop_application['trnx_date'])),
                'validity_date' => $mtop_application['validity_date'] !== null ? date('m-d-Y', strtotime($mtop_application['validity_date'])) : '',
                'transact_type' => $mtop_application['mtfrb_case_no'] !== null ? $application_type : '',
                'transact_date' => $mtop_application['transact_date'] !== null ? date('m-d-Y', strtotime($mtop_application['transact_date'])) : '',
                'make_type' => $mtop_application['make_type'],
                'engine_motor_no' => $mtop_application['engine_motor_no'],
                'chassis_no' => $mtop_application['chassis_no'],
                'plate_no' => $mtop_application['plate_no'],
                'approve_date' => $mtop_application['approve_date'] !== null ? date('m-d-Y', strtotime($mtop_application['approve_date'])) : '',
                'or_no' => $mtop_application['or_no'],
                'amount' => $mtop_application['amount'],
            ];

            array_push($applications, $data);

        }

        return response()->json(['applications' => $applications]);
    }

    public function master_list_export() {
        return Excel::download(new MasterListExport(), 'master_list.xls');
    }


    /* MTOP REPORTS */

    public function index() {
        return view('report.mtop_report');
    }

    public function getdata() {
        $barangays = $this->barangay->fetchData();
        return response()->json(['barangays' => $barangays]);
    }

    public function generate_report($type, $from, $to, $barangay_id) {
        /* generate old new franchise. group report per body number */

        $report = array();
        $test = array();

        if($type == 1)
        {

            $transact_type = array(['1', 'renewal'], ['2', 'dropping'], ['3','change_unit'], ['4','new']);

            /* we must check each record and check the transaction type */


            foreach($transact_type as $transact)
            {

                $mtop_applications = MtopApplication::leftJoin(
                    'taxpayer',
                    'taxpayer.id',
                    'mtop_applications.taxpayer_id'
                )
                ->leftJoin(
                    'barangay',
                    'barangay.id',
                    'mtop_applications.barangay_id'
                )
                ->leftJoin(
                    'colhdr',
                    'colhdr.mtop_application_id',
                    'mtop_applications.id'
                )
                ->leftJoin(
                    'collne2',
                    'collne2.or_code',
                    'colhdr.or_code'
                )
                ->where(function($query) use ($barangay_id)
                {
                    if($barangay_id !== 'null')
                    {
                        $query->where('mtop_applications.barangay_id', $barangay_id);
                    }
                })
                ->where('status', 4)
                ->where('transact_type', 'LIKE' , '%'. $transact[0] .'%')
                ->whereBetween('transact_date', [$from, $to])
                ->select(
                    'mtop_applications.body_number',
                    'taxpayer.full_name',
                    'taxpayer.address1',
                    'taxpayer.mobile',
                    'colhdr.or_number',
                    'mtop_applications.transact_type'
                )
                ->selectRaw('SUM(collne2.price) as total_amount')
                ->groupBy(
                    'collne2.or_code',
                    'mtop_applications.body_number',
                    'taxpayer.full_name',
                    'taxpayer.address1',
                    'taxpayer.mobile',
                    'colhdr.or_number',
                    'mtop_applications.transact_type'
                )
                ->orderBy('transact_type')
                ->get();


                foreach($mtop_applications as $data)
                {

                    array_push(
                        $report,
                        [
                            $data['body_number'],
                            $data['full_name'],
                            $data['address1'],
                            $data['mobile'],
                            $data['or_number'],
                            $data['total_amount'],
                            $transact[1],
                            $data['transact_type'],
                        ]);

                }
            }

            foreach($report as $key=>$value)
            {

                if($value[6] == 'change_unit')
                {
                    continue;
                }

                foreach($report as $key2=>$value2)
                {
                    if($value2[6] != 'change_unit')
                    {
                        continue;
                    }


                    if($value[0] == $value2[0])
                    {
                        array_push($report[$key2], 'W/ ' . strtoupper($value[6]));
                        $report[$key2][5] = 0.00;
                    }
                }

            }

        }


        if($type == 2)
        {

            $system_settings = DB::table('m99')
                ->select('body_number_from', 'body_number_to')
                ->first();



            /* SELECTING OLD BODY NUMBERS */


            $old = Tricycle::where('body_number', '<' , $system_settings->body_number_from)
                ->where('make_type', '<>', '')
                ->select('make_type', DB::raw('count(*) as total'))
                ->groupBy('make_type')
                ->orderBy('make_type')
                ->get();



            $new = Tricycle::whereBetween('body_number', [$system_settings->body_number_from, $system_settings->body_number_to])
                ->where('make_type', '<>', '')
                ->select('make_type', DB::raw('count(*) as total'))
                ->groupBy('make_type')
                ->orderBy('make_type')
                ->get();



            $make_types = Tricycle::where('body_number', '<>', '')
                ->select('make_type')
                ->groupBy('make_type')
                ->orderBy('make_type','DESC')
                ->get();



            $first_body_number = Tricycle::orderBy('body_number')
                ->where('body_number', '<>', '')
                ->first();



            $old_body_numbers = $first_body_number['body_number'] . '-' . ($system_settings->body_number_from - 1);
            $new_body_numbers = $system_settings->body_number_from . '-' . $system_settings->body_number_to;



            foreach($make_types as $make_type)
            {

                $total_per_type = 0;
                $old_total = 0;
                $new_total = 0;


                foreach($old as $data1)
                {

                    if($data1['make_type'] == $make_type['make_type'])
                    {
                        $total_per_type += $data1['total'];
                        $old_total = $data1['total'];
                        continue;
                    }

                }


                foreach($new as $data2)
                {

                    if($data2['make_type'] == $make_type['make_type'])
                    {
                        $total_per_type += $data2['total'];
                        $new_total = $data2['total'];
                        continue;
                    }

                }


                array_push($report, [$make_type['make_type'], $old_total, $new_total, $total_per_type]);

            }

            array_push($report, [$old_body_numbers, $new_body_numbers]);

        }



        return $report;
    }

    public function export($type, $from, $to, $barangay_id) {
        $generated_report = $this->generate_report($type, $from, $to, $barangay_id);
        $report_title = '';

        if($type == 1) {
            $report_title = 'MTOP_Detailed_report';
        }

        if($type == 2) {
            $report_title = 'Old_New_Franchise';
        }

        return Excel::download(new MTOPReportExport($generated_report, $type, $from, $to), $report_title . time() . '.xlsx');

    }

    public function pdf($type, $from, $to, $barangay_id, $size, $orientation) {

        $generated_report = $this->generate_report($type, $from, $to, $barangay_id);


        $blade = '';



        if($type == 1)
        {

            $blade = 'pdf.pdf_mtop_detail_report';

        }


        if($type == 2)
        {

            $blade = 'pdf.pdf_mtop_old_new_franchise';

        }


        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView($blade, compact('generated_report', 'from', 'to'))->setPaper($size, $orientation);





        return $pdf->stream();
    }


}
