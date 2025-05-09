<?php

namespace App\Http\Controllers;

use App;
use App\Models\signatories;
use Carbon\Carbon;
use App\Models\Banca;
use App\Models\Charge;
use App\Models\Barangay;
use App\Models\BoatType;
use App\Models\Taxpayer;
use App\Models\FvrCharges;
use App\Models\BoatCaptain;
use Illuminate\Http\Request;
use App\Models\OperatorImage;
use App\Models\FvrApplication;
use App\Models\AuxiliaryEngine;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FvrApplicationCharge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\StoreFVRApplication;
use App\Models\FvrApplicationAuxiliaryEngine;

class FvrApplicationController extends Controller
{
    private $operators;
    private $barangays;
    private $fvr_application;
    private $bancas;
    private $auxiliary_engine;
    private $fvr_application_auxiliary_engine;
    private $boat_type;
    private $charges;
    private $boat_types;
    private $fvr_application_charges;
    private $boat_captain;
    private $operator_img;


    public function __construct()
    {


        $this->operators = new Taxpayer();
        $this->barangays = new Barangay();
        $this->bancas = new Banca();
        $this->boat_types = new BoatType();
        $this->fvr_application = new FvrApplication();
        $this->auxiliary_engine = new AuxiliaryEngine();
        $this->fvr_application_auxiliary_engine = new FvrApplicationAuxiliaryEngine();
        $this->fvr_application_charges = new FvrApplicationCharge();
        $this->charges = new FvrCharges();
        $this->boat_captain = new BoatCaptain();
        $this->operator_img = new OperatorImage();


    }

    public function index() {
        return view('fvr.fvr');
    }

    public function getdata() {



        $barangays = $this->barangays->fetchData();
        $boat_types = $this->boat_types->fetchData();
        $charges = $this->charges->fetchData();





        return response()->json(['barangays' => $barangays, 'boat_types' => $boat_types, 'charges' => $charges, 'city_code' => DB::table('m99')->select('banca_city_code')->first()->banca_city_code]);


    }

    public function getdata_filtered($from, $to, $barangay_id) {



        $fvr_applications = $this->fvr_application->fetchFilteredData($from, $to, $barangay_id);
        return response()->json(['fvr_applications' => $fvr_applications]);




    }

    public function payment(Request $request) {







        $rules = ['or_date' => ['required']];
        if($request->or_group_a === true) {
            $rules += ['or_number' => ['required', 'unique:fvr_applications,or_number', 'unique:fvr_applications,or_number_2', 'unique:fvr_applications,or_number_3', 'max:20', 'nullable']];
        }

        if($request->or_group_b === true) {
            $rules += ['or_number_2' => ['required', 'unique:fvr_applications,or_number', 'unique:fvr_applications,or_number_2', 'unique:fvr_applications,or_number_3', 'max:20', 'nullable']];
        }

        if($request->or_group_c === true) {
            $rules += ['or_number_3' => ['required', 'unique:fvr_applications,or_number', 'unique:fvr_applications,or_number_2', 'unique:fvr_applications,or_number_3', 'max:20', 'nullable']];
        }

        $request->validate($rules);








        // 'or_number' => ['required', 'unique:fvr_applications,or_number', 'max:20', 'nullable'],

        $fvr_application = $this->fvr_application->fetchDataById($request->id);
        $fvr_application->or_number = $request->or_number === null ? null : $request->or_number;
        $fvr_application->or_number_2 = $request->or_number_2 === null ? null : $request->or_number_2;
        $fvr_application->or_number_3 = $request->or_number_3 === null ? null : $request->or_number_3;
        $fvr_application->or_date = $request->or_date;
//        $fvr_application->body_number = $fvr_application->body_number . '-' . date('d', strtotime($request->or_date)) . '-' . date('y', strtotime($request->or_date));
        $fvr_application->status = 2;
        $fvr_application->validity_date = date('Y-m-d', strtotime("+1 year", strtotime($request->or_date)));






        /* if the application is new save the or date to the bancas.or_new_application_date for reference when generating body number */

        if($fvr_application->transact_type == 4)
        {
            DB::table('bancas')->where('id', $fvr_application->banca_id)->update(['or_new_application_date' => $request->or_date]);
        }





        return $fvr_application->save()
        ? response()->json(['message' => 'OR Successfully Added!'], 200)
        : response()->json(['error' => 'Something went Wrong'], 401);
    }

