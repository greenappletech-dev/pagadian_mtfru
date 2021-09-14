<?php

namespace App\Http\Controllers;

use App\Exports\MasterListExport;
use App\Exports\MTOPReportExport;
use App\Models\Barangay;
use App\Models\MtopApplication;
use App\Models\Tricycle;
use Illuminate\Support\Facades\DB;
use DateTime;
use Excel;

class ReportController extends Controller
{

    private $mtop_applications;
    private $barangay;
    private $tricycles;

    public function __construct()
    {
        $this->mtop_applications = new MtopApplication();
        $this->barangay = new Barangay();
        $this->tricycles = new Tricycle();
    }

    public function master_list() {
        return view('report.master_list');
    }


    public function master_list_getdata() {

        $arr = array();

        $master_list = $this->tricycles->masterList();

        foreach($master_list as $data) {

            $transact_type = $this->mtop_applications->getApplicationType(explode(',', $data->transact_type));

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
            ]);

        }


        return response()->json(['master_list' => $arr]);
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


        /* MTOP DETAILED REPORT */


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
                    'mtop_applications.transact_date',
                    'mtop_applications.body_number',
                    'taxpayer.full_name',
                    'taxpayer.address1',
                    'taxpayer.mobile',
                    'colhdr.or_number',
                    'mtop_applications.transact_type'
                )
                ->selectRaw('SUM(collne2.price) as total_amount')
                ->groupBy(
                    'mtop_applications.transact_date',
                    'mtop_applications.body_number',
                    'collne2.or_code',
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
                            $data['transact_date']
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


        /* END */


        /* SUMMARY PER MAKE/TYPE REPORT */


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


        /* END */


        /* NEW FRANCHISE SUMMARY REPORT */


        if($type == 3)
        {

            $report = array();


            $new_franchise = DB::table('m99')->select('body_number_from', 'body_number_to')->first();


            $application = MtopApplication::whereBetween('body_number', [$new_franchise->body_number_from, $new_franchise->body_number_to])
                ->whereBetween('transact_date', [$from, $to])
                ->where('mtop_applications.transact_type', 4)
                ->where(function($query) use ($barangay_id)
                {
                    if($barangay_id !== 'null')
                    {
                        $query->where('mtop_applications.barangay_id', $barangay_id);
                    }
                })
                ->select(
                    DB::raw("date_trunc('month', transact_date) as month"),
                    DB::raw("count(*) as total")
                )
                ->groupBy('month')
                ->get();




            $payment = MtopApplication::leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
                ->whereBetween('colhdr.trnx_date', [$from, $to])
                ->whereBetween('mtop_applications.body_number', [$new_franchise->body_number_from, $new_franchise->body_number_to])
                ->where('mtop_applications.transact_type', 4)
                ->where(function($query) use ($barangay_id)
                {
                    if($barangay_id !== 'null')
                    {
                        $query->where('mtop_applications.barangay_id', $barangay_id);
                    }
                })
                ->select(
                    DB::raw("date_trunc('month', colhdr.trnx_date) as month"),
                    DB::raw("count(*) as total")
                )
                ->groupBy('month', 'body_number')
                ->get();


            $pending = MtopApplication::whereBetween('transact_date', [$from, $to])
                ->whereBetween('body_number', [$new_franchise->body_number_from, $new_franchise->body_number_to])
                ->whereIn('status', [1,2])
                ->where('transact_type', 4)
                ->where(function($query) use ($barangay_id)
                {
                    if($barangay_id !== 'null')
                    {
                        $query->where('mtop_applications.barangay_id', $barangay_id);
                    }
                })
                ->select(
                    DB::raw("date_trunc('month', transact_date) as month"),
                    DB::raw("count(*) as total")
                )
                ->groupBy('month')
                ->get();


            $completed = MtopApplication::whereBetween('approve_date', [$from, $to])
                ->where('status', 4)
                ->whereBetween('body_number', [$new_franchise->body_number_from, $new_franchise->body_number_to])
                ->where('transact_type', 4)
                ->where(function($query) use ($barangay_id)
                {
                    if($barangay_id !== 'null')
                    {
                        $query->where('mtop_applications.barangay_id', $barangay_id);
                    }
                })
                ->select(
                    DB::raw("date_trunc('month', approve_date) as month"),
                    DB::raw("count(*) as total")
                )
                ->groupBy('month')
                ->get();

            /* change both filter dates to the beginning day of the month */



            $start_date = new DateTime(date('Y-m-01', strtotime($from)));
            $end_date = new DateTime(date('Y-m-01', strtotime("+1 month" . $to)));
            $interval = \DateInterval::createFromDateString('1 month');
            $dates = new \DatePeriod($start_date, $interval, $end_date);


            $total = 0;



            foreach($dates as $date)
            {


                $application_key = $application->search(function($item, $key) use ($date) {
                    if(date('m', strtotime($item->month)) == $date->format('m')) {
                        return;
                    }
                });


                $payment_key = $payment->search(function($item, $key) use ($date) {
                    if(date('m', strtotime($item->month)) == $date->format('m')) {
                        return;
                    }
                });

                $pending_key = $pending->search(function($item, $key) use ($date) {
                    if(date('m', strtotime($item->month)) == $date->format('m')) {
                        return;
                    }
                });


                $completed_key = $completed->search(function($item, $key) use ($date) {
                    return date('m-Y', strtotime($item->month)) == $date->format('m-Y');
                });

                array_push($report,
                    [
                        $date->format('F'),
                        $application_key === false ? 0 : $application[$application_key]->total,
                        $payment_key === false ? 0 : $payment[$payment_key]->total,
                        $pending_key === false ? 0 : $pending[$pending_key]->total,
                        $completed_key === false ? 0 : $completed[$completed_key]->total,
                    ]
                );
            }

            return $report;
        }

        /* END */



        /* NEW FRANCHISE REPORT */


        if($type == 4)
        {
            $new_franchise = DB::table('m99')->select('body_number_from', 'body_number_to')->first();
            $get_body_number_length = strlen($new_franchise->body_number_from);
            $report = array();


            for($i = (int)$new_franchise->body_number_from; $i <= (int)$new_franchise->body_number_to; $i++)
            {

                /* adding zeros in the front of the number */
                $body_number = str_pad($i, $get_body_number_length, '0', STR_PAD_LEFT);

                $data = Tricycle::leftJoin('mtop_applications', 'mtop_applications.body_number', 'tricycles.body_number')
                    ->leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
                    ->leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
                    ->where('tricycles.body_number', $body_number)
                    ->select(
                        'tricycles.*',
                        'mtop_applications.transact_date',
                        'mtop_applications.approve_date',
                        'mtop_applications.status',
                        'colhdr.trnx_date',
                        'taxpayer.full_name',
                        'taxpayer.address1 as address',
                        'taxpayer.mobile'
                    )
                    ->first();

                array_push($report, [$body_number,$data]);
            }

        }

        /* END */

        return $report;
    }

    public function export($type, $from, $to, $barangay_id) {

        $generated_report = $this->generate_report($type, $from, $to, $barangay_id);

        $barangay = null;

        if($barangay_id != 'null') {
            $barangay = Barangay::where('id', $barangay_id)
            ->select('brgy_desc')
            ->first();
        }

        $report_title = '';

        if($type == 1) {
            $report_title = 'MTOP_Detailed_Report';
        }

        if($type == 2) {
            $report_title = 'Summary_Per_MakeType_Report';
        }

        if($type == 3) {
            $report_title = 'New_Franchise_Summary_Per_Month';
        }

        if($type == 4) {
            $report_title = 'New_Franchise_Report';
        }


        return Excel::download(new MTOPReportExport($generated_report, $type, $from, $to, $barangay), $report_title . time() . '.xlsx');

    }

    public function pdf($type, $from, $to, $barangay_id, $size, $orientation) {

        $generated_report = $this->generate_report($type, $from, $to, $barangay_id);

        $barangay = null;

        if($barangay_id != 'null') {
            $barangay = Barangay::where('id', $barangay_id)
                ->select('brgy_desc')
                ->first();
        }


        $blade = '';


        if($type == 1)
        {
            $blade = 'pdf.pdf_mtop_detail_report';
        }


        if($type == 2)
        {
            $blade = 'pdf.pdf_mtop_old_new_franchise';
        }


        if($type == 3)
        {
            $blade = 'pdf.pdf_mtop_summary_new_franchise';
        }

        if($type == 4)
        {
            $blade = 'pdf.pdf_mtop_new_franchise';
        }


        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView($blade, compact('generated_report', 'from', 'to', 'barangay'))->setPaper($size, $orientation);
        return $pdf->stream();
    }


}
