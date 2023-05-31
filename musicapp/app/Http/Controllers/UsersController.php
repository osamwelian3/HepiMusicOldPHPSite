<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator,Redirect,Response;
use DateTime;
use Carbon\Carbon;
use Session;
use Auth;
use DB;
use DataTables;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function get(Request $request)
    {
        $data = DB::table('users')->where([['status', 1], ['role_type', 2]]);
        return datatables()::of($data)
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);die();
    }

    public function detail(Request $request)
    {
        $result = ['status' => false, 'message' => ""];

        if($request->ajax()){
            $user = User::find($request->id);
            $result = ['status' => true, 'message' => '', 'data' => $user];
        }
        return response()->json($result);exit();
    }

    public function referralUsers()
    {
        return view('users.referral-users');
    }

    public function referralList(Request $request)
    {
        $loginUser = Auth::user();
        $data = DB::table('users')->where([['referral_id', $loginUser->referral_code], ['role_type', 2], ['is_deleted', 0]]);
        return datatables()::of($data)
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);die();
    }

    public function delete(Request $request)
    {
        $user = User::where('id', $request->id)->update(array('status' => '0', 'is_deleted' => '1'));

        if($user){
            $result = ['status' => true, 'message' => 'Delete successfully'];
        }else{
            $result = ['status' => false, 'message' => 'Delete fail'];
        }
        return response()->json($result);
    }
}
