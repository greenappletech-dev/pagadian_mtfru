<?php

namespace App\Http\Controllers;

use App\Models\MtopApplicationCharge;
use Illuminate\Http\Request;

class MtopApplicationChargeController extends Controller
{
    public function __construct()
    {
        $this->mtop_charges = new MtopApplicationCharge();
    }

    public function store(Request $request) {
        $mtop_charges = new MtopApplicationCharge();
        $mtop_charges->mtop_application_id = $request->mtop_application_id;
        $mtop_charges->otherinc_id = $request->id;
        $mtop_charges->price = $request->price;
        $mtop_charges->save();
        return $this->update_charges($request->mtop_application_id);
    }

    public function destroy($id) {
        $mtop_charge = MtopApplicationCharge::where('id', $id)->first();
        $mtop_charge->delete();
    }

    public function update_charges($id) {
        $mtop_charges = $this->mtop_charges->fetchDataById($id);
        return response()->json(['mtop_charges' => $mtop_charges], 200);
    }

}
