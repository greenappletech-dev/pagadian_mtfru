<?php

namespace App\Http\Controllers;

use App\Exports\DriverExport;
use App\Exports\TricycleExport;
use App\Http\Middleware\TrustHosts;
use App\Http\Requests\StoreTricycle;
use App\Models\Taxpayer;
use App\Models\Tricycle;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;

class TricycleController extends Controller
{

    private $tricycles;
    private $operator;

    public function __construct()
    {
        $this->tricycles = new Tricycle();
        $this->operator = new Taxpayer();
    }

    public function index() {
        return view('mtfru.tricycle');
    }

    public function getdata(){
        $tricycles = $this->tricycles->fetchData();
        $body_number = DB::table('m99')->where('par_code', '001')->first();

        return response()->json([
            'tricycles' => $tricycles,
            'body_number' => $body_number->body_number_from,
        ], 200);
    }

    public function search($string) {
        $operators = $this->operator->fetchSearchedDataByName($string);
        return response()->json(
            ['operators' => $operators]
            , 200);
    }

    public function dbValues(Tricycle $tricycle, Request $request, $message) {
        $tricycle->operator_id = $request->operator_id;
        $tricycle->body_number = strtoupper($request->body_number);
        $tricycle->make_type = strtoupper($request->make_type);
        $tricycle->engine_motor_no = strtoupper($request->engine_motor_no);
        $tricycle->chassis_no = strtoupper($request->chassis_no);
        $tricycle->plate_no = strtoupper($request->plate_no);
        return !$tricycle->save()

        ? response()->json(
            ['err_msg' => 'There was an Error Encountered.']
            , 401)

        : response()->json(
            [ 'message' => $message]
            ,200);
    }

    public function update_body_number_settings($current_body_number) {
        $length = strlen($current_body_number);
        $convert_to_int = (int)$current_body_number;
        $converted_length = strlen($convert_to_int);
        $zeros = '';

        if($converted_length < $length) {

            /* if the length is less than the current settings must add zeros in the beginning */

            for($i = $converted_length; $i < $length; $i++) {

                $zeros = $zeros . '0';

            }
        }

        $convert_to_int = $convert_to_int + 1;
        $new_body_number = $zeros . $convert_to_int;

        return DB::table('m99')->where('par_code', '001')->update(['body_number_from' => $new_body_number]);
    }

    public function store(StoreTricycle $request) {
        $tricycle = new Tricycle();
        $this->update_body_number_settings($request->body_number);
        return $this->dbValues($tricycle, $request, 'Tricycle Successfully Saved');
    }

    public function edit($id) {
        $tricycle = $this->tricycles->fetchDataById($id);
        $operator = $this->operator->getDataByIdTricycle($tricycle->taxpayer_id);

        return response()->json([
            'tricycle' => $tricycle,
            'operator' => $operator
        ], 200);
    }

    public function update(StoreTricycle $request) {
        $tricycle = $this->tricycles->fetchDataById($request->id);
        return $this->dbValues($tricycle, $request, 'Tricycle Updated Successfully');
    }

    public function destroy($id) {
        $tricycle = $this->tricycles->fetchDataById($id);

        return $tricycle->delete()

        ? response()->json(
            ['message' => 'Record Successfully Deleted.']
            , 200)

        : response()->json(
            ['err_msg' => 'There was an Error Encountered.']
            , 401);
    }

    public function print($id) {
        $tricycle = $this->tricycles->fetchDataById($id);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.pdf_tricycle_stab', compact('tricycle'));
        return $pdf->stream();
    }

    public function pdf($size, $orientation) {
        $tricycles = $this->tricycles->fetchData();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.pdf_tricycle', compact('tricycles'))->setPaper($size, $orientation);
        return $pdf->stream();
    }

    public function export() {
        return Excel::download(new DriverExport(), 'drivers_' . date('mdy') . '.xlsx');
    }
}
