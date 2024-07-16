<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMTOPApplication;
use App\Models\Barangay;
use App\Models\Charge;
use App\Models\MtopApplication;
use App\Models\MtopApplicationCharge;
use App\Models\OperatorImage;
use App\Models\Taxpayer;
use App\Models\Tricycle;
use App\Models\TricycleAssociationMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNan;

class MtopApplicationController extends Controller
{

    private $tricycles;
    private $barangays;
    private $mtop_applications;
    private $operators;
    private $charges;
    private $mtop_applications_charge;
    private $operator_img;

    public function __construct()
    {
        $this->mtop_applications = new MtopApplication();
        $this->tricycles = new Tricycle();
        $this->barangays = new Barangay();
        $this->operators = new Taxpayer();
        $this->charges = new Charge();
        $this->mtop_applications_charge = new MtopApplicationCharge();
        $this->operator_img = new OperatorImage();
    }

    public function index() {
        return view('mtfru.mtop');
    }

    public function getdata() {
        $barangays = $this->barangays->fetchData();
        $tricycles = $this->tricycles->fetchData();
        $charges = $this->charges->fetchData();

        return response()->json(['barangays' => $barangays, 'tricycles' => $tricycles, 'charges' => $charges]);
    }

    /* MTOP DATA ENTRY */

    public function create() {
        $renewal = 'n';
        return view('mtfru.mtop_entry', compact('renewal'));
    }

    public function renew($id) {
        $total_charges = 0;
        $mtop_application = $this->mtop_applications->fetchDataById($id);
        $mtop_previous_charges = $this->mtop_applications_charge->fetchDataById($id);

        $mtfrb_case_no = '';

//        $mtfrb_case_no = $mtop_application->body_number;

        /* explode and check if the array count is == 3 that means that the previous application user inputted a valid mtfrb case no */
        $arr = explode('-', $mtop_application->mtfrb_case_no);

        if (count($arr) === 3) {
            $mtfrb_case_no = $arr[2];
        }



        $arr_id = [];
        foreach($mtop_previous_charges as $charge) {
            array_push($arr_id, $charge['otherinc_id']);
            $total_charges += $charge['price'];
        }

        $charges = Charge::whereIn('id', $arr_id)->select('id','inc_desc as name', 'price')->get();
        return view('mtfru.mtop_renewal', compact('mtop_application', 'charges', 'total_charges', 'mtfrb_case_no'));
    }

    public function search($string) {
        $operator = $this->operators->fetchSearchedData($string);

        /* get previous mtfrb_case_no */

        $previous = $this->mtop_applications->fetchDataById($operator[0]->mtop_application_id);

        $mtfrb_case_no = $operator[0]->body_number;

        if($previous !== null) {

            /* explode and check if the array count is == 3 that means that the previous application user inputted a valid mtfrb case no */
            $arr = explode('-', $previous->mtfrb_case_no);
            if (count($arr) === 3) {
                $mtfrb_case_no = $arr[2];
            }

        }

        return response()->json(['operator' => $operator, 'mtfrb_case_no' => $mtfrb_case_no], 200);
    }

    public function search_new_operator($string) {
        $operator = $this->operators->fetchSearchedDataByName($string);
        return response()->json(['operator' => $operator], 200);
    }

    public function gettricycle($operator_id) {
        $tricycles = $this->tricycles->fetchDataByOperator($operator_id);
        return response()->json(['tricycles' => $tricycles], 200);
    }

    public function updateTricycleDetails($data) {
        $tricycle = Tricycle::where('id', $data['tricycle_id'])->first();
        $tricycle->operator_id = $data['operator_id'];
        $tricycle->make_type =$data['make_type'];
        $tricycle->engine_motor_no = $data['engine_motor_no'];
        $tricycle->chassis_no = $data['chassis_no'];
        $tricycle->plate_no = $data['plate_no'];
        $tricycle->save();
    }

    public function updateOperatorDetails(Request $request) {
        $barangay = $this->barangays->fetchDataById($request->barangay_id);
        $taxpayer = Taxpayer::where('id', $request->taxpayer_id)->first();
        $taxpayer->address1 = $request->address;
        $taxpayer->brgy_code = $barangay->brgy_code;
        $taxpayer->brgy_desc = $barangay->brgy_desc;
        $taxpayer->save();
    }





