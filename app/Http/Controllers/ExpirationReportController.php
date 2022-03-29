<?php

namespace App\Http\Controllers;

use App\Exports\ExpireFranchiseExport;
use App\Models\Tricycle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Excel;

class ExpirationReportController extends Controller
{

    private $filter;

    public function expirationReport() {

        /* first get all expired franchise */

        if($this->filter === 'expired')
        {
            $data = Tricycle::query()
                ->select(
                    'mtop_applications.mtfrb_case_no',
                    'tricycles.body_number',
                    'tricycles.make_type',
                    'tricycles.engine_motor_no',
                    'tricycles.chassis_no',
                    'tricycles.plate_no',
                    'mtop_applications.validity_date'
                )
                ->join('mtop_applications', 'mtop_applications.id', 'tricycles.mtop_application_id')
                ->join('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
                ->where('mtop_applications.validity_date', '<', date('Y-m-d'))
                ->where('mtop_applications.mtfrb_case_no', '!=', '')
                ->orderBy('validity_date')
                ->orderBy('body_number')
                ->get()
                ->map(function ($item) {
                    $item->status = 'EXPIRED';
                    return $item;
                });
        }
        else
        {
            $startDate = date('Y-m-d');
            $endDate = Carbon::parse()->lastOfYear()->format('Y-m-d');

            $data = Tricycle::query()
                ->select(
                    'mtop_applications.mtfrb_case_no',
                    'tricycles.body_number',
                    'tricycles.make_type',
                    'tricycles.engine_motor_no',
                    'tricycles.chassis_no',
                    'tricycles.plate_no',
                    'mtop_applications.validity_date'
                )
                ->join('mtop_applications', 'mtop_applications.id', 'tricycles.mtop_application_id')
                ->join('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
                ->whereBetween('mtop_applications.validity_date', [$startDate, $endDate])
                ->where('mtop_applications.mtfrb_case_no', '!=', '')
                ->orderBy('validity_date')
                ->orderBy('body_number')
                ->get()
                ->map(function($item){

//                    $expiredDate = Carbon::parse($item->validity_date);
//                    $dateToday = Carbon::now();

//                    $item->status = $dateToday->gt($expiredDate) ? 'EXPIRED' : 'FOR EXPIRATION';

                    $item->status = 'FOR EXPIRATION';

                    return $item;
                });
        }

        return $data;

    }


    public function downloadPDF($filter, $size, $orientation)
    {
        $this->filter = $filter;
        $expired = $this->expirationReport();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.pdf_expired_franchise', compact('expired'))->setPaper($size, $orientation);
        return $pdf->stream();
    }

    public function downloadExcel($filter)
    {
        $this->filter = $filter;
        $expired = $this->expirationReport();
        $export = new ExpireFranchiseExport($expired);


        return Excel::download($export, 'ExpiredFranchiseReport.xls');
    }

}
