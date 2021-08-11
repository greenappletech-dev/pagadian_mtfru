<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCharges;
use App\Models\Charge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChargeController extends Controller
{

    private $charges;

    public function __construct()
    {
        $this->charges = new Charge();
    }

    public function index() {
        return view('system.charges');
    }

    public function dbValues(Charge $data, Request $request, $message) {
        $data->name = $request->name;
        $data->price = $request->price;
        return !$data->save()
        ? response()->json(['err_msg' => 'There was an Error Encountered.'], 401)
        : response()->json([ 'message' => $message],200);
    }

    public function updateM99($price){
        DB::table('m99')->where('par_code', '001')->update(['dropping_fee' => $price]);
    }

    public function getdata() {
        $charges = $this->charges->fetchData();
        return response()->json(['charges' => $charges], 200);
    }

    public function store(StoreCharges $request) {
        $charges = new Charge();
        if($request->IsDroppingChecked) { $charges->drop_default = 1; $this->updateM99($request->price); }
        return $this->dbValues($charges, $request, 'Charges Successfully Saved');
    }

    public function edit($id) {
        $charge = $this->charges->fetchDataById($id);
        return response()->json(['charge' => $charge], 200);
    }

    public function update(StoreCharges $request) {
        $charge = $this->charges->fetchDataById($request->id);
        $charge->drop_default = $request->IsDroppingChecked ?  $charge->drop_default = 1 : $charge->drop_default = null;
        if($request->IsDroppingChecked) { $this->updateM99($request->price); }
        return $this->dbValues($charge, $request, 'Charges Successfully Updated');
    }

    public function destroy($id) {
        $charge = $this->charges->fetchDataById($id);
        return $charge->delete()
            ? response()->json(['message' => 'Charges Successfully Deleted.'], 200)
            : response()->json(['err_msg' => 'There was an Error Encountered.'], 401);
    }


}
