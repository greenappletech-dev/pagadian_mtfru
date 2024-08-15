<?php

namespace App\Http\Controllers;

use App\Exports\MasterListExport;
use App\Exports\MTOPReportExport;
use App\Models\Barangay;
use App\Models\Driver;
use App\Models\MtopApplication;
use App\Models\Tricycle;
use Illuminate\Support\Arr;
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
            $driver_details = Driver::where('tricycle_id', $data->id)->select('full_name as driver', 'driver_license_no')->orderBy('created_at', 'DESC')->first();





            array_push($arr,
            [
                'mtfrb_case_no' => $data->mtfrb_case_no,
                'transact_date' => $data->transact_date,
                'validity_date' => $data->validity_date,
                'approve_date' => $data->approve_date,
                'payment_date' => $data->payment_date,
                'body_number' => $data->body_number,
                'make_type' => $data->make_type,
                'date_registered' => $data->date_registered,
                'engine_motor_no' => $data->engine_motor_no,
                'chassis_no' => $data->chassis_no,
                'plate_no' => $data->plate_no,
                'full_name' => $data->full_name,
                'address1' => $data->address1,
                'mobile' => $data->mobile,
                'or_no' => $data->or_no,
                'amount' => $data->amount,
                'transact_type' => !empty($data->transact_type) ? $transact_type : '',
                'driver' => $driver_details['driver'] ?? '',
                'driver_license_no' => $driver_details['driver_license_no'] ?? '',
                'barangay' => $data->barangay,
            ]);

        }


        return response()->json(['master_list' => $arr]);
    }


    public function master_list_export($sort, $order) {
        return Excel::download(new MasterListExport($sort, $order), 'master_list.xls');
    }


    /* MTOP REPORTS */

    public function index() {
        return view('report.mtop_report');
    }

    public function getdata() {
        $barangays = $this->barangay->fetchData();
        return response()->json(['barangays' => $barangays]);
    }

    private function get_status($status): string {
        if($status !== null) {
            $status_array = array('APPLICATION', 'FOR PAYMENT', 'FOR APPROVAL', 'APPROVED');
            return $status_array[$status - 1];
        }

        return false;
    }

    public function get_transaction_type($transact_type) : string {
        $transact_type = explode(',', $transact_type);
        $transact_type_to_string = array();

        foreach($transact_type as $type)
        {

            if($type == 1)
            {
                array_push($transact_type_to_string, 'R');
            }

            if($type == 2)
            {
                array_push($transact_type_to_string, 'T');
            }

            if($type == 3)
            {
                array_push($transact_type_to_string, 'CU');
            }

            if($type == 4)
            {
                array_push($transact_type_to_string, 'N');
            }

        }

        return implode('|', $transact_type_to_string);
    }

    public function mtop_detailed_report($from, $to, $barangay_id)
    {

        $report = array();
        $transact_type = array(['1', 'renewal'], ['2', 'dropping'], ['3','change_unit'], ['4','new']);

        /* we must check each record and check the transaction type */


        foreach($transact_type as $transact)
        {
            $mtop_applications = MtopApplication::leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
                ->leftJoin('barangay', 'barangay.id', 'mtop_applications.barangay_id')
                ->leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
                ->leftJoin('collne2', 'collne2.or_code', 'colhdr.or_code')
                ->where(function($query) use ($barangay_id) {
                    if($barangay_id !== 'null') {
                        $query->where('mtop_applications.barangay_id', $barangay_id);
                    }
                })
                ->where('status', 4)
                ->where('transact_type', 'LIKE' , '%'. $transact[0] .'%')
                ->where('colhdr.cancel', null)
                ->whereBetween('transact_date', [$from, $to])
                ->where(function($query) {
                    $query->where('colhdr.trans_type', 'MTOP')
                        ->orWhere('colhdr.trans_type', null);
                })
                ->select(
                    'mtop_applications.transact_date',
                    'mtop_applications.body_number',
                    'mtop_applications.make_type',
                    'taxpayer.full_name',
                    'taxpayer.address1',
                    'taxpayer.mobile',
                    'colhdr.or_number',
                    'colhdr.trnx_date',
                    'mtop_applications.transact_type',
                    'mtop_applications.transact_type as status',
                    'mtop_applications.id as id',
                )
                ->selectRaw('SUM(collne2.ln_amnt) as total_amount')
                ->groupBy(
                    'mtop_applications.transact_date',
                    'mtop_applications.body_number',
                    'collne2.or_code',
                    'taxpayer.full_name',
                    'taxpayer.address1',
                    'taxpayer.mobile',
                    'colhdr.or_number',
                    'colhdr.trnx_date',
                    'mtop_applications.transact_type',
                    'mtop_applications.id',
                    'barangay.brgy_desc'
                )
                ->orderBy('barangay.brgy_desc')
                ->orderBy('mtop_applications.transact_date')
                ->get()
                ->each(function($item) {
                    $item->status = $this->get_transaction_type($item->status);
                });


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
                        $transact[1], /* still on the foreach getting the value of the transact type */
                        $data['transact_type'],
                        $data['transact_date'],
                        $data['id'],
                        $data['make_type'],
                        $data['status'],
                        $data['trnx_date'],
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


                if($value[0] == $value2[0] && $value[9] == $value2[9])
                {
                    array_push($report[$key2], 'W/ ' . strtoupper($value[6]));
                    $report[$key2][5] = 0.00;
                }
            }

        }

        return $report;
    }

    public function summary_per_make_type_report($from, $to) {

        $report = array();

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


        $make_types = Tricycle::leftjoin('mtop_applications','mtop_applications.tricycle_id','tricycles.id')
            ->where('tricycles.body_number', '<>', '')
            ->whereBetween('mtop_applications.transact_date', [$from, $to])
            ->select('tricycles.make_type')
            ->groupBy('tricycles.make_type')
            ->orderBy('tricycles.make_type','DESC')
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

        return $report;
    }

    public function new_franchise_summary_report($from, $to, $barangay_id) {

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
            ->where('colhdr.cancel', null)
            ->where('mtop_applications.transact_type', 4)
            ->where(function($query) use ($barangay_id) {
                if($barangay_id !== 'null') {
                    $query->where('mtop_applications.barangay_id', $barangay_id);
                }
            })
            ->where(function($query) {
                $query->where('colhdr.trans_type', 'MTOP')
                    ->orWhere('colhdr.trans_type', null);
            })
            ->select(
                DB::raw("date_trunc('month', colhdr.trnx_date) as month"),
                DB::raw("count(*) as total")
            )
            ->groupBy('month')
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

        $count = 0;

        foreach($dates as $date)
        {

            $date_value = $date->format('Y-m-d H:i:s' . '+08');

            $application_value = $application->where('month', $date_value);
            $payment_value = $payment->where('month', $date_value);
            $pending_value = $pending->where('month', $date_value);
            $completed_value = $completed->where('month', $date_value);

            $report[$date->format('F, Y')] = [
                'application' => $application_value->count() === 0 ? 0 : $application_value->first()->total,
                'payment' => $payment_value->count() === 0 ? 0 : $payment_value->first()->total,
                'pending' => $pending_value->count() === 0 ? 0 : $pending_value->first()->total,
                'completed' => $completed_value->count() === 0 ? 0 : $completed_value->first()->total
            ];

            $count++;
//                $application_key = $application->search(function($item, $key) use ($date) {
//                    return date('m-Y', strtotime($item->month)) == $date->format('m-Y');
//                });
//
//
//                $payment_key = $payment->search(function($item, $key) use ($date) {
//                    return date('m-Y', strtotime($item->month)) == $date->format('m-Y');
//                });
//
//                $pending_key = $pending->search(function($item, $key) use ($date) {
//                    return date('m-Y', strtotime($item->month)) == $date->format('m-Y');
//                });
//
//
//                $completed_key = $completed->search(function($item, $key) use ($date) {
//                    return date('m-Y', strtotime($item->month)) == $date->format('m-Y');
//                });
//
//                array_push($report,
//                    [
//                        $date->format('F'),
//                        $application_key === false ? 0 : $application[$application_key]->total,
//                        $payment_key === false ? 0 : $payment[$payment_key]->total,
//                        $pending_key === false ? 0 : $pending[$pending_key]->total,
//                        $completed_key === false ? 0 : $completed[$completed_key]->total,
//                    ]
//                );
        }

        return $report;
    }

    public function new_franchise_report($from, $to, $barangay_id)
    {
        $new_franchise = DB::table('m99')->select('body_number_from', 'body_number_to')->first();
        $get_body_number_length = strlen($new_franchise->body_number_from);
        $report = array();

        $data = MtopApplication::leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
            ->leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
            ->leftJoin('tricycles', 'tricycles.body_number', 'mtop_applications.body_number')
            ->whereDate('mtop_applications.transact_date', '<=' , $to)
            ->whereBetween('mtop_applications.body_number', [$new_franchise->body_number_from, $new_franchise->body_number_to])
            ->where('colhdr.cancel', null)
            ->where(function($query) {
                $query->where('colhdr.trans_type', 'MTOP')
                    ->orWhere('colhdr.trans_type', null);
            })
            ->select(
                'mtop_applications.id',
                DB::raw("mtop_applications.body_number::INTEGER"),
                'taxpayer.full_name',
                'taxpayer.address1 as address',
                'taxpayer.mobile',
                'tricycles.created_at as date_registered',
                'mtop_applications.transact_date',
                DB::raw("(mtop_applications.validity_date + INTERVAL '-2 years') as payment_date"),
                'mtop_applications.approve_date',
                'mtop_applications.make_type',
                'mtop_applications.status',
                'mtop_applications.transact_type',
            )
            ->orderBy('mtop_applications.body_number')
            ->orderBy('mtop_applications.id')
            ->get()
            ->each(function($item, $index) {
                $item->status = $this->get_status($item->status);
                $item->transact_type = $this->get_transaction_type($item->transact_type);
            })
            ->toArray();


        /* create array for the range of the body number from the start to the last */
        $arr2 = range((int)$new_franchise->body_number_from,(int)$new_franchise->body_number_to);

        /* find the missing body number based on the queried data */
        $no_data = array_diff($arr2, array_column($data, 'body_number'));

        /* push all missing body numbers and leave all the details as blank */
        foreach($no_data as $bodyNumberNoApplication) {

            $checkForNewPendingRegistration = Tricycle::where('body_number', $bodyNumberNoApplication)
                ->leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
                ->whereDate('tricycles.created_at', '<=' , $to)
                ->select(
                    'taxpayer.full_name',
                    'taxpayer.address1 as address',
                    'taxpayer.mobile',
                    'tricycles.created_at as date_registered',
                )
                ->first();

            $full_name = '';
            $address = '';
            $mobile = '';
            $date_registered = '';
            $status = '';
            $transact_type = '';

            if($checkForNewPendingRegistration)
            {
                if($checkForNewPendingRegistration->full_name == '' && $checkForNewPendingRegistration->full_name == null)
                {

                }
                else
                {
                    $full_name = $checkForNewPendingRegistration->full_name;
                    $address = $checkForNewPendingRegistration->address;
                    $mobile = $checkForNewPendingRegistration->mobile;
                    $date_registered = $checkForNewPendingRegistration->date_registered;
                    $status = 'REGISTERED';
                    $transact_type = 'N';
                }
            }

            array_push($data, [
                'id' => '',
                'body_number' => $bodyNumberNoApplication,
                'full_name' =>  $full_name,
                'date_registered' => $date_registered,
                'address' => $address,
                'mobile' => $mobile,
                'transact_date' => '',
                'payment_date' => '',
                'approve_date' => '',
                'make_type' => '',
                'status' => $status,
                'transact_type' => $transact_type,
            ]);

        }

        $return_data = collect($data)->sortByDesc('payment_date')->sortBy('body_number')->toArray();
        $dup_body_number = '';
        $dup_key = '';
        $paymentDate = '';

        foreach($return_data as $key => $value)
        {
            if($value['body_number'] == $dup_body_number)
            {
                /* removing duplicate based on the previous id inserted */
                unset($return_data[$dup_key]);
            }

            $dup_key = $key;
            $paymentDate = $value['payment_date'];
            $dup_body_number = $value['body_number'];
        }



        return $return_data;

    }

    public function monthly_accomplishment_report($from, $to) {

        $select_query = array('taxpayer.full_name', 'mtop_applications.body_number', 'taxpayer.address1 as address', 'taxpayer.mobile', 'mtop_applications.make_type', 'mtop_applications.transact_date', DB::raw("(mtop_applications.validity_date + INTERVAL '-2 years') as payment_date"), 'mtop_applications.transact_type');
        $monthly_accomplishment = MtopApplication::leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
            ->select($select_query)
            ->whereBetween('transact_date', [$from, $to])
            ->whereNotNull('validity_date')
            ->orderBy('transact_date')
            ->get()
            ->each(function($item) {
                $item->transact_type = $this->get_transaction_type($item->transact_type);
            })
            ->toArray();

        return collect((object)$monthly_accomplishment)->groupBy('transact_type');

    }

    public function generate_report($type, $from, $to, $barangay_id) {

        if($type == 1)
        {
            return $this->mtop_detailed_report($from, $to, $barangay_id);
        }

        if($type == 2)
        {
            return $this->summary_per_make_type_report($from, $to);
        }

        if($type == 3)
        {
            return $this->new_franchise_summary_report($from, $to, $barangay_id);
        }

        if($type == 4)
        {
            return $this->new_franchise_report($from, $to, $barangay_id);
        }

        if($type == 5)
        {
            return $this->monthly_accomplishment_report($from, $to);
        }

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

        if($type == 5) {
            $report_title = 'Accomplishment_Report';
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

        if($type == 5)
        {
            $blade = 'pdf.pdf_mtop_accomplishment_report';
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView($blade, compact('generated_report', 'from', 'to', 'barangay'))->setPaper($size, $orientation);
        return $pdf->stream();
    }


}
