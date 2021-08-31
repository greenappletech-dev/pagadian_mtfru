<?php

namespace App\Http\Controllers;

use App\Exports\BoatCaptainExport;
use App\Models\Banca;
use App\Models\BoatCaptain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Excel;

class BoatCaptainController extends Controller
{
    private $banca;
    private $captain;

    public function __construct()
    {
        $this->banca = new Banca();
        $this->captain = new BoatCaptain();
    }

    public function index() {
        return view('fvr.captain');
    }

    public function getrecord() {
        $captains = $this->captain->fetchData();
        return response()->json(['captains' => $captains]);
    }

    public function search($string) {
        $banca = $this->banca->fetchDataByName($string);
        return response()->json(['banca' => $banca]);
    }

    public function dbValues(BoatCaptain $data, Request $request, $message) {
        $data->last_name = strtoupper($request->last_name);
        $data->first_name = strtoupper($request->first_name);
        $data->middle_name = strtoupper($request->middle_name);
        $data->full_name = strtoupper($request->last_name . ', ' . $request->first_name . ' ' . $request->middle_name . '.');
        $data->license_number = strtoupper($request->license_number);
        $data->banca_id = $request->banca_id;
        return !$data->save()

            ? response()->json(
                ['err_msg' => 'There was an Error Encountered.']
                , 401)

            : response()->json(
                [ 'message' => $message]
                ,200);
    }

    public function store(Request $request) {
        $request->validate([
            'banca_id' => 'required',
            'last_name' => 'required',
        ],
        [
            'banca_id.required' => 'Banca is Required!',
            'last_name.required' => 'Last name is required',
        ]);

        $captain = new BoatCaptain();
        return $this->dbValues($captain, $request, 'Captain Successfully Saved!');
    }

    public function edit($id) {
        $captain = $this->captain->fetchDataById($id);
        return response()->json(['captain' => $captain]);
    }

    public function update(Request $request) {
        $captain = $this->captain->fetchDataById($request->id);
        return $this->dbValues($captain, $request, 'Captain Successfully Updated');
    }

    public function destroy($id) {
        $captain = $this->captain->fetchDataById($id);

        return !$captain->delete()
        ? response()->json(['err_msg' => 'There was an Error Encountered.'], 401)
        : response()->json([ 'message' => 'Captain Successfully Deleted!'],200);
    }

    public function upload(Request $request) {

        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:2048'
        ]);


        if($request->file()) {

            $original_filename = $request->file('image')->getClientOriginalName();


            $extension = pathinfo($original_filename, PATHINFO_EXTENSION);

            $boat_captain_img = $this->captain->fetchDataById($request->id);
            $file_name = time(). '_' . $boat_captain_img->last_name . '.' . $extension;


            $captain_img = BoatCaptain::where('id', $request->id)->first();
            $captain_img->image_location = $file_name;
            $captain_img->save();

            $file_path = $request->file('image')->storeAs('captain_image', $file_name, 'public');

            return response()->json(['message' => 'Successfully Uploaded!']);
        }
    }

    public function export() {
        return Excel::download(new BoatCaptainExport(), 'boat_captain_' . date('mdy') . '.xlsx');
    }
}
