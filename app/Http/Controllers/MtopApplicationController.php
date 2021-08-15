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

        $mtfrb_case_no = '';

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

    public function saveDBValues(MtopApplication $data, Request $request, $message) {

        $transaction_type = [];

        if($request->renewal) {
            array_push($transaction_type, 1);
        }

        if($request->dropping) {
            array_push($transaction_type, 2);
        }

        if($request->change_unit) {
            array_push($transaction_type, 3);
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
                                ? $request-> change_unit_details['new_plate_no']
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

        $transaction_type = [];

        if($request->renewal) {
            array_push($transaction_type, 1);
        }

        if($request->dropping) {
            array_push($transaction_type, 2);
        }

        if($request->change_unit) {
            array_push($transaction_type, 3);
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
        $mtop_application = MtopApplication::where('id', $request->id)->first();
        return $this->updateDBValues($request,$mtop_application, 'Application Successfully Updated!');
    }


    /* END OF MTOP UPDATE */



    /* MTOP APPLICATION LIST */


    public function getdata_filtered($from, $to, $barangay_id) {

        /* check if transaction are paid */

        $check_paid = $this->mtop_applications->fetchFilteredData($from, $to, $barangay_id);

        foreach ($check_paid as $application) {
            if($application['status'] === 2) {
                $paid_applications = DB::table('colhdr')->where('mtop_application_id',$application['application_id'])->first();


                if($paid_applications != null) {

                    /* check if there's a change unit only transaction.
                    So we can get the last application and get the validity date
                    No renewal on change unit transaction only */


                    $transaction_type = explode(',', $application['transact_type']);


                    /* add 2 year to the validity date */

                    $validity_date = date('m/d/Y', strtotime('+2 years', strtotime($paid_applications->trnx_date)));;



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

                    /* if transaction is renewal of dropping must generate new validity date */

                    $update_application = MtopApplication::where('id', $application['application_id'])->first();
                    $update_application->status = 3;
                    $update_application->validity_date = $validity_date;
                    $update_application->save();
                }
            }
        }

        /* fetch data to display */

        $mtop_applications = $this->mtop_applications->fetchFilteredData($from, $to, $barangay_id);
        return response()->json(['mtop_applications' => $mtop_applications]);
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
        $mtop_application = $this->mtop_applications->fetchDataById($id);
        return $mtop_application->delete()
            ? response()->json(['message' => 'Application Deleted'], 200)
            : response()->json(['message' => 'Something When Wrong'], 401);
    }

    public function pdfApplication($id, $form_to_print) {
        $mtop_application = $this->mtop_applications->fetchDataForPrinting($id);
        $charges = $this->mtop_applications_charge->fetchDataById($id);
        $operator_img = $this->operator_img->fetchDataById($mtop_application->taxpayer_id);
        $transaction = DB::table('colhdr')->where('mtop_application_id', $id)->first();

        /* must check if the application is new, renewal, dropping or change unit */

        $transaction_type = explode(',',$mtop_application->transact_type);
        $application_type = $this->mtop_applications->getApplicationType($transaction_type, $mtop_application->body_number, $id);


        $data = [$mtop_application, $charges, $operator_img];

        if((int)$form_to_print === 1) {
            $blade = 'pdf_mtfrb_application';
        }

        if((int)$form_to_print === 3) {
            $blade = 'pdf_mtfrb_receipt';
//            array_push($data, $transaction->trnx_date);
        }

        if((int)$form_to_print === 4) {
            $blade = 'pdf_mtfrb_permit';
            array_push($data, $transaction->trnx_date , $application_type);
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
}