    public function getTransaction(Request $request)
    {
        $arr = [];

        if($request->new)
        {
            $applicationId = null;


            if(isset($request->id))
            {
                $applicationId = $request->id;
            }

            /* validate if the transaction is new. to qualified the transaction must have no previous records */

            $checkIfHasPreviousRecord = MtopApplication::where('tricycle_id', $request->tricycle_id)
                ->where(function($query) use ($applicationId) {
                    if($applicationId != null)
                    {
                        $query->where('id', '!=', $applicationId);
                    }
                })
                ->get();

            if(count($checkIfHasPreviousRecord) != 0)
            {
                return false;
            }

            array_push($arr, 4);
        }

        if($request->renewal) {
            array_push($arr, 1);
        }

        if($request->dropping) {
            array_push($arr, 2);
        }

        if($request->change_unit) {
            array_push($arr, 3);
        }

        return $arr;
    }

    public function validateData(Request $request) {


        /* get transaction type */

        $currentTransactions =  MtopApplication::where('body_number', $request->body_number)
            ->where(\DB::raw('extract(year from transact_date)'), date('Y'))
            ->where('status','!=', 4)
            ->get();

        if($currentTransactions->count() !== 0)
        {
            return response()->json(['err_msg' => 'This Body Number has a pending transaction. Unable to Proceed with this transaction'], 400);
        }


    }


    public function saveDBValues(MtopApplication $data, Request $request, $message) {


        $transaction_type = $this->getTransaction($request);


        if($transaction_type === false)
        {
            return response()->json(['err_msg' => 'This record already have previous transaction'], 401);
        }

        DB::beginTransaction();

        try {
            $data->transact_date = date("M/d/y");
            $data->mtfrb_case_no = $request->mtfrb_case_no;
            $data->tricycle_id = $request->tricycle_id;
            $data->body_number = $request->body_number;

            $data->taxpayer_id = $request->dropping
                                ? $request->dropping_details['new_operator_id']
                                : $request->taxpayer_id;

            $data->address = $request->dropping
                                ? $request->dropping_details['new_address']
                                : $request->address;

            $data->barangay_id = $request->dropping
                                ? $request->dropping_details['new_barangay_id']
                                : $request->barangay_id;

            $data->make_type = $request->change_unit
                                ? $request->change_unit_details['new_make_type']
                                : $request->make_type;

            $data->engine_motor_no = $request->change_unit
                                ? $request->change_unit_details['new_engine_motor_no']
                                : $request->engine_motor_no;

            $data->chassis_no = $request->change_unit
                                ? $request->change_unit_details['new_chassis_no']
                                : $request->chassis_no;

            $data->plate_no = $request->change_unit
                                ? $request->change_unit_details['new_plate_no']
                                : $request->plate_no;

            $data->transact_type = implode(',', $transaction_type); // implode array to save the transaction type separated by comma

            $data->status = 1;
            $data->user_id = Auth::user()->name;
            $data->save();


            /* save all transaction details */

            foreach($request->charges as $charge) {
                $charges = new MtopApplicationCharge();
                $charges->mtop_application_id = $data->id;
                $charges->otherinc_id = $charge['id'];
                $charges->price = $charge['price'];
                $charges->save();
            }

        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback(); /* if the saving encountered an error the changes revert */
            return response()->json(['err_msg' => $ex], 401);
        }

        DB::commit();
        return response()->json(['message' => $message], 200);
    }

    public function store(StoreMTOPApplication $request) {

        $validate = $this->validateData($request);


        if(!empty($validate))
        {
            return $validate;
        }

        $mtop_application = new MtopApplication();
        return $this->saveDBValues($mtop_application, $request, 'MTOP Application Created on this MTFRB # ' . $request->mtfrb_case_no);
    }


    /* END MTOP DATA ENTRY */


    /* MTOP UPDATE */

    public function getdata_update() {
        $charges = $this->charges->fetchData();
        $barangays = $this->barangays->fetchData();
        return response()->json(['charges' => $charges, 'barangays' => $barangays]);
    }

    public function edit($id) {
        $total_charges = 0;
        $mtop_application = $this->mtop_applications->fetchDataById($id);
        $mtop_charges = $this->mtop_applications_charge->fetchDataById($id);
        $tricycle_current_record = $this->tricycles->fetchDataById($mtop_application->tricycle_id);

        foreach ($mtop_charges as $totals) { $total_charges += $totals->price; }

        return view('mtfru.mtop_edit', compact('mtop_application', 'mtop_charges', 'total_charges', 'tricycle_current_record'));
    }

