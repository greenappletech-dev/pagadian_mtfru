<?php

namespace App\Http\Controllers;

use App\Exports\BancaExport;
use App\Http\Requests\StoreBanca;
use App\Models\AuxiliaryEngine;
use App\Models\Banca;
use App\Models\BoatType;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;

class BancaController extends Controller
{
    private $taxpayer;
    private $bancas;
    private $auxiliary;

    public function __construct()
    {
        $this->taxpayer = new Taxpayer();
        $this->bancas = new Banca();
        $this->auxiliary = new AuxiliaryEngine();
    }


    public function index() {
        return view('fvr.banca');
    }

    public function getdata() {
        $bancas = $this->bancas->fetchAllData();
        $boat_types = BoatType::all();
        return response()->json(['bancas' => $bancas, 'boat_types' => $boat_types]);
    }

    public function destroy($id) {
        $banca = Banca::where('id', $id)->first();

        return $banca->delete()
        ? response()->json(
            ['message' => 'Record Successfully Deleted.']
            , 200)

        : response()->json(
            ['err_msg' => 'There was an Error Encountered.']
            , 401);
    }

    /* BANCA EDIT */

    public function edit($id) {
        $banca = $this->bancas->fetchDataById($id);
        $auxiliary_engine = $this->auxiliary->fetchDataByForeignKey($id);
        return view('fvr.banca_edit', compact('banca', 'auxiliary_engine'));
    }

    public function update(StoreBanca $request) {
        $banca = Banca::where('id', $request->id)->first();
        return $this->dbValues($banca, $request, 'Banca Successfully Updated');
    }

    /* BANCA ENTRY */

    public function entry() {
        return view('fvr.banca_entry');
    }

    public function search($string) {
        $operators = $this->taxpayer->fetchSearchedDataByName($string);
        return response()->json(['operators' => $operators]);
    }

    public function dbValues(Banca $data, Request $request, $message) {
        DB::beginTransaction();

        try {
            $data->operator_id = $request->operator_id;
            $data->name = strtoupper($request->name);
            $data->color = strtoupper($request->color);
            $data->length = $request->length;
            $data->width = $request->width;
            $data->dept = $request->dept;
            $data->gross_tonnage = $request->gross_tonnage;
            $data->net_tonnage = $request->net_tonnage;
            $data->make_type = strtoupper($request->make_type);
            $data->horsepower = $request->horsepower;
            $data->engine_motor_no = strtoupper($request->engine_motor_no);
            $data->cylinder = $request->cylinder;
            $data->boat_type_id = $request->boat_type_id;
            $data->fishing_gear = strtoupper($request->fishing_gear);
            $data->manning_crew = $request->manning_crew;
            $data->body_number = strtoupper($request->body_number);
            $data->save();

            if($request->auxiliary_engine != null) {
                foreach($request->auxiliary_engine as $engine) {
                    $auxiliary = new AuxiliaryEngine();
                    $auxiliary->banca_id = $data->id;
                    $auxiliary->make_type = strtoupper($engine['make_type']);
                    $auxiliary->horsepower = $engine['horsepower'];
                    $auxiliary->engine_motor_no = strtoupper($engine['engine_motor_no']);
                    $auxiliary->cylinder = $engine['cylinder'];
                    $auxiliary->save();
                }
            }
        } catch(\Illuminate\Database\QueryException $ex) {
            DB::rollback(); /* if the saving encountered an error the changes revert */
            return response()->json(['err_msg' => $ex], 401);
        }

        DB::commit();
        return response()->json(['message' => $message], 200);
    }

    public function store(StoreBanca $request) {
        $banca = new Banca();
        return $this->dbValues($banca, $request, 'Banca Successfully Saved');
    }

    public function export() {

        $export_array = [];

        $bancas = $this->bancas->fetchAllData();

        $rowcount = 1;

        /* get all auxiliary engines */

        foreach($bancas as $key=>$value) {

            $export_array[$rowcount] = [
                $value['operator'],
                $value['body_number'],
                $value['name'],
                $value['color'],
                $value['manning_crew'],
                $value['fishing_gear'],
                $value['boat_type'],
                $value['length'],
                $value['width'],
                $value['dept'],
                $value['gross_tonnage'],
                $value['net_tonnage'],
                $value['make_type'],
                $value['engine_motor_no'],
                $value['horsepower'],
                $value['cylinder']
            ];

            $auxiliary_engine = [];


            /* check if auxiliary engine is existing */
            $auxiliary = $this->auxiliary->fetchDataByForeignKey($value['id']);

            if(count($auxiliary) !== 0) {

                foreach($auxiliary as $key=>$value) {

                    $rowcount = $rowcount + 1;

                    $export_array[$rowcount] = array([
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        $value->make_type,
                        $value->engine_motor_no,
                        $value->horsepower,
                        $value->cylinder
                    ]);
                }
                $rowcount = $rowcount + 1;
            }
        }

        $export = new BancaExport($export_array);
        return Excel::download($export, 'banca_' . date('mdy') . '.xlsx');
    }


}
