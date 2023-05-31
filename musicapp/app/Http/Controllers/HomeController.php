<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
use Carbon\Carbon;
use Session;
use Auth;
use App\User;
use App\Models\Offers;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        $loginUser = Auth::user();

        // $ip_address = request()->ip();

        $categories = DB::table('categories')->where([['status', 1], ['is_deleted', 0]])->get();
        // $songs = DB::table('songs')->where([['status', 1], ['is_deleted', 0]])->get();
        $songs = DB::table('songs')->leftJoin('votes', 'songs.id', '=', 'votes.song_id')
        ->leftJoin('likes', function($query) use ($loginUser){
            $query->on('songs.id', '=', 'likes.song_id')->where('likes.user_id', '=', (isset($loginUser->id)) ? $loginUser->id : 0);
        })->where([['songs.status', 1], ['songs.is_deleted', 0]])->select('songs.*', DB::raw('SUM(CASE WHEN (votes.vote = 1) THEN 1 ELSE 0 END) AS up_vote'), DB::raw('SUM(CASE WHEN (votes.vote = 0) THEN 1 ELSE 0 END) AS down_vote'), 'likes.id as liked', DB::raw('songs.up_votes - songs.down_votes as total_votes'))->groupBy('songs.id')->orderBy('new_position', 'asc')->get();

        $vote_arr = [];
        if ($loginUser) {
            $votes = DB::table('votes')->where([['user_id', $loginUser->id], ['is_deleted', 0]])->get();

            if ($votes->count()) {
                foreach ($votes as $key => $vote) {
                    $vote_arr[$vote->song_id]['song_id'] = $vote->song_id;
                    $vote_arr[$vote->song_id]['vote'] = $vote->vote;
                }
            }
        }
        return view('home', compact('categories', 'songs', 'vote_arr'));
    }

    public function index()
    {
        $loginUser = Auth::user();
        return view('dashboard', compact('loginUser'));
    }

    public function profile()
    {
        $loginUser = Auth::user();
        return view('admin.profile', compact('loginUser'));
    }

    public function profileDetail(Request $request)
    {
        $result = ['status' => false, 'message' => 'Something went wrong.'];
        if($request->ajax()){
            $loginUser = Auth::user();
            $result = ['status' => true, 'message' => '', 'data' => $loginUser];  
        }
        return response()->json($result);exit();
    }

    public function updateProfile(Request $request)
    {
        if($request->ajax()){
            $result = ['status' => true, 'message' => ''];
            $loginUser = Auth::user();
            if(!isset($loginUser->id)){
                $result = ['status' => false, 'message' => 'Data update fail!', 'data' => []];
            }

            $rules = array(
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|string|email|max:50|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email,' . $loginUser->id,
                // 'phone' => 'required|string',
            );

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                $result = ['status' => false, 'error' => $validator->errors()];
            }else{
                $loginUser->first_name = $request->first_name;
                $loginUser->last_name = $request->last_name;
                $loginUser->email = $request->email;
                $loginUser->phone = $request->phone;
                $loginUser->updated_at = Carbon::now();

                if($loginUser->save())
                {
                    $result = ['status' => true, 'message' => 'Profile updated successfully.'];                    
                }else{
                    $result = ['status' => false, 'message' => 'Profile update fail!'];                    
                }
            }
            return response()->json($result);exit();
        }else{
            return Redirect::to("home");
        }
    }
}
