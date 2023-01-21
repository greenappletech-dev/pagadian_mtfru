<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemParameterController extends Controller
{
    public function index() {
        return view('mtfru.system_parameter');
    }

    public function getrecord() {

        $systemParameter = DB::table('m99')->get();

        return response()->json([
            'body_number_from' => $systemParameter[0]->body_number_from,
            'body_number_to' => $systemParameter[0]->body_number_to,
            'mtfru_chairman' => $systemParameter[0]->mtfru_chairman
        ], 200);
    }

    public function update(Request $request) {
        if(empty($request->body_number_to) || empty($request->body_number_from)) {
            return response()->json([
                'err_msg' => 'Body Number Must have a Range'
            ], 401);
        }

        DB::table('m99')->where('par_code', '001')->update([
                'body_number_from' => $request->body_number_from,
                'body_number_to' => $request->body_number_to,
                'mtfru_chairman' => $request->mtfru_chairman
            ]);

        return response()->json([
            'message' => 'Successfully Updated!'
        ], 200);
    }

}
