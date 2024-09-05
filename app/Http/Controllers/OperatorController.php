<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperator;
use App\Models\Barangay;
use App\Models\Operator;
use App\Models\OperatorImage;
use App\Models\Taxpayer;
use App\Models\Tricycle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class OperatorController extends Controller
{

    /**
     * @var Taxpayer
     */
    private $operators;
    private $operator_img;

    public function __construct()
    {
        $this->operators = new Taxpayer();
        $this->operator_img = new OperatorImage();
    }

    public function index() {
        return view('mtfru.operator');
    }

    public function getdata() {
        $operators = $this->operators->fetchDataPaginated();
        $barangays = Barangay::orderBy('id')->get();
        return response()->json(['operators' => $operators, 'barangays' => $barangays], 200);
    }

    public function search($string) {
        $operators = $this->operators->fetchSearchedDataPaginated($string);
        return response()->json(['operators' => $operators], 200);
    }

    public function dbValues(Taxpayer $operator, Request $request, $message) {
        $operator->last_name = strtoupper($request->last_name);
        $operator->first_name = strtoupper($request->first_name);
        $operator->mid_name = strtoupper($request->mid_name);
        $operator->suffix = strtoupper($request->suffix);
        $operator->birth_date = $request->birth_date;
        $operator->sex = strtoupper($request->sex);
        $operator->civ_stat = strtoupper($request->civ_stat);
        $operator->mobile = $request->mobile;
        $operator->email = strtoupper($request->email);
        $operator->brgy_code = $request->brgy_code;
        $operator->city_mun = $request->city_mun;
        $operator->purok = $request->purok;
        $operator->address1 = strtoupper($request->address);
        $operator->user_id = Auth::user()->name;
        $operator->full_name = strtoupper($request->last_name . ', ' . $request->first_name . ' ' . $request->mid_name);
        return !$operator->save()
            ? response()->json(['err_msg' => 'There was an Error Encountered.'], 401)
            : response()->json([ 'message' => $message],200);
    }

    private function getTaxId() {
        $tax_id = DB::table('m99')->select('tax_id')->first();
        return $tax_id->tax_id;
    }

    private function updatTaxId($prev_code) {
        $new_tax_id = substr('00000000', strlen($prev_code)) . $prev_code;
        $tax_id = DB::table('m99')->update(['tax_id' => $new_tax_id]);
    }

    public function store(StoreOperator $request) {
        DB::raw('LOCK TABLES taxpayer WRITE');
        $operator = new Taxpayer();
        $operator->tax_id = $this->getTaxId();
        $tax_id_value = (int)$this->getTaxId() + 1;
        $this->updatTaxId($tax_id_value);
        return $this->dbValues($operator, $request, 'Operator Successfully Saved');
    }

    public function edit($id) {
        $operator = $this->operators->getDataById($id);
        return response()->json(['operator' => $operator], 200);
    }

    public function update(StoreOperator $request) {
        $operator = $this->operators->getDataById($request->id);
        return $this->dbValues($operator, $request, 'Operator Successfully Updated');
    }

    public function destroy($id) {
        $operator = $this->operators->getDataById($id);
        if(!$operator->delete()) {
            return response()->json(['err_msg' => 'There was an Error Encountered']);
        }
        return response()->json(['message' => 'Operator Successfully Deleted']);
    }

    public function upload(Request $request) {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png|max:2048'
        ]);


        if($request->file()) {

            $original_filename = $request->file('image')->getClientOriginalName();
            $extension = pathinfo($original_filename, PATHINFO_EXTENSION);

            $operator_info = $this->operators->getDataById($request->id);
            $file_name = time(). '_' . $operator_info->tax_id . '.' . $extension;


            $operator_img = new OperatorImage();
            $operator_img->name = $file_name;
            $operator_img->taxpayer_id = $request->id;
            $operator_img->save();

            $file_path = $request->file('image')->storeAs('operator_image', $file_name, 'public');

            return response()->json(['message' => 'Successfully Uploaded!']);
        }

    }

    public function viewImage($id) {
        $image = OperatorImage::where('taxpayer_id', $id)->orderBy('id', 'desc')->first();
        if($image)
        {
            return response()->json(['image' => $image->name]);
        }
        else
        {
            return response()->json(['err_msg' => 'No Image Found'], 402);
        }

    }

    public function pdf($size, $orientation) {

    }

    public function export() {

    }
}
