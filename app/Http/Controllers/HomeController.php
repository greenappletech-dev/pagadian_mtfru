<?php

namespace App\Http\Controllers;

use App\Exports\SampleExport;
use \App\Models\FvrApplication;
use App\Models\MtopApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Tricycle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use DateTime;
use Excel;

class HomeController extends Controller
{

    public function index() {
        return view('home');
    }


    /* mtop */

    public function transactioncount($month) {

        $start_date = date('Y-m-01', strtotime($month));
        $end_date = date('Y-m-t', strtotime($month));
        $transaction = array();

        $application = MtopApplication::whereBetween('transact_date', [$start_date, $end_date])
        ->select(
            DB::raw("count(*) as total")
        )
        ->get();


        $payment = MtopApplication::leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
        ->whereBetween('colhdr.trnx_date', [$start_date, $end_date])
        ->select(
            DB::raw("count(*) as total")
        )
        ->get();


        $pending = MtopApplication::whereBetween('transact_date', [$start_date, $end_date])
        ->whereIn('status', [1,2])
        ->select(
            DB::raw("count(*) as total")
        )
        ->get();


        $completed = MtopApplication::whereBetween('approve_date', [$start_date, $end_date])
        ->where('status', 4)
        ->select(
            DB::raw("count(*) as total")
        )
        ->get();


        return response()->json([
            'application' => $application,
            'payment' => $payment,
            'pending' => $pending,
            'completed' => $completed
        ], 200);

    }

    public function monthlyrevenue($year) {

        $from = date($year . '-01-01', strtotime($year));
        $to = date($year . '-12-01', strtotime($year));
        $to = date('Y-m-t',strtotime($to));

        $month_revenue = array();


        $revenue = DB::table('colhdr')->leftJoin('collne2', 'collne2.or_code', 'colhdr.or_code')
        ->whereBetween('trnx_date', [$from, $to])
        ->where('mtop_application_id', '>', 0)
        ->select(
            DB::raw("date_trunc('month', trnx_date) as month"),
            DB::raw("SUM(collne2.ln_amnt) as total")
        )
        ->groupBy('month')
        ->get();


        $start_date = new DateTime(date('Y-m-01', strtotime($from)));
        $end_date = new DateTime(date('Y-m-01', strtotime("+1 month" . $to)));
        $interval = \DateInterval::createFromDateString('1 month');
        $dates = new \DatePeriod($start_date, $interval, $end_date);


        foreach($dates as $date)
        {

            $total = 0;
            $month = $date->format('F');

            foreach($revenue as $key=>$value)
            {
                $revenue_month = date('m',strtotime($value->month));


                if($revenue_month === $date->format('m'))
                {
                    $total = $value->total;
                }

            }

            array_push($month_revenue, [$month, $total]);

        }

        return response()->json([
            'revenue' => $month_revenue,
        ], 200);

    }

    public function dailytransaction($month) {
        $from = date('Y-m-01', strtotime($month));
        $to = date('Y-m-t',strtotime($month));

        $daily = MtopApplication::whereBetween('transact_date', [$from, $to])
        ->select(
            DB::raw("date_trunc('day', transact_date) as day"),
            DB::raw("count(*) as total")
        )
        ->groupBy('day')
        ->get();


        $start_date = new DateTime(date('Y-m-01', strtotime($from)));
        $end_date = new DateTime(date('Y-m-t', strtotime("+1 month" . $to)));
        $interval = \DateInterval::createFromDateString('1 day');
        $dates = new \DatePeriod($start_date, $interval, $end_date);



        $daily_transactions = array();
        foreach($dates as $date)
        {
            $total = 0;
            $month = $date->format('F d');

            foreach($daily as $key=>$value)
            {
                $revenue_month = date('d',strtotime($value->day));


                if($revenue_month === $date->format('d'))
                {
                    $total = $value->total;
                }

            }

            array_push($daily_transactions, [$date->format('d'), $total]);
        }

        return response()->json([
            'daily' => $daily_transactions,
        ], 200);
    }


    /* end */


    public function fvrtransactioncount($month) {

        $start_date = date('Y-m-01', strtotime($month));
        $end_date = date('Y-m-t', strtotime($month));

        $application = FvrApplication::whereBetween('transact_date', [$start_date, $end_date])
        ->select(
            DB::raw("count(*) as total")
        )
        ->get();


        $payment = FvrApplication::whereBetween('or_date', [$start_date, $end_date])
        ->select(
            DB::raw("count(*) as total")
        )
        ->get();


        $pending = FvrApplication::whereBetween('transact_date', [$start_date, $end_date])
        ->whereIn('status', [1])
        ->select(
            DB::raw("count(*) as total")
        )
        ->get();


        $completed = FvrApplication::whereBetween('approve_date', [$start_date, $end_date])
        ->where('status', 3)
        ->select(
            DB::raw("count(*) as total")
        )
        ->get();


        return response()->json([
            'application' => $application,
            'payment' => $payment,
            'pending' => $pending,
            'completed' => $completed
        ], 200);

    }

    public function fvrmonthlyrevenue($year) {

        $from = date($year . '-01-01', strtotime($year));
        $to = date($year . '-12-01', strtotime($year));
        $to = date('Y-m-t',strtotime($to));

        $month_revenue = array();

        $revenue = FvrApplication::leftJoin('fvr_application_charges', 'fvr_application_charges.fvr_application_id','fvr_applications.id')
        ->whereBetween('or_date', [$from, $to])
        ->whereNotNull('or_date')
        ->select(
            DB::raw("date_trunc('month', or_date) as month"),
            DB::raw("SUM(fvr_application_charges.price) as total")
        )
        ->groupBy('month')
        ->get();


        $start_date = new DateTime(date('Y-m-01', strtotime($from)));
        $end_date = new DateTime(date('Y-m-01', strtotime("+1 month" . $to)));
        $interval = \DateInterval::createFromDateString('1 month');
        $dates = new \DatePeriod($start_date, $interval, $end_date);


        foreach($dates as $date)
        {

            $total = 0;
            $month = $date->format('F');

            foreach($revenue as $key=>$value)
            {
                $revenue_month = date('m',strtotime($value->month));


                if($revenue_month === $date->format('m'))
                {
                    $total = $value->total;
                }

            }

            array_push($month_revenue, [$month, $total]);

        }

        return response()->json([
            'revenue' => $month_revenue,
        ], 200);

    }

    public function fvrdailytransaction($month) {
        $from = date('Y-m-01', strtotime($month));
        $to = date('Y-m-t',strtotime($month));

        $daily = FvrApplication::whereBetween('transact_date', [$from, $to])
            ->select(
                DB::raw("date_trunc('day', transact_date) as day"),
                DB::raw("count(*) as total")
            )
            ->groupBy('day')
            ->get();


        $start_date = new DateTime(date('Y-m-01', strtotime($from)));
        $end_date = new DateTime(date('Y-m-t', strtotime("+1 month" . $to)));
        $interval = \DateInterval::createFromDateString('1 day');
        $dates = new \DatePeriod($start_date, $interval, $end_date);



        $daily_transactions = array();
        foreach($dates as $date)
        {
            $total = 0;
            $month = $date->format('F d');

            foreach($daily as $key=>$value)
            {
                $revenue_month = date('d',strtotime($value->day));


                if($revenue_month === $date->format('d'))
                {
                    $total = $value->total;
                }

            }

            array_push($daily_transactions, [$date->format('d'), $total]);
        }

        return response()->json([
            'daily' => $daily_transactions,
        ], 200);
    }

}
