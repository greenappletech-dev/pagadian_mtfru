<?php

namespace App\Http\Controllers;

use App\Exports\ChargesExport;
use Illuminate\Support\Facades\DB;
use Excel;

class MTOPChargeListController extends Controller
{
    public function index() {
        return view('report.mtop_charge_list');
    }

    public function fetchsearcheddata($operator, $year) {

        $data = DB::table('collne2')
        ->leftJoin('colhdr', 'colhdr.or_code', 'collne2.or_code')
        ->leftJoin('otherinc', 'otherinc.inc_code','collne2.inc_code')
        ->leftJoin('mtop_applications', 'mtop_applications.id', 'colhdr.mtop_application_id')
        ->where('otherinc.annual_tax','Y')
        ->where(function($query) use ($operator) {

            if($operator != 'null') {
                $query->whereRaw('CONCAT(colhdr.full_name, colhdr.or_number) LIKE ?', ['%' . strtoupper($operator) . '%']);
            }

        })

        ->where(function($query) use ($year) {

            if($year != 'null') {
                $query->where('collne2.inc_desc', 'LIKE', '%' . $year . '%');
            }

        })

        ->select(
            'colhdr.trnx_date',
            'collne2.inc_desc',
            'colhdr.full_name as operator',
            'colhdr.or_number',
            'mtop_applications.mtfrb_case_no',
            'mtop_applications.validity_date'
        )
        ->orderBy('colhdr.id', 'DESC')
        ->get();

        return $data;
    }

    public function filter($operator, $year) {
        return response()->json(['charges' => $this->fetchsearcheddata($operator, $year)], 200);
    }

    public function pdf($operator, $year, $size, $orientation) {
        $mtop_charges = $this->fetchsearcheddata($operator, $year);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.pdf_mtop_charges', compact('mtop_charges'));
        $pdf->setPaper($size, $orientation);

        return $pdf->stream();
    }

    public function export($operator, $year) {
        return Excel::download(new ChargesExport($this->fetchsearcheddata($operator, $year)), 'operator_annual_tax' . time() . '.xlsx');
    }

}
