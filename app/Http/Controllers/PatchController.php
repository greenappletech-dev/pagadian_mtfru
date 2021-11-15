<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use App\Models\Tricycle;
use App\Models\MtopApplication;
use Illuminate\Support\Facades\DB;

class PatchController extends Controller
{

    private function transform_status($status) {

        $status_array = array();
        $transact_type = array();

        if(!empty($status)) {
            $status_array = explode('/', $status);
        }


        foreach($status_array as $data) {

            if($data == 'R') {
                array_push($transact_type, '1');
            }

            if($data == 'T') {
                array_push($transact_type, '2');
            }

            if($data == 'CU') {
                array_push($transact_type, '3');
            }

            if($data == 'NEW') {
                array_push($transact_type, '4');
            }
        }

        return implode(',', $transact_type);
    }

    public function change_old_status_to_transact_type() {
        $tricycles = Tricycle::orderBy('id')->get();

        $test = array();
        foreach($tricycles as $tricycle) {
            Tricycle::where('id', $tricycle['id'])->first();
            $tricycle->status_old_data = $this->transform_status($tricycle['status_old_data']);
            $tricycle->save();
        }

        return 'Success!';
    }

    public function update_old_application() {
        $tricycles = Tricycle::whereNull('created_at')->orderBy('body_number')->get();
        foreach($tricycles as $tricycle) {
            DB::table('mtop_applications')->whereNull('created_at')
            ->where('body_number', $tricycle['body_number'])
            ->update(['transact_type' => $tricycle['status_old_data']]);
        }

        return 'Success!';
    }

    public function update_mobile_number() {
        $tricycles = Tricycle::whereNotNull('mobile')->where('mobile','!=','')->select('operator_id', 'mobile')->orderBy('body_number')->get();

        foreach($tricycles as $tricycle) {
            DB::table('taxpayer')->where('id', $tricycle->operator_id)
            ->update(['mobile' => $tricycle->mobile]);
        }

        return 'Success!';
    }

    public function separate_driver_fullname() {

        DB::beginTransaction();

        try
        {
            $drivers = Driver::get();

            foreach($drivers as $data)
            {
                if($data->last_name == null)
                {
                    $explode_str = str_replace('.', '', $data->full_name);
                    $explode_str = explode(',', $explode_str);
                    $update_driver_info = Driver::where('id', $data->id)->first();
                    $update_driver_info->last_name = isset($explode_str[0]) ? trim($explode_str[0]) : '';
                    $update_driver_info->first_name = isset($explode_str[1]) ? trim($explode_str[1]) : '';
                    $update_driver_info->save();
                }
            }
        }
        catch(\Illuminate\Database\QueryException $ex)
        {
            DB::rollBack();
            return $ex;
        }

        DB::commit();
        return 'Successfully!';



    }

}
