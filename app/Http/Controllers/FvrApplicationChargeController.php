<?php

namespace App\Http\Controllers;

use App\Models\FvrApplicationCharge;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FvrApplicationChargeController extends Controller
{

    private $fvr_charges;

    public function __construct()
    {
        $this->fvr_charges = new FvrApplicationCharge();
    }

    public function store(Request $request) {
        $fvr_charges = new FvrApplicationCharge();
        $fvr_charges->fvr_application_id = $request->fvr_application_id;
        $fvr_charges->otherinc_id = $request->id;
        $fvr_charges->price = $request->price;
        $fvr_charges->qty = $request->qty;
        $fvr_charges->tot_amnt = $request->tot_amnt;
        $fvr_charges->save();
        return $this->update_charges($request->fvr_application_id);
    }

    public function destroy($id) {
        $fvr_charge = FvrApplicationCharge::where('id', $id)->first();
        $fvr_charge->delete();
    }

    public function update_charges($id) {
        $fvr_charges = $this->fvr_charges->fetchDataByForeignId($id);
        return response()->json(['fvr_charges' => $fvr_charges], 200);
    }
}
