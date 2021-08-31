<?php

namespace App\Http\Controllers;

use App\Models\FvrApplicationAuxiliaryEngine;
use Illuminate\Http\Request;

class FvrApplicationAuxiliaryEngineController extends Controller
{

    public function store(Request $request) {

        $auxiliary = new FvrApplicationAuxiliaryEngine();
        $auxiliary->make_type = strtoupper($request->make_type);
        $auxiliary->horsepower = $request->horsepower;
        $auxiliary->engine_motor_no = strtoupper($request->engine_motor_no);
        $auxiliary->cylinder = $request->cylinder;
        $auxiliary->save();
    }

    public function update(Request $request) {
        $auxiliary = FvrApplicationAuxiliaryEngine::where('id', $request->id)->first();
        $auxiliary->make_type = strtoupper($request->make_type);
        $auxiliary->horsepower = $request->horsepower;
        $auxiliary->engine_motor_no = strtoupper($request->engine_motor_no);
        $auxiliary->cylinder = $request->cylinder;
        $auxiliary->save();
    }

    public function destroy($id) {
        $auxiliary = FvrApplicationAuxiliaryEngine::where('id', $id)->first();
        $auxiliary->delete();
    }
}
