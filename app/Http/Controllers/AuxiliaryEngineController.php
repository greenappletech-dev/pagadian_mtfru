<?php

namespace App\Http\Controllers;

use App\Models\AuxiliaryEngine;
use Illuminate\Http\Request;

class AuxiliaryEngineController extends Controller
{

    private $auxiliary_engine;

    public function __construct()
    {
        $this->auxiliary_engine = new AuxiliaryEngine();
    }

    public function store(Request $request) {
        $auxiliary_engine = new AuxiliaryEngine();
        $auxiliary_engine->banca_id = $request->banca_id;
        $auxiliary_engine->make_type = strtoupper($request->make_type);
        $auxiliary_engine->horsepower = $request->horsepower;
        $auxiliary_engine->cylinder = $request->cylinder;
        $auxiliary_engine->engine_motor_no = strtoupper($request->engine_motor_no);
        $auxiliary_engine->save();
        return $this->returnData($request->banca_id);
    }

    public function edit($id) {
        $auxiliary_engine = AuxiliaryEngine::where('id', $id)->first();
        return response()->json(['auxiliary_engine' => $auxiliary_engine]);
    }

    public function destroy($id, $parent_id) {
        $auxiliary_engine = AuxiliaryEngine::where('id', $id)->first();
        $auxiliary_engine->delete();
        return $this->returnData($parent_id);
    }

    public function returnData($id) {
        $new_auxiliary_engine = $this->auxiliary_engine->fetchDataByForeignKey($id);
        return response()->json(['auxiliary_engine' => $new_auxiliary_engine]);
    }
}
