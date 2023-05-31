<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\User;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        return view('password.view');
    }

    public function update(Request $request)
    {
        if($request->ajax()) {
            $user = Auth::user();
            $rules = array(
                'old_password' => 'required',
                'password' => 'confirmed|min:3|string|different:old_password',
            );

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                $result = ['status' => false, 'error' => $validator->errors(), 'data' => []];
            }
            elseif (!(Hash::check($request->old_password, $user->password))) {
                $result = ['status' => false, 'message' => 'Old password do not match', 'data' => []];
            }
            else
            {
                $user->password = Hash::make($request->password);
                if($user->save()){
                    $result = ['status' => true, 'message' => 'Password update successfully.', 'data' => []];
                }else{
                    $result = ['status' => false, 'message' => 'Password update fail!', 'data' => []];
                }
            }
            return response()->json($result);
        }
    }
}