    public function updateDBValues(Request $request, MtopApplication $data, $message) {

        $transaction_type = $this->getTransaction($request);

        if($transaction_type === false)
        {
            return response()->json(['err_msg' => 'This record already have previous transaction'], 401);
        }

        DB::beginTransaction();

        try {

            $data->mtfrb_case_no = $request->mtfrb_case_no;
            $data->tricycle_id = $request->tricycle_id;
            $data->body_number = $request->body_number;

            $data->taxpayer_id = $request->dropping
                ? $request->dropping_details['new_operator_id']
                : $request->taxpayer_id;

            $data->address = $request->dropping
                ? $request->dropping_details['new_address']
                : $request->address;

            $data->barangay_id = $request->dropping
                ? $request->dropping_details['new_barangay_id']
                : $request->barangay_id;

            $data->make_type = $request->change_unit
                ? $request->change_unit_details['new_make_type']
                : $request->make_type;

            $data->engine_motor_no = $request->change_unit
                ? $request->change_unit_details['new_engine_motor_no']
                : $request->engine_motor_no;

            $data->chassis_no = $request->change_unit
                ? $request->change_unit_details['new_chassis_no']
                : $request->chassis_no;

            $data->plate_no = $request->change_unit
                ? $request-> change_unit_details['new_plate_no']
                : $request->plate_no;

            $data->transact_type = implode(',', $transaction_type); // implode array to save the transaction type separated by comma
            $data->save();



//            $data->transact_date = date("M/d/y");
//            $data->taxpayer_id = $request->taxpayer_id;
//            $data->address = $request->address;
//            $data->barangay_id = $request->barangay_id;
//            $data->tricycle_id = $request->tricycle_id;
//            $data->body_number = $request->body_number;
//            $data->make_type = $request->make_type;
//            $data->engine_motor_no = $request->engine_motor_no;
//            $data->chassis_no = $request->chassis_no;
//            $data->plate_no = $request->plate_no;

//            /* update tricycle details */
//
//            $this->updateTricycleDetails($request);
//
//            /* update operator details */
//
//            $this->updateOperatorDetails($request);

        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback(); /* if the saving encountered an error the changes revert */
            return response()->json(['err_msg' => $ex], 401);
        }

        DB::commit();
        return response()->json(['message' => $message], 200);
    }

    public function update(StoreMTOPApplication $request) {

        $this->validateData($request);

        $mtop_application = MtopApplication::where('id', $request->id)->first();
        return $this->updateDBValues($request,$mtop_application, 'Application Successfully Updated!');
    }

    public function update_validity(Request $request) {
        $mtop_application = MtopApplication::where('id', $request->id)->first();
        $mtop_application->validity_date = $request->validity_date;
        return $mtop_application->save()
            ? response()->json(['message' => 'Validity Date Successfully Updated!'], 200)
            : response()->json(['err_msg' => 'Failed to Save!'], 401);
    }


    /* END OF MTOP UPDATE */



    /* MTOP APPLICATION LIST */

    public function getdata_filtered($from, $to, $barangay_id) {
        /* check if transaction are paid */

        $data = $this->mtop_applications->fetchFilteredData($from, $to, $barangay_id);
        $data->getCollection()->transform(function($application){
            $validity_date = null;
            $status = null;

            if($application['status'] != 4 && $application['status'] != 1)
            {
                $storeValidityDateIfNotCancel = $this->ORDetails($application);

                if($storeValidityDateIfNotCancel)
                {
                    if($storeValidityDateIfNotCancel != 'cancelled' && $application['status'] == 2)
                    {
                        /* check if there's a change unit only transaction.
                        So we can get the last application and get the validity date
                        No renewal on change unit transaction only */
                        $transaction_type = explode(',', $application['transact_type']);
                        $validity_date = $storeValidityDateIfNotCancel;
                        $status = 3;

                        /* if the transaction is change unit retain the validity date from the previous transaction */

                        if(count($transaction_type) == 1) {
                            /* the value of change unit is 3 */

                            if((int)$transaction_type[0] === 3) {
                                $get_previous_transaction = Tricycle::leftJoin('mtop_applications', 'mtop_applications.id','tricycles.mtop_application_id')
                                    ->where('tricycles.body_number', $application['body_number'])
                                    ->first();

                                /* save previous validity date */
                                $validity_date = $get_previous_transaction->validity_date;
                            }
                        }
                    }
                    else if($storeValidityDateIfNotCancel === 'cancelled' && $application['status'] == 3)
                    {
                        $status = 1;
                    }

                    if($status)
                    {
                        $update_application = MtopApplication::where('id', $application['application_id'])->first();
                        $update_application->status = $status;
                        $update_application->validity_date = $validity_date ?? null;
                        $update_application->save();

                        /* update the list also to prevent calling the same function again */
                        $application->status = $status;
                        $application->validity_date = $validity_date ?? null;
                    }
                }
            }
            else if ($application['status'] == 4)
            {
                if($this->ORDetails($application) == 'cancelled')
                {
                    $application->cancelled = true;
                }
            }


            /*
                check if the transact date is less than the implementation date
                a additional button will be added to be able to tag the OR of the selected transaction.
            */

            $colhdr = \DB::table('colhdr')
                ->where(function($query) {
                    $query->where('colhdr.trans_type', 'MTOP')
                        ->orWhere('colhdr.trans_type', null);})
                ->where('mtop_application_id', $application->application_id)->first();

            if($application->status === 4)
            {
                $application->old_transaction = $colhdr === null;
            }

            return $application;
        });

        return response()->json(['mtop_applications' => $data]);
    }

