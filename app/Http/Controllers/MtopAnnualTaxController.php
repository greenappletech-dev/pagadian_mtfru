<?php

namespace App\Http\Controllers;

use App\Models\MtopAnnualTax;
use App\Models\MtopAnnualTaxCharge;
use App\Models\MtopAnnualTaxItem;
use App\Models\MtopApplication;
use App\Models\Taxpayer;
use App\Models\Tricycle;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MtopAnnualTaxController extends Controller
{

//    private $bodyNumber;
//    private $dateToday;
//
//    public function __construct()
//    {
//        $this->dateToday = strtotime(date('Y-m-d'));
//    }
//
//    public function index() {
//        $annualtax = \DB::table('otherinc')
//            ->where('annual_tax', 'Y')
//            ->select('id','inc_desc', 'price')
//            ->orderBy('id','desc')
//            ->get();
//
//        return view('mtfru.mtop_annual_tax', compact('annualtax'));
//    }
//
//    public function filter($from, $to) {
//        $annualTaxList = MtopAnnualTax::whereBetween('transaction_date', [$from, $to])
//            ->leftJoin('taxpayer', 'taxpayer.id', 'mtop_annual_taxes.operator_id')
//            ->leftJoin('otherinc', 'otherinc.id', 'mtop_annual_taxes.otherinc_id')
//            ->select(
//                'mtop_annual_taxes.id',
//                'mtop_annual_taxes.transaction_date',
//                'mtop_annual_taxes.body_number',
//                'taxpayer.full_name',
//                'otherinc.inc_desc',
//                \DB::raw("CASE mtop_annual_taxes.status WHEN '1' THEN 'PENDING' ELSE 'PAID' END as status"))
//            ->get();
//
//        return response()->json(['list' => $annualTaxList], 200);
//    }
//
//    public function searchBodyNumber($bodyNumber) {
//
//        $this->bodyNumber = $bodyNumber;
//
//        return $this->isApplicationExpired() ?? $this->returnTricycleInformation();
//    }
//
//    private function isApplicationExpired() {
//        $getLastestApplication = MtopApplication::where('body_number', $this->bodyNumber)
//            ->orderBy('id', 'desc')
//            ->first();
//
//        $validityDate = strtotime($getLastestApplication->validity_date);
//
//        if($this->dateToday > $validityDate)
//        {
//            return response()->json(['err_msg' => 'Franchise Already Expired'], 402);
//        }
//    }
//
//
//    private function returnTricycleInformation() {
//        $tricycle = Tricycle::where('body_number', $this->bodyNumber)
//            ->leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
//            ->select('tricycles.id','tricycles.body_number', 'taxpayer.full_name')
//            ->first();
//
//        return response()->json(['data' => $tricycle], 200);
//    }
//
//    public function save(Request $request) {
//
//        /* save get tricycle information */
//
//        $tricycleInfo = Tricycle::where('tricycles.id', $request->tricycle_id)
//            ->leftJoin('taxpayer', 'taxpayer.id','tricycles.operator_id')
//            ->select(
//                'tricycles.operator_id',
//                'tricycles.id',
//                'taxpayer.brgy_code',
//                'tricycles.body_number',
//                'tricycles.make_type',
//                'tricycles.engine_motor_no',
//                'tricycles.chassis_no',
//                'tricycles.plate_no')
//            ->first();
//
//        if($tricycleInfo)
//        {
//            try
//            {
//                $save = new MtopAnnualTax();
//                $save->operator_id = $tricycleInfo->operator_id;
//                $save->tricycle_id = $tricycleInfo->id;
//                $save->barangay_id = $tricycleInfo->brgy_code;
//                $save->otherinc_id = $request->otherinc_id;
//                $save->body_number = $tricycleInfo->body_number;
//                $save->make_type = $tricycleInfo->make_type;
//                $save->engine_motor_no = $tricycleInfo->engine_motor_no;
//                $save->chassis_no = $tricycleInfo->chassis_no;
//                $save->plate_no = $tricycleInfo->plate_no;
//                $save->transaction_date = date('Y-m-d H:i:s');
//                $save->status = 1;
//                $save->save();
//            }
//            catch(QueryException $ex)
//            {
//                return response()->json(['err_msg' => $ex], 422);
//            }
//
//            return response()->json(['message' => 'Transaction Succesfully Created!']);
//        }
//
//    }
//
//    public function stab($id) {
//
//
//        $stab_info = MtopAnnualTax::leftJoin('tricycles', 'tricycles.id', 'mtop_annual_taxes.tricycle_id')
//            ->leftJoin('taxpayer', 'mtop_annual_taxes.operator_id', 'taxpayer.id')
//            ->leftJoin('otherinc', 'otherinc.id', 'mtop_annual_taxes.otherinc_id')
//            ->leftJoin('mtop_applications', 'mtop_applications.id', 'tricycles.mtop_application_id')
//            ->select('taxpayer.full_name', 'tricycles.body_number','otherinc.inc_desc', 'mtop_applications.validity_date')
//            ->where('mtop_annual_taxes.id', $id)
//            ->first();
//
//
//
//        $pdf = \App::make('dompdf.wrapper');
//        $pdf->getDomPDF()->set_option("enable_php", true);
//        $pdf->loadView('pdf.pdf_annual_tax_stab', compact('stab_info'));
//        return $pdf->stream();
//    }
//
//    public function destroy($id){
//        MtopAnnualTax::where('id', $id)->delete();
//        return response()->json(['message' => 'Transaction Deleted Successfully!'] , 200);
//    }


    public function index() {
        $otherinc = DB::table('otherinc')->where('tricycle', 'Y')->get();
        return view('mtfru.mtop_annual_tax', compact('otherinc'));
    }

    public function filterDate($from, $to) {
        return MtopAnnualTax::leftJoin('taxpayer', 'taxpayer.id', 'mtop_annual_taxes.operator_id')
            ->select('mtop_annual_taxes.id',
                'mtop_annual_taxes.transaction_date',
                'mtop_annual_taxes.name as operator',
                'mtop_annual_taxes.status',
                'mtop_annual_taxes.body_number',
                'mtop_annual_taxes.make_type',
                'mtop_annual_taxes.engine_motor_no',
                'mtop_annual_taxes.chassis_no',
                'mtop_annual_taxes.plate_no')
            ->whereBetween('transaction_date', [$from, $to])
            ->orderBy('mtop_annual_taxes.created_at')
            ->get();
    }

    public function initialData($from, $to) {
        return response()->json(['data' => $this->filterDate($from, $to)]);
    }

    public function getLastPaidAnnualTax($tricycle_id) {

        return DB::table('colhdr')
            ->select(
                'colhdr.trnx_date',
                'otherinc.inc_desc',
                'otherinc.id as otherinc_id',
            )
            ->leftJoin('collne2', 'collne2.or_code', 'colhdr.or_code')
            ->leftJoin('otherinc', 'otherinc.inc_code', 'collne2.inc_code')
            ->leftJoin('mtop_applications', 'mtop_applications.id', 'colhdr.mtop_application_id')
            ->leftJoin('tricycles', 'tricycles.id', 'mtop_applications.tricycle_id')
            ->where('tricycles.id', $tricycle_id)
            ->where('annual_tax_year', '!=', null)
            ->where('otherinc.annual_tax', 'Y')
            ->orderBy('trnx_date', 'desc')
            ->first();

    }

    public function searchBodyNumber($body_number) {

        $data = Tricycle::leftJoin('taxpayer','taxpayer.id', 'tricycles.operator_id')
            ->select(
                'tricycles.id as tricycle_id',
                'tricycles.body_number',
                'tricycles.make_type',
                'tricycles.engine_motor_no',
                'tricycles.chassis_no',
                'tricycles.plate_no',
                'taxpayer.id as operator_id',
                'taxpayer.full_name',
                'taxpayer.address1 as address',
                'taxpayer.mobile')
            ->where('tricycles.body_number', $body_number)
            ->first();

        if(!$data)
        {
            return response()->json(['err_msg' => 'Not Result'], 400);
        }

        $previousAnnualTax = $this->getLastPaidAnnualTax($data->tricycle_id);

        if($previousAnnualTax)
        {
            $data->annual_tax = $previousAnnualTax->inc_desc;
        }
        else
        {
            $data->annual_tax = '';
        }



        $defaultCharges = collect([]);

        $charges = DB::table('otherinc')->where('annual_tax', 'Y')
            ->get();

        foreach($charges as $charge)
        {

            if((int)$charge->annual_tax_year == date('Y') || $charge->annual_tax_year == null)
            {
                $defaultCharges->push([
                    'id' => $charge->id,
                    'inc_desc' => $charge->inc_desc,
                    'price' => $charge->price
                ]);
            }


        }

        return response()->json(['data' => $data, 'default_charges' => $defaultCharges], 200);

    }

    public function validateData(Request $request)
    {
        $request->validate([
            'operator_id' => 'required',
            'transaction_date' => 'required',
        ],[
            'operator_id.required' => 'You must Select Operator First',
            'transaction_date' => 'Transaction Date is Required'
        ]);
    }

    public function store(Request $request) {

        $this->validateData($request);

        \DB::beginTransaction();

        try
        {

            if(isset($request->id))
            {
                $data = MtopAnnualTax::where('id', $request->id)->first();
            }
            else
            {
                $data = new MtopAnnualTax();
            }

            $this->storeData($request, $data);

        }
        catch(QueryException $ex)
        {
            DB::rollBack();
            return response()->json(['err_msg' => $ex], 400);
        }

        DB::commit();

        return response()->json(['data' => 'success'], 200);

    }

    public function storeData(Request $request, MtopAnnualTax $query) {

        $operator_info = Taxpayer::where('id', $request->operator_id)->first();
        $query->operator_id = $request->operator_id;
        $query->name = $operator_info->full_name;
        $query->address = $operator_info->address1;
        $query->mobile_number = $operator_info->mobile_number;
        $query->transaction_date = $request->transaction_date;
        $query->tricycle_id = $request->tricycle_id;
        $query->body_number = $request->body_number;
        $query->make_type = $request->make_type;
        $query->engine_motor_no = $request->engine_motor_no;
        $query->chassis_no = $request->chassis_no;
        $query->plate_no = $request->plate_no;
        $query->status = 1;
        $query->user_id = auth()->user()->id;
        $query->save();


        /* store charges */

        $charges = $request->charges;
        $changesCurrentIdValues = [];

        foreach($charges as $charge)
        {

            if(isset($charge['mtop_annual_tax_charge_id']))
            {
                $data = MtopAnnualTaxCharge::where('id', $charge['mtop_annual_tax_charge_id'])->first();
            }
            else
            {
                $data = new MtopAnnualTaxCharge();
                $data->mtop_annual_tax_id = $query->id;
            }

            $data->otherinc_id = $charge['id'];
            $data->amount = $charge['price'];
            $data->save();

            $changesCurrentIdValues[] = $data->id;

        }

        MtopAnnualTaxCharge::whereNotIn('id', $changesCurrentIdValues)->where('mtop_annual_tax_id',$query->id)->delete();

    }

    public function edit($id)
    {

        $data = MtopAnnualTax::where('mtop_annual_taxes.id', $id)
            ->leftJoin('taxpayer','taxpayer.id','mtop_annual_taxes.operator_id')
            ->select(
                'mtop_annual_taxes.id',
                'mtop_annual_taxes.operator_id',
                'mtop_annual_taxes.tricycle_id',
                'mtop_annual_taxes.address',
                'mtop_annual_taxes.body_number',
                'mtop_annual_taxes.make_type',
                'mtop_annual_taxes.engine_motor_no',
                'mtop_annual_taxes.chassis_no',
                'mtop_annual_taxes.plate_no',
                'mtop_annual_taxes.transaction_date',
                'taxpayer.full_name',
                'taxpayer.address1')
            ->first();

        $charges = collect([]);

        if($data)
        {
            $previousAnnualTax = $this->getLastPaidAnnualTax($data->tricycle_id);

            if($previousAnnualTax)
            {
                $data->annual_tax = $previousAnnualTax->inc_desc;
            }
            else
            {
                $data->annual_tax = '';
            }

            $charges = MtopAnnualTaxCharge::where('mtop_annual_tax_id', $data->id)
                ->leftJoin('otherinc', 'otherinc.id', 'mtop_annual_tax_charges.otherinc_id')
                ->select(
                    'otherinc.inc_desc',
                    'mtop_annual_tax_charges.id as mtop_annual_tax_charge_id',
                    'mtop_annual_tax_charges.otherinc_id as id',
                    'mtop_annual_tax_charges.amount as price')
                ->get();
        }



        return response()->json(['data' => $data, 'charges' => $charges], 200);

    }

    public function destroy($id) {

        try
        {
            MtopAnnualTaxCharge::where('mtop_annual_tax_id', $id)->delete();
            MtopAnnualTax::where('id', $id)->delete();
        }
        catch(QueryException $exception)
        {
            return response()->json(['err_msg' => $exception->getMessage()], 400);
        }


        return response()->json(['data' => 'success'], 200);

    }



}
