<?php

namespace App\Http\Controllers;
use App\Models\AvailableCharge;
use App\Models\Charge;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemSettingController extends Controller
{
   
    private $charges;

     public function __construct()
    {
        $this->charges = new Charge();

    }
   
   public function index(){
   $charges = $this->charges->fetchData();
    return view('mtfru.system_setting', compact('charges'));
   }
   public function getTransactionTypes()
{
    return response()->json([
        ['id' => 'new', 'name' => 'New'],
        ['id' => 'renewal', 'name' => 'Renewal'], 
        ['id' => 'dropping', 'name' => 'Dropping'],
        ['id' => 'change_unit', 'name' => 'Change Unit']
    ]);
}
public function getRecords() {
    $data = SystemSetting::leftJoin('available_charges', 'system_settings.id', '=', 'available_charges.system_setting_id')
       ->leftJoin('otherinc', 'available_charges.charge_id', '=', 'otherinc.id')          
    ->select(
        'system_settings.id',
        'system_settings.transaction_type',
         DB::raw("STRING_AGG(otherinc.inc_desc, ', ' ) AS charge_names"),
         DB::raw("SUM(available_charges.total_price) as total_price")   
    )
    ->groupBy('system_settings.transaction_type', 'system_settings.id')
    ->get();

    return response()->json(['data' => $data], 200);
}


public function getChargesByTransactionType($type)
{
    if (empty($type)) {
        return response()->json([]);
    }
            $charges = Charge::where('tricycle','Y')
                        ->whereNotIn('inc_desc', ['Surcharge 25% (MTFRU)', 'Interest 2% (MTFRU)'])
                        ->select('id', 'inc_desc as name', 'price', )
                        ->get();
                return response()->json($charges);
}
public function getAllCharges()
{
    if (empty($type)) {
        return response()->json([]);
    }
            $charges = Charge::where('tricycle','Y')
                        ->whereNotIn('inc_desc', ['Surcharge 25% (MTFRU)', 'Interest 2% (MTFRU)'])
                        ->select('id', 'inc_desc as name', 'price', )
                        ->get();
                return response()->json($charges);
}

public function assignCharges(Request $request)
{
    $request->validate([
        'transaction_type' => 'required',
        'charges' => 'required|array',
        'charges.*.id' => 'exists:otherinc,id',
        'charge.*.price' => 'required|numeric|min:0'
    ]);

    if($request->filled('system_setting_id')){
      $system_setting = SystemSetting::find($request->system_setting_id);
      if(!$system_setting){
         return response()->json(['message' => 'System Setting Not Found'], 404);
      }
      AvailableCharge::where('system_setting_id', $system_setting->id)->delete();
    }else{

      $existing = SystemSetting::where('transaction_type', $request->transaction_type)->first();
      if($existing){
         return response()->json(['message' => 'This transaction type already exist. Please edit the existing record instead.'], 422);
      }
      $system_setting = new SystemSetting();
      $system_setting->transaction_type = $request->transaction_type;
      $system_setting->save();
    }
    
    foreach ($request->charges as $item) {
            $available_charge = new AvailableCharge();
            $available_charge->system_setting_id = $system_setting->id;
            $available_charge->charge_id = $item['id'];
            $available_charge->total_price = $item['price'];
            $available_charge->save();
   }
       return response()->json(['message' => 'Charges assigned successfully']);

}

public function getChargesForTransactionType($type){
   $systemSetting = SystemSetting::where('transaction_type', $type)->first();
   if(!$systemSetting){
      return response()->json([]);
   }
   $charges = AvailableCharge::where('system_setting_id', $systemSetting->id)
      ->leftJoin('otherinc', 'available_charges.charge_id', '=', 'otherinc.id')
      ->select('available_charges.charge_id as id', 'otherinc.inc_desc as name', 'available_charges.total_price as price')
      ->get();
   return response()->json($charges);
}


   // public function getTransactionTypes()
   //  {
   //      return response()->json([
   //          ['id' => 1, 'name' => 'New'],
   //          ['id' => 2, 'name' => 'Renewal'], 
   //          ['id' => 3, 'name' => 'Dropping'],
   //          ['id' => 4, 'name' => 'Change Unit']
   //      ]);
   //  }

   //  public function getChargesByTransactionType($type)
   //  {
   //      // Get charges for the specific transaction type
   //      $charges = Charge::where('transaction_type', $type)
   //          ->orWhere('transaction_type', 'all') // Include charges for all types
   //          ->select('id', 'inc_desc as name', 'price')
   //          ->get();
            
   //      return response()->json($charges);
   //  }

   //  public function assignCharges(Request $request)
   //  {
   //      $request->validate([
   //          'transaction_type' => 'required|in:1,2,3,4',
   //          'charge_ids' => 'required|array',
   //          'charge_ids.*' => 'exists:charges,id'
   //      ]);

   //      // Clear existing assignments for this type
   //      DB::table('charges')
   //          ->where('transaction_type', $request->transaction_type)
   //          ->update(['transaction_type' => null]);

   //      // Assign new charges
   //      DB::table('charges')
   //          ->whereIn('id', $request->charge_ids)
   //          ->update(['transaction_type' => $request->transaction_type]);

   //      return response()->json(['message' => 'Charges assigned successfully']);
   //  }
public function getSelectedCharges($systemSettingId)
{
    $charges = AvailableCharge::where('system_setting_id', $systemSettingId)->get();
    return response()->json($charges);
}

   public function show(){
      $data = SystemSetting::all();
      return response()->json(['data' => $data], 200);
   }
   // public function getChargesByTransactionType($type){
   //       if(empty($type)){
   //       return response()->json([]);
   //    }
   //    $charges = Charge::fetchByTransactionType($type);
   //    return response()->json($charges);
   //  }

      public static function fetchByTransactionType($type) {
         return self::where('tricycle', 'Y')
            ->where('type', $type)
            ->select('id', 'inc_desc as name', 'price')
            ->orderBy('id')
            ->get();
      }

   public function store(Request $request){
      $request->validate([
         'transaction_type' => 'required|string|unique:system_settings,transaction_type',
      ], [], [
         'transactio_type' => 'Transaction Type'
      ]);
      $data = new SystemSetting();
      $data->transaction_type = $request->transaction_type;
      $data->save();

      return response()->json(['message' => 'System Setting Successfully Saved!'], 200);
   }

   public function edit($id){
      $setting = SystemSetting::find($id);
      return response()->json(['setting' => $setting], 200);
   }

   public function update(Request $request, $id){
      $request->validate([
        'transaction_type' => 'required|string|unique:system_settings,transaction_type,' .$id,
      ], [], [
         'transaction_type' => 'Transaction Type'
      ]);

      $data = SystemSetting::findOrFail($id);
      $data->transaction_type = $request->transaction_type;
      $data->save();

      return response()->json(['message' => 'System Setting Sucessfully Updated!'], 200);
   }

   public function destroy($id){
      $data = SystemSetting::find($id);
      $data->delete();
      return response()->json(['message' => 'System Setting Successfully Deleted!'], 200);
   }

   public function deleteAvailableCharge($id){

      $sytem_setting = SystemSetting::find($id);
      AvailableCharge::where('system_setting_id', $id)->delete();
      
       $sytem_setting->delete();

      return response()->json(['message' => 'Charge Deleted Successfully!']);

   }

}
