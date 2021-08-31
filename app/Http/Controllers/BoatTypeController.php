<?php

namespace App\Http\Controllers;
use App\Models\BoatType;
use Illuminate\Http\Request;

class BoatTypeController extends Controller
{

    private $boat_type;

    public function __construct()
    {
        $this->boat_type = new BoatType();
    }


    public function index() {
        return view('fvr.boat_type');
    }

    public function getrecord() {
        $boat_types = $this->boat_type->fetchData();
        return response()->json(['boat_types' => $boat_types], 200);
    }

    public function store(Request $request) {

        $request->validate([
            'name' => ['required', 'unique:boat_types,name']
        ],[
            'name.required' => 'Boat Type Is Required'
        ]);

        /* SAVE ALL REQUEST */

        $boat_type = new BoatType();
        $boat_type->name = strtoupper($request->name);
        $boat_type->with_engine = (bool)$request->with_engine;

        return !$boat_type->save()
            ? response()->json(['error' => 'Something when wrong'], 401)
            : response()->json(['message' => 'Data Successfully Saved!'], 200);
    }

    public function edit($id) {
        $boat_type = $this->boat_type->fetchDataById($id);
        return response()->json(['boat_type' => $boat_type], 200);
    }

    public function update(Request $request) {
        $request->validate([
            'name' => ['required', 'unique:boat_types,name,' . $request->id]
        ] ,
        [
            'name.required' => 'Boat Type Is Required'
        ]);

        $boat_type = $this->boat_type->fetchDataById($request->id);
        $boat_type->name = strtoupper($request->name);
        $boat_type->with_engine = $request->with_engine;

        return !$boat_type->save()
            ? response()->json(['message' => 'Something when wrong'], 401)
            : response()->json(['message' => 'Data Successfully Updated!'], 200);
    }

    public function destroy($id) {
        $boat_type = $this->boat_type->fetchDataById($id);
        return !$boat_type->delete()
            ? response()->json(['message' => 'Something when wrong'], 401)
            : response()->json(['message' => 'Data Successfully Deleted!'], 200);
    }

}
