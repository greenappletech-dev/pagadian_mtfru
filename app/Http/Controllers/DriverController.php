<?php

namespace App\Http\Controllers;

use App\Exports\DriverExport;
use App\Exports\TricycleExport;
use App\Http\Requests\StoreDriver;
use App\Models\Driver;
use App\Models\Tricycle;
use App\Models\TricycleAssociation;
use Illuminate\Http\Request;
use Excel;

class DriverController extends Controller
{
    private $drivers;
    private $tricycles;

    public function __construct()
    {
        $this->drivers = new Driver();
        $this->tricycles = new Tricycle();
    }

    public function index() {
        $associations = TricycleAssociation::get();
        return view('mtfru.driver', compact('associations'));
    }

    public function getrecord() {
        $drivers = $this->drivers->fetchData();
        return response()->json(['drivers' => $drivers]);
    }

    public function search($string) {
        $tricycle = $this->tricycles->fetchDataByBodyNumber($string);
        return response()->json(['tricycle' => $tricycle]);
    }

    public function dbValues(Driver $driver, Request $request, $message) {
        $driver->last_name = strtoupper($request->last_name);
        $driver->first_name = strtoupper($request->first_name);
        $driver->middle_name = strtoupper($request->middle_name);
        $driver->driver_license_no = strtoupper($request->driver_license_no);
        $driver->full_name = strtoupper($request->last_name . ', ' . $request->first_name . ' ' . $request->middle_name . '.');
        $driver->tricycle_id = $request->tricycle_id;
        $driver->address = $request->address;
        $driver->mobile_number = $request->mobile_number;
        $driver->gcash = $request->gcash;
        $driver->tricycle_association_id = $request->tricycle_association_id;

        return !$driver->save()
        ? response()->json(
            ['err_msg' => 'There was an Error Encountered.']
            , 401)

        : response()->json(
            [ 'message' => $message]
            ,200);
    }

    public function store(StoreDriver $request) {
        $driver = new Driver();
        return $this->dbValues($driver, $request, 'Driver Added Successfully!');
    }

    public function edit($id) {
        $driver = $this->drivers->fetchDataById($id);
        return response()->json(['driver' => $driver]);
    }

    public function update(StoreDriver $request){
        $driver = $this->drivers->fetchDataByIdForChanges($request->id);
        return $this->dbValues($driver, $request, 'Driver Updated Successfully!');
    }

    public function destroy($id) {
        $driver = $this->drivers->fetchDataByIdForChanges($id);

        return !$driver->delete()
        ? response()->json(['err_msg' => 'There was an Error Encountered.'], 401)
        : response()->json([ 'message' => 'Driver Successfully Deleted!'],200);
    }

    public function export() {{}
        return Excel::download(new DriverExport(), 'drivers_' . date('mdy') . '.xlxs');

    }



}