    public function approve($id) {

        DB::beginTransaction();

        try {




            $fvr_application = $this->fvr_application->fetchDataById($id);;
            $fvr_application_auxiliary_engine  = $this->fvr_application_auxiliary_engine->fetchDataByForeignId($id);







            /* UPDATE BANCA */

            $banca = Banca::where('id', $fvr_application->banca_id)->first();
            $banca->operator_id = $fvr_application->taxpayer_id;
            $banca->name = $fvr_application->name;
            $banca->color = $fvr_application->color;
            $banca->length = $fvr_application->length;
            $banca->width = $fvr_application->width;
            $banca->dept = $fvr_application->dept;
            $banca->gross_tonnage = $fvr_application->gross_tonnage;
            $banca->net_tonnage = $fvr_application->net_tonnage;
            $banca->make_type = $fvr_application->make_type;
            $banca->horsepower = $fvr_application->horsepower;
            $banca->engine_motor_no = $fvr_application->engine_motor_no;
            $banca->cylinder = $fvr_application->cylinder;
            $banca->boat_type_id = $fvr_application->boat_type_id;
            $banca->fishing_gear = $fvr_application->fishing_gear;
            $banca->manning_crew = $fvr_application->manning_crew;
            $banca->fvr_application_id = $id;
            $banca->save();







            /* REMOVE ALL AUXILIARY ENGINE AND REPLACE BY NEW AUXILIARY ENGINE BASED ON THE APPLICATION */

            $auxiliary_engine = AuxiliaryEngine::where('banca_id', $fvr_application->banca_id)->delete();

            foreach($fvr_application_auxiliary_engine as $new_auxiliary) {
                $auxiliary = new AuxiliaryEngine();
                $auxiliary->make_type = $new_auxiliary->make_type;
                $auxiliary->horsepower = $new_auxiliary->horsepower;
                $auxiliary->engine_motor_no = $new_auxiliary->engine_motor_no;
                $auxiliary->cylinder = $new_auxiliary->cylinder;
                $auxiliary->banca_id = $fvr_application->banca_id;
                $auxiliary->save();
            }





            $fvr_application->status = 3;
            $fvr_application->approve_date = date('Y-m-d');
            $fvr_application->save();

        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback(); /* if the saving encountered an error the changes revert */
            return response()->json(['err_msg' => $ex], 401);
        }

        DB::commit();
        return response()->json(['message' => 'Application Successfully Approved!'], 200);

    }

    public function destroy($id) {



        $fvr_application = $this->fvr_application->fetchDataById($id);
        return $fvr_application->delete()
            ? response()->json(['message' => 'Application Deleted'], 200)
            : response()->json(['message' => 'Something When Wrong'], 401);
    }




    /* CREATE FVR APPLICATION */





    public function create() {
        return view('fvr.fvr_entry');
    }

    public function search($string) {

        $banca = $this->bancas->fetchDataByBodyNumber($string);


        if(isset($banca[0]->id))
        {
            $auxiliary = $this->auxiliary_engine->fetchDataByForeignKey($banca[0]->id);
            $banca[0]->body_number = implode('-', $this->getBancaApplicationBodyNumber($banca[0]));
            return response()->json(['operator' => $banca, 'auxiliary' => $auxiliary]);
        }

    }

    public function getBancaApplicationBodyNumber($banca) {

        $date = $banca->or_new_application_date ?? Carbon::now()->format('Y-m-d');



        return [
            'city_code' => DB::table('m99')->select('banca_city_code')->first()->banca_city_code,
            'brgy_code' => $banca->banca_code,
            'code' => $banca->boat_type_code,
            'body_number' => $banca->body_number,
            'month' => str_pad(Carbon::parse($date)->format('m'), 2, 0, STR_PAD_LEFT),
            'year' => Carbon::now()->format('y')
        ];





    }