    public function cancel($id)
    {
        $mtop_application = $this->mtop_applications->fetchDataById($id);
        $mtop_application->status = 1;
        $mtop_application->validity_date = null;

        return $mtop_application->save()
            ? response()->json(['message' => 'Application Cancelled'], 200)
            : response()->json(['message' => 'Something When Wrong'], 401);
    }

    private function ORDetails($data) {

        $checkOR = DB::table('colhdr')
            ->where('mtop_application_id', $data['application_id'])
            ->where(function($query) {
                $query->where('colhdr.trans_type', 'MTOP')
                    ->orWhere('colhdr.trans_type', null);
            })
            ->orderBy('or_number', 'desc')
            ->first();

        /* OR IS CANCELLED */

        if($checkOR)
        {
            return $checkOR->cancel !== 'Y' ? date('m/d/Y', strtotime('+2 years', strtotime($checkOR->trnx_date))) : 'cancelled';
        }
    }

    public function approve($id, $type) {

        $message = '';

        DB::beginTransaction();

        try {

            $mtop_application_update_status = $this->mtop_applications->fetchDataById($id);;

            if($type === 'approval') {
                $mtop_application_update_status->status = 4;
                $mtop_application_update_status->approve_date = date('Y-m-d');

                /* if there's a changes in case of dropping and change unit. must update all master data */

                $data = array(
                    'operator_id' => $mtop_application_update_status->taxpayer_id,
                    'tricycle_id' => $mtop_application_update_status->tricycle_id,
                    'make_type' => $mtop_application_update_status->make_type,
                    'engine_motor_no' => $mtop_application_update_status->engine_motor_no,
                    'chassis_no' => $mtop_application_update_status->chassis_no,
                    'plate_no' => $mtop_application_update_status->plate_no,
                );

                /* add mtop_application_id to the tricycle */

                $tricycle = $this->tricycles->fetchDataById($mtop_application_update_status->tricycle_id);
                $tricycle->mtop_application_id = $mtop_application_update_status->id;
                $tricycle->save();

                $this->updateTricycleDetails($data);

                $message = 'Application Approved';
            }
            else
            {
                $mtop_application_update_status->status = 2;
                $message = 'Application Ready for Payment';
            }

            $mtop_application_update_status->save();

        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback(); /* if the saving encountered an error the changes revert */
            return response()->json(['err_msg' => $ex], 401);
        }

        DB::commit();
        return response()->json(['message' => $message], 200);
    }

    public function destroy($id) {
        Tricycle::where('mtop_application_id', $id)->update(['mtop_application_id' => null]);
        MtopApplicationCharge::where('mtop_application_id', $id)->delete();
        MtopApplication::where('id', $id)->delete();
        return response()->json(['message' => 'Application Deleted'], 200);
    }

