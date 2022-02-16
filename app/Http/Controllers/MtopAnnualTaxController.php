<?php

namespace App\Http\Controllers;

use App\Models\MtopAnnualTax;
use App\Models\MtopApplication;
use App\Models\Tricycle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MtopAnnualTaxController extends Controller
{

    private $bodyNumber;
    private $dateToday;

    public function __construct()
    {
        $this->dateToday = strtotime(date('Y-m-d'));
    }

    public function index() {
        $annualtax = \DB::table('otherinc')
            ->where('annual_tax', 'Y')
            ->select('id','inc_desc', 'price')
            ->orderBy('id','desc')
            ->get();

        return view('mtfru.mtop_annual_tax', compact('annualtax'));
    }

    public function filter($from, $to) {
        $annualTaxList = MtopAnnualTax::whereBetween('transaction_date', [$from, $to])
            ->leftJoin('taxpayer', 'taxpayer.id', 'mtop_annual_taxes.operator_id')
            ->leftJoin('otherinc', 'otherinc.id', 'mtop_annual_taxes.otherinc_id')
            ->select(
                'mtop_annual_taxes.id',
                'mtop_annual_taxes.transaction_date',
                'mtop_annual_taxes.body_number',
                'taxpayer.full_name',
                'otherinc.inc_desc',
                \DB::raw("CASE mtop_annual_taxes.status WHEN '1' THEN 'PENDING' ELSE 'PAID' END as status"))
            ->get();

        return response()->json(['list' => $annualTaxList], 200);
    }

    public function searchBodyNumber($bodyNumber) {

        $this->bodyNumber = $bodyNumber;

        return $this->isApplicationExpired() ?? $this->returnTricycleInformation();
    }

    private function isApplicationExpired() {
        $getLastestApplication = MtopApplication::where('body_number', $this->bodyNumber)
            ->orderBy('id', 'desc')
            ->first();

        $validityDate = strtotime($getLastestApplication->validity_date);

        if($this->dateToday > $validityDate)
        {
            return response()->json(['err_msg' => 'Franchise Already Expired'], 402);
        }
    }


    private function returnTricycleInformation() {
        $tricycle = Tricycle::where('body_number', $this->bodyNumber)
            ->leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
            ->select('tricycles.id','tricycles.body_number', 'taxpayer.full_name')
            ->first();

        return response()->json(['data' => $tricycle], 200);
    }

    public function save(Request $request) {

        /* save get tricycle information */

        $tricycleInfo = Tricycle::where('tricycles.id', $request->tricycle_id)
            ->leftJoin('taxpayer', 'taxpayer.id','tricycles.operator_id')
            ->select(
                'tricycles.operator_id',
                'tricycles.id',
                'taxpayer.brgy_code',
                'tricycles.body_number',
                'tricycles.make_type',
                'tricycles.engine_motor_no',
                'tricycles.chassis_no',
                'tricycles.plate_no')
            ->first();

        if($tricycleInfo)
        {
            try
            {
                $save = new MtopAnnualTax();
                $save->operator_id = $tricycleInfo->operator_id;
                $save->tricycle_id = $tricycleInfo->id;
                $save->barangay_id = $tricycleInfo->brgy_code;
                $save->otherinc_id = $request->otherinc_id;
                $save->body_number = $tricycleInfo->body_number;
                $save->make_type = $tricycleInfo->make_type;
                $save->engine_motor_no = $tricycleInfo->engine_motor_no;
                $save->chassis_no = $tricycleInfo->chassis_no;
                $save->plate_no = $tricycleInfo->plate_no;
                $save->transaction_date = date('Y-m-d H:i:s');
                $save->status = 1;
                $save->save();
            }
            catch(QueryException $ex)
            {
                return response()->json(['err_msg' => $ex], 422);
            }

            return response()->json(['message' => 'Transaction Succesfully Created!']);
        }

    }

    public function stab($id) {


        $stab_info = MtopAnnualTax::leftJoin('tricycles', 'tricycles.id', 'mtop_annual_taxes.tricycle_id')
            ->leftJoin('taxpayer', 'mtop_annual_taxes.operator_id', 'taxpayer.id')
            ->leftJoin('otherinc', 'otherinc.id', 'mtop_annual_taxes.otherinc_id')
            ->leftJoin('mtop_applications', 'mtop_applications.id', 'tricycles.mtop_application_id')
            ->select('taxpayer.full_name', 'tricycles.body_number','otherinc.inc_desc', 'mtop_applications.validity_date')
            ->where('mtop_annual_taxes.id', $id)
            ->first();



        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.pdf_annual_tax_stab', compact('stab_info'));
        return $pdf->stream();
    }

    public function destroy($id){
        MtopAnnualTax::where('id', $id)->delete();
        return response()->json(['message' => 'Transaction Deleted Successfully!'] , 200);
    }

}