    public function search_new_operator($string) {



        $operator = $this->operators->fetchSearchedDataByName($string);
        return response()->json(['operator' => $operator], 200);



    }

    public function store(StoreFVRApplication $request) {

        $transaction_type = [];


//        $check_if_new = Banca::where('id', $request->banca_id)->whereNotNull('fvr_application_id')->count();
//
//        if(!$check_if_new) {
//            array_push($transaction_type, 4);
//        }
//        else
//        {
//
//        }

        $transaction_type = $this->getTransaction($request);

        if($transaction_type === false)
        {
            return response()->json(['err_msg' => 'This record already have previous transaction'], 401);
        }

        DB::beginTransaction();

        try {


            /* We get the master data records if no change unit */

            $master_banca_record = $this->bancas->fetchDataById($request->banca_id);
            $master_auxiliary_record = $this->auxiliary_engine->fetchDataByForeignKey($request->banca_id);

            $fvr_application = new FvrApplication();

            /* CHANGE THIS DETAILS IF THE TRANSACTION IS DROPPING */

            $fvr_application->taxpayer_id = !$request->dropping
                                            ? $request->taxpayer_id
                                            : $request->dropping_details['new_operator_id'];

            $fvr_application->barangay_id = !$request->dropping
                                            ? $request->barangay_id
                                            : $request->dropping_details['new_barangay_id'];

            $fvr_application->address = !$request->dropping
                                        ? $request->address
                                        : $request->dropping_details['new_address'];

            /* FIXED VALUE */

            $fvr_application->banca_id = $request->banca_id;
            $fvr_application->transact_date = date("M-d-y");
            $fvr_application->ctc_no = $request->ctc_no;
            $fvr_application->ctc_date = $request->ctc_date;


            /* CHANGE THIS DETAILS IF THE TRANSACTION HAS CHANGE UNIT */

            $fvr_application->name = $request->change_unit
                                    ? strtoupper($request->boat_name)
                                    : $master_banca_record->name;

            $fvr_application->color = $request->change_unit
                                    ? strtoupper($request->boat_color)
                                    : $master_banca_record->color;

            $fvr_application->length = $request->change_unit
                                    ? $request->length
                                    : $master_banca_record->length;

            $fvr_application->width = $request->change_unit
                                    ? $request->width
                                    : $master_banca_record->width;

            $fvr_application->dept = $request->change_unit
                                    ? $request->dept
                                    : $master_banca_record->dept;

            $fvr_application->gross_tonnage = $request->change_unit
                                    ? $request->gross_tonnage
                                    : $master_banca_record->gross_tonnage;

            $fvr_application->net_tonnage = $request->change_unit
                                    ? $request->net_tonnage
                                    : $master_banca_record->net_tonnage;

            $fvr_application->make_type = $request->change_unit
                                    ? strtoupper($request->make_type)
                                    : $master_banca_record->make_type;

            $fvr_application->horsepower = $request->change_unit
                                    ? $request->horsepower
                                    : $master_banca_record->horsepower;

            $fvr_application->engine_motor_no = $request->change_unit
                                    ? strtoupper($request->engine_motor_no)
                                    : $master_banca_record->engine_motor_no;

            $fvr_application->cylinder = $request->change_unit
                                    ? $request->cylinder
                                    : $master_banca_record->cylinder;

            $fvr_application->boat_type_id = $request->change_unit
                                    ? $request->boat_type_id
                                    : $master_banca_record->boat_type_id;

            $fvr_application->fishing_gear = $request->change_unit
                                    ? strtoupper($request->fishing_gear)
                                    : $master_banca_record->fishing_gear;

            $fvr_application->manning_crew = $request->change_unit
                                    ? $request->manning_crew
                                    : $master_banca_record->manning_crew;

            $fvr_application->body_number = strtoupper($request->body_number);






            if($request->dropping) {
                $banca_code = DB::table('barangay')->where('id', $request->dropping_details['new_barangay_id'])->get();
                $check_banca_code = explode('-', $request->body_number);
                $check_banca_code[1] = $banca_code[0]->banca_code;
                $fvr_application->body_number = implode('-', $check_banca_code);
            }





            $fvr_application->transact_type = implode(',', $transaction_type); // implode array to save the transaction type separated by comma
            $fvr_application->status = 1;
            $fvr_application->user_id = Auth::user()->name;
            $fvr_application->save();






            /* SAVE AUXILIARY TABLE CHECK ALSO IF THERE'S A CHANGE UNIT */

            foreach($request->change_unit ? $request->auxiliary : $master_auxiliary_record as $auxiliary) {
                $fvr_auxiliary_engine = new FvrApplicationAuxiliaryEngine();
                $fvr_auxiliary_engine->make_type = strtoupper($auxiliary['make_type']);
                $fvr_auxiliary_engine->engine_motor_no = strtoupper($auxiliary['engine_motor_no']);
                $fvr_auxiliary_engine->horsepower = $auxiliary['horsepower'];
                $fvr_auxiliary_engine->cylinder = $auxiliary['cylinder'];
                $fvr_auxiliary_engine->fvr_application_id = $fvr_application->id;
                $fvr_auxiliary_engine->save();
            }







            /* SAVE CHARGES */

            foreach($request->charges as $charge) {
                $charges = new FvrApplicationCharge();
                $charges->fvr_application_id = $fvr_application->id;
                $charges->otherinc_id = $charge['id'];
                $charges->price = $charge['price'];
                $charges->qty = $charge['qty'];
                $charges->tot_amnt = $charge['tot_amnt'];
                $charges->or_group = $charge['or_group'];
                $charges->save();
            }




        }
        catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback(); /* if the saving encountered an error the changes revert */
            return response()->json(['err_msg' => $ex], 401);
        }

