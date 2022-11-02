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
        $annualtax = MtopAnnualTax::select('mtop_annual_taxes.id','transaction_date', 'name', 'status')
            ->orderBy('mtop_annual_taxes.created_at')
            ->get();

        $otherinc = \DB::table('otherinc')->where('tricycle', 'Y')->get();

        return view('mtfru.mtop_annual_tax', compact('annualtax', 'otherinc'));
    }

    public function operator($option, $string) {

        $data = Taxpayer::query()
            ->leftJoin('tricycles', 'tricycles.operator_id', 'taxpayer.id')
            ->where(function($query) use ($option, $string) {

                if($option === 'body_number')
                {
                    $query->where('tricycles.body_number', 'LIKE', '%' . $string . '%');
                }
                else
                {
                    $query->where('tricycles.body_number', 'LIKE', '%' . $string . '%');
                }

            })
            ->select('taxpayer.id', 'tricycles.body_number', 'taxpayer.full_name as operator', 'taxpayer.tel_num')
            ->get()
            ->map(function($item) {

                $units = Tricycle::where('operator_id', $item->id)->groupBy('id')->count();
                $item->no_of_unit = $units;
                return $item;

            });

        return response()->json(['data' => $data], 200);

    }

    public function tricycles($operator_id) {

        $tricycles = Tricycle::where('operator_id', $operator_id)->get();
        $dataArr = array();

        foreach($tricycles as $tricycle)
        {
            $data = \DB::table('colhdr')
                ->select(
                    'colhdr.trnx_date',
                    'otherinc.inc_desc',
                    'otherinc.id as otherinc_id',
                )
                ->leftJoin('collne2', 'collne2.or_code', 'colhdr.or_code')
                ->leftJoin('otherinc', 'otherinc.inc_code', 'collne2.inc_code')
                ->leftJoin('mtop_applications', 'mtop_applications.id', 'colhdr.mtop_application_id')
                ->leftJoin('tricycles', 'tricycles.id', 'mtop_applications.tricycle_id')
                ->where('tricycles.id', $tricycle->id)
                ->where('otherinc.annual_tax', 'Y')
                ->orderBy('trnx_date', 'desc')
                ->first();


            $dataArr[] = [
                'tricycle_id' => $tricycle->id,
                'body_number' => $tricycle->body_number,
                'make_type' => $tricycle->make_type,
                'engine_motor_no' => $tricycle->engine_motor_no,
                'chassis_no' => $tricycle->chassis_no,
                'plate_no' => $tricycle->plate_no,
                'trnx_date' => $data ? $data->trnx_date : '',
                'inc_desc' => $data ? $data->inc_desc : '',
                'otherinc_id' => $data ? $data->otherinc_id : '',
                'checked' => false,

            ];
        }

        return response()->json(['data' => $dataArr], 200);
    }

    public function validateData(Request $request)
    {
        $request->validate([
            'operator_id' => 'required',
        ],[
            'operator_id.required' => 'You must Select Operator First',
        ]);
    }

    public function store(Request $request) {

        $this->validateData($request);

//        if($this->checkIfTheTricycleNoRepeated($request))
//        {
//            return response()->json(['invalid_msg' => 'invalid'], 400);
//        }

        \DB::beginTransaction();

        try
        {
            $query = new MtopAnnualTax();
            $this->storeParentData($request, $query);
            $this->storeChildData($request->tricycle_details, $query->id);
            $this->storeChargesData($request->charges, $query->id);
        }
        catch(QueryException $ex)
        {
            \DB::rollBack();
            return response()->json(['err_msg' => $ex], 400);
        }

        \DB::commit();

        return response()->json(['data' => 'success'], 200);

    }

    public function storeParentData(Request $request, MtopAnnualTax $query) {

        $operator_info = Taxpayer::where('id', $request->operator_id)->first();
        $query->operator_id = $request->operator_id;
        $query->name = $operator_info->full_name;
        $query->address = $operator_info->address1;
        $query->mobile_number = $operator_info->mobile_number;
        $query->transaction_date = date('Y-m-d');
        $query->status = 1;
        $query->user_id = auth()->user()->id;
        $query->save();

    }

    public function storeChildData($details, $id)
    {
        $idArr = [];

        foreach($details as $detail)
        {
            if($detail['checked'] === true)
            {

                if(isset($detail['id']))
                {
                    /* this is an old data */
                    $store = MtopAnnualTaxItem::where('id', $detail['id'])->first();
                    $idArr[] = $detail['id'];
                }
                else
                {
                    $store = new MtopAnnualTaxItem();
                }

                $tricycle_info = Tricycle::where('id', $detail['tricycle_id'])->first();
                $store->mtop_annual_tax_id = $id;
                $store->tricycle_id = $tricycle_info->id;
                $store->body_number = $tricycle_info->body_number;
                $store->make_type = $tricycle_info->make_type;
                $store->engine_motor_no = $tricycle_info->engine_motor_no;
                $store->chassis_no = $tricycle_info->chassis_no;
                $store->plate_no = $tricycle_info->plate_no;
//                $store->status = 1;
                $store->save();

                $idArr[] = $store->id;
            }
        }

        if(count($idArr) > 0)
        {
            MtopAnnualTaxItem::whereNotIn('id', $idArr)->where('mtop_annual_tax_id', $id)->delete();
        }
    }

    public function storeChargesData($charges, $id)
    {

        $idArr = [];

        foreach($charges as $charge)
        {
            if(isset($charge['id']))
            {
                $data = MtopAnnualTaxCharge::where('id', $charge['id'])->first();
            }
            else
            {
                $data = new MtopAnnualTaxCharge();
            }

            $data->mtop_annual_tax_id = $id;
            $data->otherinc_id = $charge['inc_id'];
            $data->amount =  $charge['amount'];
            $data->qty =  $charge['qty'];
            $data->total =  $charge['total'];
            $data->save();

            $idArr[] = $data->id;

        }

        MtopAnnualTaxCharge::whereNotIn('id', $idArr)->where('mtop_annual_tax_id', $id)->delete();
    }


    public function edit($id)
    {
        $mtop_tax = MtopAnnualTax::where('id', $id)->first();

        $operator_data = Taxpayer::select('taxpayer.id','full_name as operator', 'body_number', 'tel_num')
            ->leftJoin('tricycles', 'tricycles.operator_id', 'taxpayer.id')
            ->where('taxpayer.id', $mtop_tax->operator_id)
            ->first();

        $tricycles = Tricycle::where('operator_id', $mtop_tax->operator_id)->get();

        $charges = MtopAnnualTaxCharge::leftJoin('otherinc', 'otherinc.id', 'mtop_annual_tax_charges.otherinc_id')
            ->select('mtop_annual_tax_charges.*', 'otherinc.inc_code', 'otherinc.inc_desc', 'otherinc.id as inc_id')
            ->where('mtop_annual_tax_id', $mtop_tax->id)->get();

        $dataArr = array();

        foreach($tricycles as $tricycle)
        {
            $data = \DB::table('colhdr')
                ->select(
                    'colhdr.trnx_date',
                    'otherinc.inc_desc',
                    'otherinc.id as otherinc_id',
                )
                ->leftJoin('collne2', 'collne2.or_code', 'colhdr.or_code')
                ->leftJoin('otherinc', 'otherinc.inc_code', 'collne2.inc_code')
                ->leftJoin('mtop_applications', 'mtop_applications.id', 'colhdr.mtop_application_id')
                ->leftJoin('tricycles', 'tricycles.id', 'mtop_applications.tricycle_id')
                ->where('tricycles.id', $tricycle->id)
                ->where('otherinc.annual_tax', 'Y')
                ->orderBy('trnx_date', 'desc')
                ->first();


            $mtop_tax_item = MtopAnnualTaxItem::where('mtop_annual_tax_id', $mtop_tax->id)
                ->where('tricycle_id', $tricycle->id)
                ->first();



            $dataArr[] = [
                'id' => $mtop_tax_item['id'],
                'tricycle_id' => $tricycle->id,
                'body_number' => $tricycle->body_number,
                'make_type' => $tricycle->make_type,
                'engine_motor_no' => $tricycle->engine_motor_no,
                'chassis_no' => $tricycle->chassis_no,
                'plate_no' => $tricycle->plate_no,
                'trnx_date' => $data ? $data->trnx_date : '',
                'inc_desc' => $data ? $data->inc_desc : '',
                'otherinc_id' => $data ? $data->otherinc_id : '',
                'checked' => (bool)$mtop_tax_item,
            ];

        }


        return response()->json(['data' => $mtop_tax, 'annual_details' => $dataArr ,'operator_data' => $operator_data, 'charges' => $charges], 200);

    }

    public function update(Request $request)
    {

        $this->validateData($request);
//
//        if($this->checkIfTheTricycleNoRepeated($request))
//        {
//            return response()->json(['invalid_msg' => 'invalid'], 400);
//        }

        \DB::beginTransaction();

        try
        {
            $query = MtopAnnualTax::where('id', $request->id)->first();
            $this->storeParentData($request, $query);
            $this->storeChildData($request->tricycle_details, $query->id);
            $this->storeChargesData($request->charges, $query->id);
        }
        catch(QueryException $ex)
        {
            \DB::rollBack();
            return response()->json(['err_msg' => $ex], 200);
        }

        \DB::commit();

        return response()->json(['data' => 'success'], 200);
    }

    public function checkIfTheTricycleNoRepeated(Request $request) {

        $getSameApplication = MtopAnnualTax::query()
            ->leftJoin('mtop_annual_tax_items', 'mtop_annual_tax_items.mtop_annual_tax_id', 'mtop_annual_taxes.id')
            ->where('operator_id', $request->operator_id)
            ->where('otherinc_id', $request->otherinc_id)
            ->where(function($query) use ($request) {
                if(isset($request->id))
                {
                    $query->where('mtop_annual_taxes.id', '!=', $request->id);
                }
            })
            ->get();

        foreach($getSameApplication as $value)
        {
            foreach($request->tricycle_details as $tricycle)
            {
                if($tricycle['checked'] && $value->tricycle_id == $tricycle['tricycle_id'])
                {
                    return true;
                }
            }
        }
    }

    public function findor($or_no) {

//        $getOR = DB::table('colhdr')
//            ->leftJoin('collne2', 'collne2.or_code', 'colhdr.or_code')
//            ->where('colhdr.or_number', 'LIKE', '%'. $or_no . '%')
//            ->where('mtop_application_id', '<=', 0)
//            ->get();
//
//        return response()->json(['data'=> $getOR]);
    }

    public function tagOR(Request $request)
    {
//        DB::table('colhdr')->where('id', $request->or_no)->update(['mtop_application_id' => $request->application_id]);
//        return response()->json(['message' => 'OR Tagged Successfully!']);
    }





}
