<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        return view('auth.useraccount');
    }

//    public function __construct()
//    {
//        $this->middleware('is_admin')->only(['store', 'edit']);
//    }

    public function getRecords() {

        $users = User::where('role', 7)->get();

        if(count($users) > 0) {
            return response()->json([
                'users' => $users
            ], 200);
        }

        return response()->json([
            'err_msg' => 'No User Account Created Yet.'
        ], 401);
    }

    public function trimInputs($input) {

        $user_input = trim($input);

        return $user_input;
    }

    public function validateUserInput(Request $request, $action) {

        $arr = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'confirmation' => ['required', 'min:8', 'same:password'],
        ];

        if($action === 'update') {
            unset($arr['password'], $arr['confirmation'], $arr['email']);
        }

        $request->validate($arr);

    }

    public function store(Request $request) {

        $this->validateUserInput($request, 'add');

        // create new user account

        $user = new User();
        $user->name = $this->trimInputs($request->name);
        $user->email = $this->trimInputs($request->email);
        $user->admin = true;
        $user->role = 7;
        $user->password = Hash::make($this->trimInputs($request->password));

        if($user->save()) {
            return response()->json([
                'message' => 'User Successfully Added.'
            ], 200);
        }

        return response()->json([
            'err_msg' => 'There was an Error Encountered'
        ], 401);

    }

    public function getUserToEdit($id) {

        $user = User::where(
            'id', '=', $id
            )->first();

        return response()->json([
            'user' => $user
        ], 200);

    }

    public function update(Request $request) {

        $this->validateUserInput($request, 'update');

        $checkDuplicate = User::where(
                    'email' , '=', $request->email
                )
                ->where(
                    'id' , '!=', $request->id
                )->first();


        if(!empty($checkDuplicate)) {

            $duplicate_email = [
                'email' => ['This Email Address Was Already Taken.']
            ];

            return response()->json([
                'errors' => $duplicate_email
            ], 401);
        }

        //unset password and confirm password

        $user = User::where(
            'id', '=', $request->id)
            ->first();

        $user->name = $this->trimInputs($request->name);
        $user->email = $this->trimInputs($request->email);

        if($user->save()) {
            return response()->json([
                'message' => 'User Successfully Updated.'
            ], 200);
        }

        return response()->json([
            'err_msg' => 'There was an Error Encountered'
        ], 401);

    }

    public function destroy($id) {
        $user = User::where('id', '=', $id)->first();

        if(!empty($user)) {

            if($user->delete()) {
                return response()->json([
                    'message' => 'User Successfully Deleted.'
                ], 200);
            }

            return response()->json([
                'err_msg' => 'There was an Error Encountered.'
            ], 401);
        }

        return response()->json([
            'err_msg' => 'User Already Deleted.'
        ], 401);
    }

}