        DB::commit();
        return response()->json(['message' => 'Application Successfully Save.'], 200);
    }




    /* EDIT FVR APPLICATION */




    public function edit($id) {
        $total_charges = 0;
        $fvr_application = $this->fvr_application->fetchDataById($id);
        $banca_master_data = $this->bancas->fetchDataById($fvr_application->banca_id);
        $fvr_auxiliary_engine = $this->fvr_application_auxiliary_engine->fetchDataByForeignId($id);
        $fvr_application_charges = $this->fvr_application_charges->fetchDataByForeignId($id);

        foreach($fvr_application_charges as $charges) {
            $total_charges += $charges->tot_amnt;
        }

        return view('fvr.fvr_edit', compact('fvr_application', 'banca_master_data', 'fvr_auxiliary_engine', 'fvr_application_charges', 'total_charges'));
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
            $checkIfHasPreviousRecord = FvrApplication::where('banca_id', $request->banca_id)
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


    public function update(StoreFVRApplication $request) {


        $transaction_type = [];
        $rules = [];


        if($request->status != 1)
        {
            if($request->or_number != null) {
                $rules += ['or_number' => ['unique:fvr_applications,or_number,' . request('id'), 'unique:fvr_applications,or_number_2,' . request('id'), 'unique:fvr_applications,or_number_3,' . request('id'), 'max:20', 'nullable']];
            }

            if($request->or_number_2 != null) {
                $rules += ['or_number_2' => ['unique:fvr_applications,or_number,' . request('id'), 'unique:fvr_applications,or_number_2,' . request('id'), 'unique:fvr_applications,or_number_3,' . request('id'), 'max:20', 'nullable']];
            }

            if($request->or_number_3 != null) {
                $rules += ['or_number_3' => ['unique:fvr_applications,or_number,' . request('id'), 'unique:fvr_applications,or_number_2,' . request('id'), 'unique:fvr_applications,or_number_3,' . request('id'), 'max:20', 'nullable']];
            }

            $request->validate($rules);
        }

        $transaction_type = $this->getTransaction($request);

        if($transaction_type === false)
        {
            return response()->json(['err_msg' => 'This record already have previous transaction'], 401);
        }

        DB::beginTransaction();

        try {

            $fvr_application = FvrApplication::where('id', $request->id)->first();

            /* We get the master data records if no change unit */

            $master_banca_record = $this->bancas->fetchDataById($request->banca_id);
            $master_auxiliary_record = $this->auxiliary_engine->fetchDataByForeignKey($request->banca_id);

            /* CHANGE THIS DETAILS IF THE TRANSACTION IS DROPPING */

            $fvr_application->taxpayer_id = !$request->dropping
                ? $master_banca_record->operator_id
                : $request->dropping_details['new_operator_id'];

            $fvr_application->barangay_id = !$request->dropping
                ? $master_banca_record->barangay_id
                : $request->dropping_details['new_barangay_id'];

            $fvr_application->address = !$request->dropping
                ? $master_banca_record->address
                : $request->dropping_details['new_address'];

            /* FIXED VALUE */

            $fvr_application->banca_id = $request->banca_id;
            $fvr_application->ctc_no = $request->ctc_no;
            $fvr_application->ctc_date = $request->ctc_date;

            /* CHANGE THIS DETAILS IF THE TRANSACTION HAS CHANGE UNIT */

            $fvr_application->name = $request->change_unit
                ? strtoupper($request->boat_name)
                : $master_banca_record->name;

            $fvr_application->color = $request->change_unit
                ? strtoupper($request->boat_color)
                : $master_banca_record->color;

            $fvr_application->length = $request->change_unit
                ? $request->length
                : $master_banca_record->length;

            $fvr_application->width = $request->change_unit
                ? $request->width
                : $master_banca_record->width;

            $fvr_application->dept = $request->change_unit
                ? $request->dept
                : $master_banca_record->dept;

            $fvr_application->gross_tonnage = $request->change_unit
                ? $request->gross_tonnage
                : $master_banca_record->gross_tonnage;

            $fvr_application->net_tonnage = $request->change_unit
                ? $request->net_tonnage
                : $master_banca_record->net_tonnage;

            $fvr_application->make_type = $request->change_unit
                ? strtoupper($request->make_type)
                : $master_banca_record->make_type;

            $fvr_application->horsepower = $request->change_unit
                ? $request->horsepower
                : $master_banca_record->horsepower;

            $fvr_application->engine_motor_no = $request->change_unit
                ? strtoupper($request->engine_motor_no)
                : $master_banca_record->engine_motor_no;

            $fvr_application->cylinder = $request->change_unit
                ? $request->cylinder
                : $master_banca_record->cylinder;

            $fvr_application->boat_type_id = $request->change_unit
                ? $request->boat_type_id
                : $master_banca_record->boat_type_id;

            $fvr_application->fishing_gear = $request->change_unit
                ? strtoupper($request->fishing_gear)
                : $master_banca_record->fishing_gear;

            $fvr_application->manning_crew = $request->change_unit
                ? $request->manning_crew
                : $master_banca_record->manning_crew;

            $barangay_id = !$request->dropping
                ? $master_banca_record->barangay_id
                : $request->dropping_details['new_barangay_id'];


            $banca_code = DB::table('barangay')->where('id', $barangay_id)->get();
            $check_banca_code = explode('-', $request->body_number);

//            if($banca_code[0]->banca_code != $check_banca_code[1]) {
//                $check_banca_code[1] = $banca_code[0]->banca_code;
//            }


            if($request->status != 1)
            {
                $fvr_application->or_number = $request->or_number;
                $fvr_application->or_number_2 = $request->or_number_2;
                $fvr_application->or_number_3 = $request->or_number_3;
                $fvr_application->or_date = $request->or_date;
            }


            $fvr_application->body_number = $request->body_number; //implode('-', $check_banca_code);
            $fvr_application->transact_type = implode(',', $transaction_type); // implode array to save the transaction type separated by comma
            $fvr_application->user_id = Auth::user()->name;
            $fvr_application->save();



            /* if the user
            uncheck and saved the change unit.
            return the previous auxiliary engine
            and delete the created auxiliary in fvr_auxiliary_engine
            table */



            if(!$request->change_unit) {

                /* check if there's a created auxiliary */

                FvrApplicationAuxiliaryEngine::where('fvr_application_id', $request->id)->delete();

                foreach($master_auxiliary_record as $auxiliary) {
                    $fvr_auxiliary_engine = new FvrApplicationAuxiliaryEngine();
                    $fvr_auxiliary_engine->make_type = strtoupper($auxiliary['make_type']);
                    $fvr_auxiliary_engine->engine_motor_no = strtoupper($auxiliary['engine_motor_no']);
                    $fvr_auxiliary_engine->horsepower = $auxiliary['horsepower'];
                    $fvr_auxiliary_engine->cylinder = $auxiliary['cylinder'];
                    $fvr_auxiliary_engine->fvr_application_id = $fvr_application->id;
                    $fvr_auxiliary_engine->save();
                }
            }
            else
            {
                /* this code is for check if the auxiliary has a update for deleted data */

                $new_auxiliary = [];

                /* DELETE AUXILIARY RECORD THAT NOT EXISTING ON THE LIST */

                $auxiliary = FvrApplicationAuxiliaryEngine::where('fvr_application_id', $request->id)->get();

                foreach($auxiliary as $data) {

                    $find = array_search($data['id'], array_column($request->auxiliary, 'id'));

                    /* IF EQUALS TO FALSE IT MEANS THAT THE ID HAS BEEN DELETED MUST DELETE TO THE DATABASE */

                    if($find === false) {
                        $delete_auxiliary = FvrApplicationAuxiliaryEngine::where('id', $data['id'])->first();
                        $delete_auxiliary->delete();
                    }
                }

                foreach($request->auxiliary as $auxiliary) {
                    if($auxiliary['id'] === 0) {
                        $add_auxiliary = new FvrApplicationAuxiliaryEngine();
                        $add_auxiliary->fvr_application_id = $request->id;
                        $add_auxiliary->make_type = strtoupper($auxiliary['make_type']);
                        $add_auxiliary->horsepower = $auxiliary['horsepower'];
                        $add_auxiliary->engine_motor_no = strtoupper($auxiliary['engine_motor_no']);
                        $add_auxiliary->cylinder = $auxiliary['cylinder'];
                        $add_auxiliary->save();
                        array_push($new_auxiliary, $add_auxiliary->id);
                        continue;
                    }

                    $update_auxiliary = FvrApplicationAuxiliaryEngine::where('id', $auxiliary['id'])->first();
                    $update_auxiliary->make_type = strtoupper($auxiliary['make_type']);
                    $update_auxiliary->horsepower = $auxiliary['horsepower'];
                    $update_auxiliary->engine_motor_no = strtoupper($auxiliary['engine_motor_no']);
                    $update_auxiliary->cylinder = $auxiliary['cylinder'];
                    $update_auxiliary->save();
                }
            }
        }
        catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback(); /* if the saving encountered an error the changes revert */
            return response()->json(['err_msg' => $ex], 401);
        }

        DB::commit();
        return response()->json(['message' => 'Application Successfully Save.'], 200);
    }

    public function pdfApplication($id, $form_to_print) {

        $fvr_application = $this->fvr_application->fetchDataForPrinting($id);
        $fvr_auxiliary_engine = $this->fvr_application_auxiliary_engine->fetchDataByForeignId($id);
        $boat_captain = $this->boat_captain->fetchDataByBanca($fvr_application->banca_id);
        $operator_img = $this->operator_img->fetchDataById($fvr_application->taxpayer_id);
        $m99 = DB::table('m99')->select('comp_addr', 'banca_trading')->first();
        $company_address = $m99->comp_addr;
        $trading = $m99->banca_trading;

        $engine_count = 1 + count($fvr_auxiliary_engine);

        $licenseBoatCode = 'M';

        if($fvr_application->boat_type_code == 'C') {
            $licenseBoatCode = 'C';
        }

        /* get license number */


        $arr_body_number = explode('-',$fvr_application->body_number);

        unset($arr_body_number[0]);

        $arr_body_number[2] = $licenseBoatCode . $arr_body_number[3];

        unset($arr_body_number[3]);

        $license_number = implode('-', $arr_body_number);

        $signatories = signatories::select(
            'ac_officer',
            'ac_verified',
            'ac_noted',
            'ac_approved',
            'cn_recommending',
            'cn_noted',
            'cn_approved',
            'sc_inspected',
            'sc_noted',
            'sc_approved',
            'mo_recommending',
            'mo_approved',
            'bc_recommending',
            'bc_approved',
            'cf_recommending',
            'cf_approved',
            'sf_verified',
            'sf_recommending',
            'sf_approved',
            'pb_certified',
            )
        ->first();


        // dd($signatories);

        $transaction_type = $this->fvr_application->transactionType($fvr_application->transact_type);

        /* GET AGE */

        $birthdate = Carbon::parse($fvr_application->birth_date)->age;

        $data = [
                'application' => $fvr_application,
                'auxiliary_engine' => $fvr_auxiliary_engine,
                'license_num' => $license_number,
                'transact_type' => $transaction_type,
                'boat_captain' => $boat_captain,
                'operator_img' => $operator_img,
                'engine_count' => $engine_count,
                'birthdate' => $birthdate,
                'comp_address' => $company_address,
                'trading' => $trading,
                'pb_certified' => $signatories->pb_certified,
                'ac_officer' => $signatories->ac_officer,
                'ac_verified' => $signatories->ac_verified,
                'ac_noted' => $signatories->ac_noted,
                'ac_approved' => $signatories->ac_approved,
                'cn_recommending' => $signatories->cn_recommending,
                'cn_noted' => $signatories->cn_noted,
                'cn_approved' => $signatories->cn_approved,
                'sc_inspected' => $signatories->sc_inspected,
                'sc_noted' => $signatories->sc_noted,
                'sc_approved' => $signatories->sc_approved,
                'mo_recommending' => $signatories->mo_recommending,
                'mo_approved' => $signatories->mo_approved,
                'bc_recommending' => $signatories->bc_recommending,
                'bc_approved' => $signatories->bc_approved,
                'cf_recommending' => $signatories->cf_recommending,
                'cf_approved' => $signatories->cf_approved,
                'sf_verified' => $signatories->sf_verified,
                'sf_recommending' => $signatories->sf_recommending,
                'sf_approved' => $signatories->sf_approved,
            ];

        if((int)$form_to_print === 0) {
            $user_name = Auth::user()->name;
            $blade = 'pdf_fvr_affidavit';
        }

        if((int)$form_to_print === 1) {
            $user_name = Auth::user()->name;
            $blade = 'pdf_fvr_mbol';
        }

        if((int)$form_to_print === 2) {
            $user_name = Auth::user()->name;
            $blade = 'pdf_fvr_supporting_docs';
        }

        $paper_size = (int)$form_to_print === 0 ? 'legal' : 'letter';

//        dd($data);

        $pdf = App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.' . $blade , compact('data', 'user_name'));
        $pdf->setPaper($paper_size);
        return $pdf->stream();
    }


    /* RENEWAL FVR APPLICATION */

    public function renew($id) {

        $prev_fvr_application = $this->fvr_application->fetchDataByIdForRenewal($id);
        $prev_charges = $this->fvr_application_charges->fetchDataByForeignIdForRenewal($id);
        $prev_auxiliary = $this->fvr_application_auxiliary_engine->fetchDataByForeignId($id);
        $prev_fvr_application->body_number = implode('-', $this->getBancaApplicationBodyNumber($prev_fvr_application));

        $total_charges = 0;

        foreach($prev_charges as $charges) {
            $total_charges += $charges['tot_amnt'];
        }


        return view('fvr.fvr_renewal', compact('prev_fvr_application', 'prev_charges', 'prev_auxiliary', 'total_charges'));
    }


    /* PDF  REPORT */

    public function pdf($size, $orientation, $from, $to, $barangay_id) {

        /* create a custom array for the report */
        $mtop_applications = $this->fvr_application_charges->fetchDataForReport($barangay_id, $from, $to);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.pdf_fvr_application_report', compact('mtop_applications'))
            ->setPaper($size, $orientation);

        return $pdf->stream();
    }


    /* or group */

    public function or_group($id) {
        return response()->json(['or_group' => FvrApplicationCharge::select('or_group', DB::raw('count(*) as count'))->where('fvr_application_id', $id)->groupBy('or_group')->get()]);
    }





}
