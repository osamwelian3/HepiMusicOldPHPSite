<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Songs;
use App\Models\Categories;
use App\Models\Votes;
use App\Models\Likes;
use Validator,Redirect,Response;
use DateTime;
use Carbon\Carbon;
use Session;
use Auth;
use DB;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SongsController extends Controller
{
    public function index()
    {
        $loginUser = Auth::user();
        $all_categories = DB::table('categories')->where([['status', 1], ['is_deleted', 0]])->get();
        if ($loginUser) {
            return view('songs.index', compact('all_categories'));
        }
        return Redirect('login');
    }

    public function listen(Request $request, $file)
    {
        $model = Songs::where(['mp3_file' => $file])->first();

        return view('songs.listen', compact('model'));
    }

    public function get(Request $request)
    {
        // $data = Songs::query()->where('is_deleted', '=', 0);
        $data = Songs::with('getCategory')->where('is_deleted', '=', 0)->select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->orderBy('new_position', 'asc')->get();
        return datatables()::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->addColumn('total_votes', function ($row) {
                $html = '<button type="button" class="btn btn-sm btn-success up_vote_btn" data-id="'.$row->id.'" data-votes="'.$row->up_votes.'" data-song="'.$row->song_title.'"><i class="fa fa-thumbs-up"></i> '.$row->up_votes.'</button>
                    <button type="button" class="btn btn-sm btn-danger down_vote_btn" data-id="'.$row->id.'" data-votes="'.$row->down_votes.'" data-song="'.$row->song_title.'"><i class="fa fa-thumbs-down"></i> '.$row->down_votes.'</button>';
                return $html;
            })
            ->escapeColumns([])
            ->make(true);die();
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $song = Songs::where('id', $id)->update(['status' => $status]);

        if ($song) {
            $result = ['status' => true, 'message' => __('Status changed successfully.'), 'data' => []];
        } else {
            $result = ['status' => false, 'message' => __('Error in saving data.'), 'data' => []];
        }
        return response()->json($result);exit();
    }

    public function addupdate(Request $request)
    {
        if ($request->ajax()) {
            if ($request->id) {
                $rules = array(
                    'category_id' => 'required',
                    'song_title' => ['required', 'string', 'max:255', Rule::unique('songs')->ignore($request->id)],
                    'mp3_file' => 'mimes:mp3',
                    'thumbnail' => 'image|mimes:jpeg,png,jpg',
                );
            } else {
                $rules = array(
                    'category_id' => 'required',
                    'song_title' => ['required', 'string', 'max:255', Rule::unique('songs')->ignore($request->id)],
                    'mp3_file' => 'required|mimes:mp3',
                    'thumbnail' => 'required|image|mimes:jpeg,png,jpg',
                );
            }

            $messages = [
                'category_id.required' => __('The category field is required.'),
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $result = ['status' => false, 'error' => $validator->errors()];
            } else {
                $succssmsg = 'Song created successfully.';
                $loginUser = Auth::user();
                if ($request->id) {
                    $model = Songs::where('id', $request->id)->first();
                    if ($model) {
                        $song = $model;
                        $succssmsg = 'Song updated successfully.';
                    } else {
                        $result = ['status' => false, 'message' => 'Invalid request.', 'data' => []];
                        return response()->json($result);
                    }
                } else {
                    $find_position = DB::table('songs')->max('new_position');

                    if ($find_position) {
                        $position = $find_position + 1;
                    } else {
                        $position = '1';
                    }

                    $song = new Songs;
                    $song->new_position = $position;
                    $song->old_position = $position;
                    $song->status = '1';
                    $song->created_by = $loginUser->id;
                }

                if ($request->hasFile('mp3_file')) {
                    if ($song->mp3_file != '') {
                        if (file_exists(public_path('storage/uploads/') . $song->mp3_file)) {
                            unlink(public_path('storage/uploads/') . $song->mp3_file);
                        }
                    }                
                    $file_name = $request->file('mp3_file')->hashName();
                    request()->mp3_file->move(public_path('storage/uploads'), $file_name);                
                    $song->mp3_file = $file_name;
                }

                if ($request->hasFile('thumbnail')) {
                    if ($song->thumbnail != '') {
                        if (file_exists(public_path('storage/uploads/') . $song->thumbnail)) {
                            unlink(public_path('storage/uploads/') . $song->thumbnail);
                        }
                    }                
                    $file_name = $request->file('thumbnail')->hashName();
                    request()->thumbnail->move(public_path('storage/uploads'), $file_name);
                    $song->thumbnail = $file_name;
                }

                $song->category_id = ($request->category_id) ? $request->category_id : NULL;
                $song->song_title = ($request->song_title) ? $request->song_title : NULL;
                $song->updated_by = $loginUser->id;
                $song->updated_at = Carbon::now();

                if($song->save()) {
                    $result = ['status' => true, 'message' => $succssmsg, 'data' => []];
                } else {
                    $result = ['status' => false, 'message' => 'Error in saving data.', 'data' => []];
                }
            }
        } else {
            $result = ['status' => false, 'message' => 'Invalid request.', 'data' => []];
        }
        return response()->json($result);
    }

    public function detail(Request $request)
    {
        $result = ['status' => false, 'message' => ""];

        if ($request->ajax()) {
            $song = Songs::find($request->id);
            $song->mp3_file = ($song->mp3_file) ? asset('storage/uploads')."/".$song->mp3_file : asset('backend/images/noimage1.jpg');
            $song->thumbnail = ($song->thumbnail) ? asset('storage/uploads')."/".$song->thumbnail : asset('backend/images/noimage1.jpg');
            $result = ['status' => true, 'message' => '', 'data' => $song];  
        }
        return response()->json($result);exit();
    }

    public function vote_old(Request $request)
    {
        $loginUser = Auth::user();

        $ip_address = request()->ip();

        if ($ip_address) {
            $song_id = $request->song_id;
            $vote = $request->vote;

            $find_vote = Votes::where([['ip_address', 'like', '%'.$ip_address.'%'], ['song_id', $song_id]])->first();

            if (!$find_vote) {
                $model = new Votes;
                $model->ip_address = $ip_address;
                $model->song_id = $song_id;
                $model->vote = $vote;

                if ($model->save()) {
                    $song = Songs::find($song_id);

                    if ($model->vote == '1') {
                        $song->up_votes++;

                        if ($find_vote) {
                            $song->down_votes--;
                        }
                    } else {
                        $song->down_votes++;

                        if ($find_vote) {
                            $song->up_votes--;
                        }
                    }
                    $song->save();

                    $count_up_votes = ($song->up_votes) ? $song->up_votes : 0;
                    $count_down_votes = ($song->down_votes) ? $song->down_votes : 0;

                    $result = ['status' => true, 'message' => 'Vote saved successfully.', 'data' => ['vote' => $vote, 'song_id' => $song_id, 'count_up_votes' => $count_up_votes, 'count_down_votes' => $count_down_votes]];
                } else {
                    $result = ['status' => false, 'message' => 'Error in saving data.', 'data' => []];
                }
            } else {
                $model = $find_vote;

                $current_date = date('Y-m-d H:i:s', strtotime('-1 day'));
                $updated_date = date('Y-m-d H:i:s', strtotime($model->updated_at));

                if ($updated_date > $current_date) {
                    $result = ['status' => false, 'message' => 'You can only vote once in 24 hours.', 'data' => []];
                } else {
                    $model->vote = $vote;

                    if ($model->save()) {
                        $song = Songs::find($song_id);

                        if ($model->vote == '1') {
                            $song->up_votes++;

                            if ($find_vote) {
                                $song->down_votes--;
                            }
                        } else {
                            $song->down_votes++;

                            if ($find_vote) {
                                $song->up_votes--;
                            }
                        }
                        $song->save();

                        $count_up_votes = ($song->up_votes) ? $song->up_votes : 0;
                        $count_down_votes = ($song->down_votes) ? $song->down_votes : 0;

                        $result = ['status' => true, 'message' => 'Vote saved successfully.', 'data' => ['vote' => $vote, 'song_id' => $song_id, 'count_up_votes' => $count_up_votes, 'count_down_votes' => $count_down_votes]];
                    }
                }
            }
        } else {
            $result = ['status' => false, 'message' => 'No IP Address.', 'data' => []];
        }
        return response()->json($result);
    }

    public function vote(Request $request)
    {
        $loginUser = Auth::user();

        if ($loginUser) {
            $song_id = $request->song_id;
            $vote = $request->vote;

            $find_vote = Votes::where([['user_id', $loginUser->id], ['song_id', $song_id]])->first();

            if (!$find_vote) {
                $model = new Votes;
                $model->user_id = $loginUser->id;
                $model->song_id = $song_id;
            } else {
                $model = $find_vote;
            }

            $model->vote = $vote;

            if ($model->save()) {
                $song = Songs::find($song_id);
                $old_position = $song->new_position;

                if ($model->vote == '1') {
                    // $current_votes = $song->up_votes + 1;
                    $aaa = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->where('id', '=', $song_id)->first();
                    $current_votes = $aaa->total_votes + 1;
                    $get_position = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->having('total_votes', '=', $current_votes)->get();

                    $small_position = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->having('total_votes', '<', $current_votes)->where([['new_position', '<', $song->new_position]])->get();

                    if ($get_position->count()) {
                        foreach ($get_position as $change_position) {
                            $old_p = $change_position->new_position;

                            $change_position->old_position = $old_p;
                            $change_position->new_position = $change_position->new_position + 1;
                            $change_position->save();
                        }

                        $song->old_position = $old_position;
                        $song->new_position = $song->new_position - $get_position->count();
                    }

                    if ($small_position->count()) {
                        foreach($small_position as $s_pos) {
                            $old_p = $s_pos->new_position;

                            $s_pos->old_position = $old_p;
                            $s_pos->new_position = $s_pos->new_position + 1;
                            $s_pos->save();
                        }

                        $song->old_position = $old_position;
                        $song->new_position = $song->new_position - $small_position->count();
                    }

                    $song->up_votes++;

                    if ($find_vote) {
                        $song->down_votes--;
                    }
                } else {
                    // $current_votes = $song->down_votes + 1;
                    $aaa = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->where('id', '=', $song_id)->first();
                    $current_votes = $aaa->total_votes - 1;
                    $get_position = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->having('total_votes', '=', $current_votes)->get();

                    $small_position = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->having('total_votes', '>', $current_votes)->where([['new_position', '>', $song->new_position]])->get();

                    if ($get_position->count()) {
                        foreach ($get_position as $change_position) {
                            $old_p = $change_position->new_position;

                            $change_position->old_position = $old_p;
                            $change_position->new_position = $change_position->new_position - 1;
                            $change_position->save();
                        }

                        $song->old_position = $old_position;
                        $song->new_position = $song->new_position + $get_position->count();
                    }

                    if ($small_position->count()) {
                        foreach($small_position as $s_pos) {
                            $old_p = $s_pos->new_position;

                            $s_pos->old_position = $old_p;
                            $s_pos->new_position = $s_pos->new_position - 1;
                            $s_pos->save();
                        }

                        $song->old_position = $old_position;
                        $song->new_position = $song->new_position + $small_position->count();
                    }

                    $song->down_votes++;

                    if ($find_vote) {
                        $song->up_votes--;
                    }
                }
                $song->save();

                // $total_up_votes = DB::table('votes')->where([['song_id', $song_id], ['vote', 1]])->get();
                // $total_down_votes = DB::table('votes')->where([['song_id', $song_id], ['vote', 0]])->get();
                // $count_up_votes = count($total_up_votes);
                // $count_down_votes = count($total_down_votes);
                $count_up_votes = ($song->up_votes) ? $song->up_votes : 0;
                $count_down_votes = ($song->down_votes) ? $song->down_votes : 0;

                $result = ['status' => true, 'message' => 'Vote saved successfully.', 'data' => ['vote' => $vote, 'song_id' => $song_id, 'count_up_votes' => $count_up_votes, 'count_down_votes' => $count_down_votes]];
            } else {
                $result = ['status' => false, 'message' => 'Error in saving data.', 'data' => []];
            }
        } else {
            $result = ['status' => false, 'message' => 'Please login to vote.', 'data' => []];
        }
        return response()->json($result);
    }

    public function filter(Request $request)
    {
        $loginUser = Auth::user();

        // $ip_address = request()->ip();

        if(isset($_POST['category']) && $_POST['category'] != '') {
            $categoryArr = $_POST['category'];
            // $imp_category_id = implode(",", $categoryArr);

            if ($categoryArr == 'all') {
                $songs = DB::table('songs')->leftJoin('votes', 'songs.id', '=', 'votes.song_id')
                ->leftJoin('likes', function($query) use ($loginUser){
                    $query->on('songs.id', '=', 'likes.song_id')->where('likes.user_id', '=', (isset($loginUser->id)) ? $loginUser->id : 0);
                })->where([['songs.status', 1], ['songs.is_deleted', 0]])->select('songs.*', DB::raw('SUM(CASE WHEN (votes.vote = 1) THEN 1 ELSE 0 END) AS up_vote'), DB::raw('SUM(CASE WHEN (votes.vote = 0) THEN 1 ELSE 0 END) AS down_vote'), 'likes.id as liked')->groupBy('songs.id')->orderBy('new_position', 'asc')->get();
            } else {
                $songs = DB::table('songs')->leftJoin('votes', 'songs.id', '=', 'votes.song_id')
                ->leftJoin('likes', function($query) use ($loginUser){
                    $query->on('songs.id', '=', 'likes.song_id')->where('likes.user_id', '=', (isset($loginUser->id)) ? $loginUser->id : 0);
                })->where([['songs.category_id', $categoryArr]])->select('songs.*', DB::raw('SUM(CASE WHEN (votes.vote = 1) THEN 1 ELSE 0 END) AS up_vote'), DB::raw('SUM(CASE WHEN (votes.vote = 0) THEN 1 ELSE 0 END) AS down_vote'), 'likes.id as liked')->groupBy('songs.id')->get();
            }

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

            if ($songs->count()) {
                ?>
                <div class="right-panel display_for_pc">
                    <?php
                    $i = 1;
                    foreach ($songs as $song) {
                        ?>
                        <div class="row song_row">
                            <div class="song_details">
                                <div class="col-md-9 col-xs-9 song_img">
                                    <div class="col-md-1 col-xs-1">
                                        <span style="font-size: 18px; color: #fff;"><?php echo $i ?></span>
                                    </div>

                                    <div class="col-md-2 col-xs-2 al_center">
                                        <img src="<?php echo asset('storage/uploads') ?>/<?php echo $song->thumbnail ?>" class="song_thumbnail">
                                    </div>

                                    <div class="col-md-1 col-xs-1">
                                        <?php
                                        if ($song->new_position < $song->old_position) {
                                            ?>
                                            <span class="song_label_up"><i class="fa fa-arrow-circle-o-up" style="margin-top: 1px;"></i></span>
                                            <?php
                                        } elseif ($song->new_position > $song->old_position) {
                                            ?>
                                            <span class="song_label_down"><i class="fa fa-arrow-circle-o-down" style="margin-top: 1px;"></i></span>
                                            <?php
                                        } else {
                                            ?>
                                            <span class="song_label_new">NEW</span>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <?php
                                        $song_title = strlen($song->song_title) > 15 ? substr($song->song_title, 0, 15)."..." : $song->song_title;
                                    ?>

                                    <div class="col-md-3 col-xs-3 al_center song_title_pc" title="<?php echo $song->song_title ?>"><?php echo $song_title ?></div>
                                    <div class="col-md-3 col-xs-3 al_center song_title_mobile" title="<?php echo $song->song_title ?>" style="display: none;"><?php echo $song->song_title ?></div>

                                    <div class="col-md-5 col-xs-5 al_center">
                                        <audio preload="auto" controls controlsList="nodownload noplaybackrate" class="audio_box" id="<?php echo $song->id ?>" data-id="<?php echo $song->id ?>" style="width: 100%;">
                                            <source src="<?php echo asset('storage') ?>/uploads/<?php echo $song->mp3_file ?>" type="audio/mpeg">
                                        </audio>
                                    </div>
                                </div>

                                <div class="col-md-3 col-xs-3 al_center btn_container" id="vote_<?php echo $song->id ?>" style="text-align: right;">
                                    <?php
                                        $up_vote = "";
                                        $down_vote = "";
                                        $liked = "";
                                        $liked_class = "like_btn";
                                        $liked_title = "Like";
                                        $thumbs_up = "thumbs_btn";
                                        $thumbs_down = "thumbs_btn";
                                        $thumbs_btn = "thumbs_btn";

                                        if (array_key_exists($song->id, $vote_arr)) {
                                            $thumbs_btn = "";
                                            $vote = $vote_arr[$song->id]['vote'];
                                            if ($vote==1) {
                                                $up_vote = "background: #F3B106 !important; color: #fff !important;";
                                                $thumbs_up = "";
                                            } else {
                                                $down_vote = "background: #F3B106 !important; color: #fff !important;";
                                                $thumbs_down = "";
                                            }
                                        }

                                        if (isset($song->liked) && $song->liked && $loginUser) {
                                            $liked = "background: red !important; color: #fff !important;";
                                            $liked_class = "liked";
                                            $liked_title = "Liked";
                                        }
                                    ?>
                                    <!-- <span class="<?php echo $liked_class ?>" data-id="<?php echo $song->id ?>" title="<?php echo $liked_title ?>" style="<?php echo $liked ?>"><i class="fa fa-heart"></i></span> -->
                                    <span class="share_song_btn" data-id="<?php echo $song->id ?>" title="Share"><i class="fa fa-share-alt"></i></span>

                                    <div class="share_box share_box_<?php echo $song->id ?>" data-id="<?php echo $song->id ?>">
                                        <p class="share_icons">
                                            <span class="social_btn btn_facebook" title="Facebook" onclick="window.open('//www.facebook.com/sharer.php?u=<?php echo route('songs.listen', $song->mp3_file) ?>', '', 'width=600, height=800');"><i class="fa fa-facebook"></i></span>

                                            <!-- <span class="btn_whatsapp" title="WhatsApp" onclick="window.open('https://web.whatsapp.com://send?text=This is whatsapp sharing example using button');"><i class="fa fa-whatsapp"></i></span> -->

                                            <a href="https://api.whatsapp.com/send?text=<?php echo route('songs.listen', $song->mp3_file) ?>" target="_blank">
                                                <span class="social_btn btn_whatsapp" title="WhatsApp"><i class="fa fa-whatsapp"></i></span>
                                            </a>

                                            <span class="social_btn btn_twitter" title="Twitter" onclick="window.open('//twitter.com/share?url=<?php echo route('songs.listen', $song->mp3_file) ?>', '', 'width=600, height=800');"><i class="fa fa-twitter"></i></span>
                                        </p>
                                    </div>

                                    <span class="<?php echo $thumbs_up ?> vote_style up_vote" data-id="<?php echo $song->id ?>" data-vote="1" title="Like" style="<?php echo $up_vote  ?>"><i class="fa fa-thumbs-up"></i> <span class="up_count"><?php echo $song->up_votes ?></span></span>

                                    <span class="<?php echo $thumbs_down ?> vote_style down_vote" data-id="<?php echo $song->id ?>" data-vote="0" title="Dislike" style="<?php echo $down_vote  ?>"><i class="fa fa-thumbs-down"></i> <span class="down_count"><?php echo $song->down_votes ?></span></span>
                                </div>
                            </div>
                        </div>
                        <?php
                        $i++;
                    }
                    ?>
                </div>

                <div class="right-panel display_for_mobile" style="display: none;">
                    <?php
                    $i = 1;
                    foreach ($songs as $song) {
                        $song_title = strlen($song->song_title) > 12 ? substr($song->song_title, 0, 12)."..." : $song->song_title;

                        ?>
                        <div class="row song_row">
                            <div class="col-md-2 col-xs-2" style="padding: 0;">
                                <?php
                                if ($song->new_position < $song->old_position) {
                                    ?>
                                    <span class="song_label_up"><?php echo $i ?> <i class="fa fa-arrow-circle-o-up" style="margin-top: 1px;"></i></span>
                                    <?php
                                } elseif ($song->new_position > $song->old_position) {
                                    ?>
                                    <span class="song_label_down"><?php echo $i ?> <i class="fa fa-arrow-circle-o-down" style="margin-top: 1px;"></i></span>
                                    <?php
                                } else {
                                    ?>
                                    <span class="song_label_new"><?php echo $i ?> -</span>
                                    <?php
                                }
                                ?>

                                <img src="<?php echo asset('storage/uploads') ?>/<?php echo $song->thumbnail ?>" class="song_thumbnail">
                            </div>

                            <div class="col-md-10 col-xs-10">
                                <div class="row song_detail_upper">
                                    <div class="col-md-3 col-xs-3" title="<?php echo $song->song_title ?>" style="width: 80%; color: #fff;"><?php echo $song_title ?></div>

                                    <div class="col-md-9 col-xs-9 btn_container" id="vote_<?php echo $song->id ?>">
                                        <?php
                                        $up_vote = "";
                                        $down_vote = "";
                                        $liked = "";
                                        $liked_class = "like_btn";
                                        $liked_title = "Like";
                                        $thumbs_up = "thumbs_btn";
                                        $thumbs_down = "thumbs_btn";
                                        $thumbs_btn = "thumbs_btn";

                                        if (array_key_exists($song->id, $vote_arr)) {
                                            $thumbs_btn = "";
                                            $vote = $vote_arr[$song->id]['vote'];
                                            if ($vote==1) {
                                                $up_vote = "background: #F3B106 !important; color: #fff !important;";
                                                $thumbs_up = "";
                                            } else {
                                                $down_vote = "background: #F3B106 !important; color: #fff !important;";
                                                $thumbs_down = "";
                                            }
                                        }

                                        if (isset($song->liked) && $song->liked && $loginUser) {
                                            $liked = "background: red !important; color: #fff !important;";
                                            $liked_class = "liked";
                                            $liked_title = "Liked";
                                        }
                                        ?>

                                        <span class="share_song_btn" data-id="<?php echo $song->id ?>" title="Share"><i class="fa fa-share-alt"></i></span>

                                        <div class="share_box share_box_<?php echo $song->id ?>" data-id="<?php echo $song->id ?>">
                                            <p class="share_icons">
                                                <span class="social_btn btn_facebook" title="Facebook" onclick="window.open('//www.facebook.com/sharer.php?u=<?php echo route('songs.listen', $song->mp3_file) ?>', '', 'width=600, height=800');"><i class="fa fa-facebook"></i></span>
                                                
                                                <a href="https://api.whatsapp.com/send?text=<?php echo route('songs.listen', $song->mp3_file) ?>" target="_blank">
                                                    <span class="social_btn btn_whatsapp" title="WhatsApp"><i class="fa fa-whatsapp"></i></span>
                                                </a>

                                                <span class="social_btn btn_twitter" title="Twitter" onclick="window.open('//twitter.com/share?url=<?php echo route('songs.listen', $song->mp3_file) ?>', '', 'width=600, height=800');"><i class="fa fa-twitter"></i></span>
                                            </p>
                                        </div>

                                        <span class="<?php echo $thumbs_up ?> vote_style up_vote" data-id="<?php echo $song->id ?>" data-vote="1" title="Up Vote" style="<?php echo $up_vote  ?>"><i class="fa fa-thumbs-up"></i> <span class="up_count"><?php echo $song->up_votes ?></span></span>

                                        <span class="<?php echo $thumbs_down ?> vote_style down_vote" data-id="<?php echo $song->id ?>" data-vote="0" title="Down Vote" style="<?php echo $down_vote  ?>"><i class="fa fa-thumbs-down"></i> <span class="down_count"><?php echo $song->down_votes ?></span></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <audio preload="auto" controls controlsList="nodownload noplaybackrate" class="audio_box" id="<?php echo $song->id ?>" data-id="<?php echo $song->id ?>">
                                        <source src="<?php echo asset('storage') ?>/uploads/<?php echo $song->mp3_file ?>" type="audio/mpeg">
                                    </audio>
                                </div>
                            </div>
                        </div>
                        <?php
                        $i++;
                    }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <div class="col-md-8">
                    <h3 style="text-align: center;">No result found.</h3>
                </div>
                <?php
            }
        }
    }

    public function editVote(Request $request)
    {
        $song = Songs::find($request->song_id);
        $old_position = $song->new_position;

        if ($request->vote_type == 'up') {
            $aaa = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->where('id', '=', $song->id)->first();
            $current_votes = $aaa->total_votes + $request->song_votes;
            $get_position = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->having('total_votes', '=', $current_votes)->get();

            $small_position = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->having('total_votes', '<', $current_votes)->where([['new_position', '<', $song->new_position]])->get();

            if ($get_position->count()) {
                foreach ($get_position as $change_position) {
                    $old_p = $change_position->new_position;

                    $change_position->old_position = $old_p;
                    $change_position->new_position = $change_position->new_position + 1;
                    $change_position->save();
                }

                $song->old_position = $old_position;
                $song->new_position = $song->new_position - $get_position->count();
            }

            if ($small_position->count()) {
                foreach($small_position as $s_pos) {
                    $old_p = $s_pos->new_position;

                    $s_pos->old_position = $old_p;
                    $s_pos->new_position = $s_pos->new_position + 1;
                    $s_pos->save();
                }

                $song->old_position = $old_position;
                $song->new_position = $song->new_position - $small_position->count();
            }

            $song->up_votes = $request->song_votes;
        } else {
            $aaa = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->where('id', '=', $song->id)->first();
            $current_votes = $aaa->total_votes - $request->song_votes;
            $get_position = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->having('total_votes', '=', $current_votes)->get();

            $small_position = Songs::select('songs.*', DB::raw('up_votes - down_votes as total_votes'))->having('total_votes', '>', $current_votes)->where([['new_position', '>', $song->new_position]])->get();

            if ($get_position->count()) {
                foreach ($get_position as $change_position) {
                    $old_p = $change_position->new_position;

                    $change_position->old_position = $old_p;
                    $change_position->new_position = $change_position->new_position - 1;
                    $change_position->save();
                }

                $song->old_position = $old_position;
                $song->new_position = $song->new_position + $get_position->count();
            }

            if ($small_position->count()) {
                foreach($small_position as $s_pos) {
                    $old_p = $s_pos->new_position;

                    $s_pos->old_position = $old_p;
                    $s_pos->new_position = $s_pos->new_position - 1;
                    $s_pos->save();
                }

                $song->old_position = $old_position;
                $song->new_position = $song->new_position + $small_position->count();
            }

            $song->down_votes = $request->song_votes;
        }

        if ($song->save()) {
            $result = ['status' => true, 'message' => 'Song votes updated successfully.'];
        } else {
            $result = ['status' => false, 'message' => 'Error in saving data.'];
        }
        return response()->json($result);
    }

    public function stream(Request $request)
    {
        $song = Songs::find($request->id);
        $song->stream_count++;

        if ($song->save()) {
            $result = ['status' => true, 'message' => ''];
        } else {
            $result = ['status' => false, 'message' => ''];
        }
    }

    public function like(Request $request)
    {
        $loginUser = Auth::user();

        if ($loginUser) {
            $id = $request->id;

            $model = new Likes;
            $model->user_id = $loginUser->id;
            $model->song_id = $id;
            $model->updated_at = Carbon::now();

            if ($model->save()) {
                $result = ['status' => true, 'message' => 'Song liked successfully.'];
            } else {
                $result = ['status' => false, 'message' => 'Error'];
            }
        } else {
            $result = ['status' => false, 'message' => 'Please login to like the song.', 'data' => []];
        }
        return response()->json($result);
    }

    public function liked()
    {
        $loginUser = Auth::user();
        $songs = DB::table('votes')->join('songs', 'votes.song_id', '=', 'songs.id')->where([['songs.is_deleted', 0], ['votes.user_id', $loginUser->id], ['votes.vote', 1]])->get();

        if ($loginUser) {
            return view('songs.liked', compact('songs'));
        }
        return Redirect('login');
    }

    public function remove_like(Request $request)
    {
        $loginUser = Auth::user();

        $song_id = $request->song_id;
        $vote = $request->vote;

        $find_vote = Votes::where([['user_id', $loginUser->id], ['song_id', $song_id]])->first();
        $find_vote->vote = $vote;

        if ($find_vote->save()) {
            $song = Songs::find($song_id);
            $song->down_votes++;
            $song->up_votes--;
            $song->save();

            $get_count = DB::table('votes')->where([['is_deleted', 0], ['user_id', $loginUser->id], ['vote', 1]])->get();
            $vote_count = $get_count->count();

            $result = ['status' => true, 'message' => 'Removed from liked songs.', 'data' => ['vote_count' => $vote_count]];
        } else {
            $result = ['status' => false, 'message' => 'Error in saving data.', 'data' => []];
        }
        return response()->json($result);
    }

    public function delete(Request $request)
    {
        $song = Songs::where('id', $request->id)->update(array('status' => '0', 'is_deleted' => '1'));

        if ($song) {
            $result = ['status' => true, 'message' => 'Song deleted successfully.'];
        } else {
            $result = ['status' => false, 'message' => 'Error in saving data.'];
        }
        return response()->json($result);
    }
}