    public function pdfApplication($id, $form_to_print) {
        $data=[];
        $mtop_application = $this->mtop_applications->fetchDataForPrinting($id);
        foreach($mtop_application as $mtop){
            
            $charges = DB::table('collne2')->where('or_code', $mtop->or_code)->get();

            $operator_img = $this->operator_img->fetchDataById($mtop->taxpayer_id);

            $transaction_type = explode(',',$mtop->transact_type);
            $application_type = $this->mtop_applications->getApplicationType($transaction_type);
            $system_parameter = DB::table('m99')->where('par_code', '001')->first();
            
            $data[] = [$mtop, $charges, $operator_img];
        }
        
        // dd($data);
        

        /* must check if the application is new, renewal, dropping or change unit */

        // $data = [$mtop_application[0], $charges, $operator_img];

        // dd($data);

        if((int)$form_to_print === 1) {
            $blade = 'pdf_mtfrb_application';
            $data=$mtop_application;
        }

        if((int)$form_to_print === 3) {
            $blade = 'pdf_mtfrb_receipt';
        }

        if((int)$form_to_print === 4) {
            $blade = 'pdf_mtfrb_permit';
            $association = '';
            $getAssociation = TricycleAssociationMember::select('tricycle_associations.name as association')
                ->where('taxpayer_id', $mtop_application[0]->taxpayer_id)
                ->leftJoin('tricycle_associations', 'tricycle_associations.id', 'tricycle_association_members.tricycle_association_id')
                ->first();
            if($getAssociation) {
                $association = $getAssociation->association;
            }

            /* get the previous application */
            array_push($data[0], $application_type, $system_parameter, $association);
            $data = $data[0];
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.' . $blade, compact('data'));
        $pdf->setPaper('legal');
        return $pdf->stream();
    }


    /* END OF MTOP APPLICATION LIST */


    public function pdf($size, $orientation, $from, $to, $barangay_id) {

        /* create a custom array for the report */
        $mtop_applications = $this->mtop_applications_charge->fetchDataForReport($barangay_id, $from, $to);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.pdf_mtop_application_report', compact('mtop_applications'))
            ->setPaper($size, $orientation);

        return $pdf->stream();
    }

    public function findor($or_no) {

        $getOR = DB::table('colhdr')
            ->leftJoin('collne2', 'collne2.or_code', 'colhdr.or_code')
            ->where('colhdr.or_number', 'LIKE', '%'. $or_no . '%')
            ->where('mtop_application_id', '<=', 0)
            ->where(function($query) {
                $query->where('colhdr.trans_type', 'MTOP')
                    ->orWhere('colhdr.trans_type', null)
                    ->orWhere('colhdr.trans_type', "'");
            })
            ->get();

        return response()->json(['data'=> $getOR]);
    }

    public function tagOR(Request $request)
    {
        $or_list = $request->or_list;
        foreach($or_list as $list){
            DB::table('colhdr')
            ->where('id', $list['id'])
            ->where(function($query) {
                $query->where('colhdr.trans_type', 'MTOP')
                    ->orWhere('colhdr.trans_type', null);
            })
            ->update(['mtop_application_id' => $request->application_id, 'trans_type' => 'MTOP']);
        }

        return response()->json(['message' => 'OR Tagged Successfully!']);
    }

    public function getdata_searched($from, $to, $barangay_id, $option, $value) {
        $data = $this->mtop_applications->fetchsSearchedData($from, $to, $barangay_id, $option, $value);

        $data->getCollection()->transform(function($application){
            $validity_date = null;
            $status = null;

            if($application['status'] != 4 && $application['status'] != 1)
            {
                $storeValidityDateIfNotCancel = $this->ORDetails($application);

                if($storeValidityDateIfNotCancel)
                {
                    if($storeValidityDateIfNotCancel != 'cancelled' && $application['status'] == 2)
                    {
                        /* check if there's a change unit only transaction.
                        So we can get the last application and get the validity date
                        No renewal on change unit transaction only */
                        $transaction_type = explode(',', $application['transact_type']);
                        $validity_date = $storeValidityDateIfNotCancel;
                        $status = 3;

                        /* if the transaction is change unit retain the validity date from the previous transaction */

                        if(count($transaction_type) == 1) {
                            /* the value of change unit is 3 */

                            if((int)$transaction_type[0] === 3) {
                                $get_previous_transaction = Tricycle::leftJoin('mtop_applications', 'mtop_applications.id','tricycles.mtop_application_id')
                                    ->where('tricycles.body_number', $application['body_number'])
                                    ->first();

                                /* save previous validity date */
                                $validity_date = $get_previous_transaction->validity_date;
                            }
                        }
                    }
                    else if($storeValidityDateIfNotCancel === 'cancelled' && $application['status'] == 3)
                    {
                        $status = 1;
                    }

                    if($status)
                    {
                        $update_application = MtopApplication::where('id', $application['application_id'])->first();
                        $update_application->status = $status;
                        $update_application->validity_date = $validity_date ?? null;
                        $update_application->save();

                        /* update the list also to prevent calling the same function again */
                        $application->status = $status;
                        $application->validity_date = $validity_date ?? null;
                    }
                }
            }
            else if ($application['status'] == 4)
            {
                if($this->ORDetails($application) == 'cancelled')
                {
                    $application->cancelled = true;
                }
            }


            /*
                check if the transact date is less than the implementation date
                a additional button will be added to be able to tag the OR of the selected transaction.
            */

            $colhdr = \DB::table('colhdr')->where('mtop_application_id', $application->application_id)
                ->where(function($query) {
                    $query->where('colhdr.trans_type', 'MTOP')
                        ->orWhere('colhdr.trans_type', null);
                })
                ->first();

            if($application->status === 4)
            {
                $application->old_transaction = $colhdr === null;
            }

            return $application;
        });

        return response()->json(['mtop_applications' => $data]);
    }

}

