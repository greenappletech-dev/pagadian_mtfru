<?php

namespace App\Http\Controllers;

use App\Exports\FVRReportExport;
use App\Models\Banca;
use App\Models\Barangay;
use App\Models\FvrApplication;
use App\Models\FvrApplicationCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;

class FvrReportController extends Controller
{

    private $fvr_applications;

    public function __construct()
    {
        $this->fvr_applications = new FvrApplication();
    }

    public function index() {
        return view('report.fvr_report');
    }

    public function getdata() {
        $barangays = Barangay::whereNotNull('banca_code')->get();
        return response()->json(['barangays' => $barangays], 200);
    }

    public function report($from, $to, $barangay_id) {

        /* get boat list */

        $bancas = FvrApplication::leftJoin('taxpayer', 'taxpayer.id', 'fvr_applications.taxpayer_id')
            ->leftJoin('barangay', 'barangay.brgy_desc', 'taxpayer.brgy_desc')
            ->leftJoin('bancas', 'bancas.id', 'fvr_applications.banca_id')
            ->whereBetween('or_date', [$from, $to])
            ->where(function($query) use ($barangay_id) {
                if($barangay_id != 'null') {
                    $query->where('fvr_applications.barangay_id', $barangay_id);
                }
            })
            ->select(
                'fvr_applications.id',
                'taxpayer.full_name',
                'bancas.name as boat_name',
                'taxpayer.address1 as address',
                'bancas.make_type',
                'bancas.horsepower',
                'fvr_applications.body_number',
                'fvr_applications.or_number',
                'fvr_applications.or_number_2',
                'fvr_applications.or_number_3',
                'fvr_applications.or_date',
                'fvr_applications.transact_type',
                'bancas.engine_motor_no',
                'bancas.fishing_gear')
            ->orderBy('barangay.brgy_desc')
            ->get()
            ->each(function($item){
                $collection = FvrApplicationCharge::where('fvr_application_id', $item->id)->select('or_group', DB::raw('SUM(tot_amnt) as totals'))->groupBy('or_group')->get();

                /* or group only have 3 group */
                $orGroupArr = ['A', 'B', 'C'];
                $totalPerTransaction = 0;

                foreach($orGroupArr as $group)
                {
                    $total_or = $collection->where('or_group', $group)->first();
                    $orgroup = $group;

                    if($total_or)
                    {
                        $item->$orgroup = $total_or['totals'] ?? 0;
                        $totalPerTransaction += $total_or['totals'];
                    }
                    else
                    {
                        $item->$orgroup = 0;
                        $totalPerTransaction += 0;
                    }
                }

                $item->total = $totalPerTransaction;
            })
            ->toArray();

        return $bancas;

//        $banca_list = FvrApplication::leftJoin('taxpayer', 'taxpayer.id', 'fvr_applications.taxpayer_id')
//        ->leftJoin('barangay', 'barangay.brgy_desc', 'taxpayer.brgy_desc')
//        ->leftJoin('bancas', 'bancas.id', 'fvr_applications.banca_id')
//        ->leftJoin('fvr_application_charges' , 'fvr_application_charges.fvr_application_id', 'fvr_applications.id')
//        ->whereBetween('or_date', [$from, $to])
//        ->where(
//            function($query) use ($barangay_id) {
//
//                if($barangay_id !== 'null') {
//                    $query->where('barangay.id', $barangay_id);
//                }
//            }
//        )
//        ->select(
//            'taxpayer.full_name',
//            'bancas.name as boat_name',
//            'taxpayer.address1 as address',
//            'bancas.make_type',
//            'bancas.horsepower',
//            'fvr_applications.or_number',
//            'fvr_applications.or_number_2',
//            'fvr_applications.or_date',
//            'fvr_applications.transact_type',
//            DB::raw("SUM(fvr_application_charges.tot_amnt) as collection"),
//            'bancas.engine_motor_no',
//            'bancas.fishing_gear'
//        )
//        ->groupBy(
//            'taxpayer.full_name',
//            'bancas.name',
//            'taxpayer.address1',
//            'bancas.make_type',
//            'bancas.horsepower',
//            'fvr_applications.or_number',
//            'fvr_applications.or_number_2',
//            'fvr_applications.or_date',
//            'fvr_applications.transact_type',
//            'fvr_application_charges.fvr_application_id',
//            'bancas.engine_motor_no',
//            'bancas.fishing_gear'
//        )
//        ->get();

    }

    public function export($from, $to, $barangay_id) {
        $generated_report = $this->report($from, $to, $barangay_id);

        $barangay = null;

        if($barangay_id != 'null') {
            $barangay = Barangay::where('id', $barangay_id)
            ->select('brgy_desc')
            ->first();
        }

        foreach($generated_report as &$report)
        {
            $report['transact_type'] = $this->fvr_applications->transactionType($report['transact_type']);
        }

        return Excel::download(new FVRReportExport($generated_report, $from, $to, $barangay), 'FVR_Amount_Collected_Report_' . time() . '.xlsx');
    }

    public function pdf($from, $to,$barangay_id, $size, $orientation) {

        $generated_report = $this->report($from, $to, $barangay_id);
        $barangay = null;

        if($barangay_id != 'null')
        {
            $barangay = Barangay::where('id', $barangay_id)
                ->select('brgy_desc')
                ->first();
        }

        foreach($generated_report as &$report)
        {
            $report['transact_type'] = $this->fvr_applications->transactionType($report['transact_type']);
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.pdf_fvr_collection_report', compact('generated_report', 'from', 'to', 'barangay'))->setPaper($size, $orientation);
        return $pdf->stream();
    }


}
