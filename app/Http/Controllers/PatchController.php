<?php

namespace App\Http\Controllers;

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

    public function updateMobileNumber() {
        $tricycles = Tricycle::whereNotNull('mobile')->orderBy('body_number')->get();

        foreach($tricycles as $tricycle) {
            DB::table('taxpayer')->where('taxpayer_id', $tricycle->operator_id)
            ->update(['mobile' => $tricycles['mobile']]);
        }
    }
}
