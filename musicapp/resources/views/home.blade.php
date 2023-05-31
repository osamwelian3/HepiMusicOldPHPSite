@php
    $loginUser = Auth::user();

    $login_id = 0;
    if ($loginUser) {
        $login_id = $loginUser->id;
    }
@endphp

@extends('layouts.frontend')

@section('content')
<div class="container" style="margin-top: 50px;">
    {{-- <div class="col-md-12">
        <div class="col-md-4"></div>
        <div class="col-md-8"></div>
    </div> --}}
    <div class="main-panel">
        <div class="left-panel">
            <h4><i class="fa fa-filter"></i> Filter by</h4>

            <div class="sub_block brands">
                <h5>Categories</h5>

                <div class="sub_filter_content">
                    {{-- <ul>
                        @foreach ($categories as $category)
                            @php
                                $encode_name = urlencode($category->category_name);
                            @endphp

                            <li>
                                <input type="checkbox" class="categories_checkbox" name="categories_checkbox" data-id="{{ $category->id }}" data-value="{{ $encode_name }}">
                                <span>{{ $category->category_name }}</span>
                                <label class="cus_checkbox" style="position: relative; display: inline-block; padding-left: 15px; font-size: 14px;">
                                    <input type="radio" name="categories_radio" class="categories_radio" value="{{ $category->id }}" style="margin-right: 10px;">
                                    {{ $category->category_name }}
                                </label>
                            </li>
                        @endforeach
                    </ul> --}}

                    <select name="category_filter" class="category_filter form-control">
                        <option value="all">All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="right-panel-main">
            <div class="right-panel display_for_pc">
                <div class="filter_bar">
                    <div class="filter_selected_value">
                        <ul class="add_filter_class">
                            <li class="clearall" style="display: none;">
                                <a href="javascript:void(0)">Clear All</a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                @php
                    $i = 1;
                @endphp

                @foreach ($songs as $song)
                    <div class="row song_row">
                        <div class="song_details">
                            <div class="col-md-9 col-xs-9 song_img">
                                <div class="col-md-1 col-xs-1">
                                    <span style="font-size: 18px; color: #fff;">{{ $i }}</span>
                                </div>

                                <div class="col-md-2 col-xs-2 al_center">
                                    <img src="{{ asset('storage/uploads') }}/{{ $song->thumbnail }}" class="song_thumbnail">
                                </div>

                                <div class="col-md-1 col-xs-1">
                                    @if ($song->new_position < $song->old_position)
                                        <span class="song_label_up"><i class="fa fa-arrow-circle-o-up" style="margin-top: 1px;"></i></span>
                                    @elseif ($song->new_position > $song->old_position)
                                        <span class="song_label_down"><i class="fa fa-arrow-circle-o-down" style="margin-top: 1px;"></i></span>
                                    @else
                                        <span class="song_label_new">NEW</span>
                                    @endif
                                </div>

                                @php
                                    $song_title = strlen($song->song_title) > 15 ? substr($song->song_title, 0, 15)."..." : $song->song_title;
                                @endphp

                                <div class="col-md-3 col-xs-3 al_center song_title_pc" title="{{ $song->song_title }}">{{ $song_title }}</div>
                                <div class="col-md-3 col-xs-3 al_center song_title_mobile" title="{{ $song->song_title }}" style="display: none;">{{ $song->song_title }}</div>

                                <div class="col-md-5 col-xs-5 al_center">
                                    <audio preload="auto" controls controlsList="nodownload noplaybackrate" class="audio_box" id="{{ $song->id }}" data-id="{{ $song->id }}" style="width: 100%;">
                                        <source src="{{ asset('storage') }}/uploads/{{ $song->mp3_file }}" type="audio/mpeg">
                                    </audio>
                                </div>
                            </div>

                            <div class="col-md-3 col-xs-3 al_center btn_container" id="vote_{{ $song->id }}" style="text-align: right;">
                                @php
                                    $up_vote = "";
                                    $down_vote = "";
                                    $liked = "";
                                    $liked_class = "like_btn";
                                    $liked_title = "Like";
                                    $thumbs_up = "thumbs_btn";
                                    $thumbs_down = "thumbs_btn";
                                    $thumbs_btn = "thumbs_btn";
                                @endphp

                                @if (array_key_exists($song->id, $vote_arr))
                                    @php
                                        $thumbs_btn = "";
                                        $vote = $vote_arr[$song->id]['vote'];
                                        if ($vote==1) {
                                            $up_vote = "background: #F3B106 !important; color: #fff !important;";
                                            $thumbs_up = "";
                                        } else {
                                            $down_vote = "background: #F3B106 !important; color: #fff !important;";
                                            $thumbs_down = "";
                                        }
                                    @endphp
                                @endif

                                @if (isset($song->liked) && $song->liked && $loginUser)
                                    @php
                                        $liked = "background: red !important; color: #fff !important;";
                                        $liked_class = "liked";
                                        $liked_title = "Liked";
                                    @endphp
                                @endif

                                {{-- <span class="{{ $liked_class }}" data-id="{{ $song->id }}" title="{{ $liked_title }}" style="{{ $liked }}"><i class="fa fa-heart"></i></span> --}}
                                <span class="share_song_btn" data-id="{{ $song->id }}" title="Share"><i class="fa fa-share-alt"></i></span>

                                <div class="share_box share_box_{{ $song->id }}" data-id="{{ $song->id }}">
                                    <p class="share_icons">
                                        <span class="social_btn btn_facebook" title="Facebook" onclick="window.open('//www.facebook.com/sharer.php?u={{ route('songs.listen', $song->mp3_file) }}', '', 'width=600, height=800');"><i class="fa fa-facebook"></i></span>
                                        
                                        {{-- {{ asset('storage/uploads') }}/{{ $song->mp3_file }} --}}
                                        <a href="https://api.whatsapp.com/send?text={{ route('songs.listen', $song->mp3_file) }}" target="_blank">
                                            <span class="social_btn btn_whatsapp" title="WhatsApp"><i class="fa fa-whatsapp"></i></span>
                                        </a>

                                        <span class="social_btn btn_twitter" title="Twitter" onclick="window.open('//twitter.com/share?url={{ route('songs.listen', $song->mp3_file) }}', '', 'width=600, height=800');"><i class="fa fa-twitter"></i></span>
                                    </p>
                                </div>

                                <span class="{{ $thumbs_up }} vote_style up_vote" data-id="{{ $song->id }}" data-vote="1" title="Up Vote" style="{{ $up_vote  }}"><i class="fa fa-thumbs-up"></i> <span class="up_count">{{ $song->up_votes }}</span></span>

                                <span class="{{ $thumbs_down }} vote_style down_vote" data-id="{{ $song->id }}" data-vote="0" title="Down Vote" style="{{ $down_vote  }}"><i class="fa fa-thumbs-down"></i> <span class="down_count">{{ $song->down_votes }}</span></span>
                            </div>
                        </div>
                    </div>

                    @php
                        $i++;
                    @endphp
                @endforeach
            </div>

            <div class="right-panel display_for_mobile" style="display: none;">
                @php
                    $i = 1;
                @endphp

                @foreach ($songs as $song)
                    @php
                        $song_title = strlen($song->song_title) > 12 ? substr($song->song_title, 0, 12)."..." : $song->song_title;
                    @endphp

                    <div class="row song_row">
                        <div class="col-md-2 col-xs-2" style="padding: 0;">
                            @if ($song->new_position < $song->old_position)
                                <span class="song_label_up">{{ $i }} <i class="fa fa-arrow-circle-o-up" style="margin-top: 1px;"></i></span>
                            @elseif ($song->new_position > $song->old_position)
                                <span class="song_label_down">{{ $i }} <i class="fa fa-arrow-circle-o-down" style="margin-top: 1px;"></i></span>
                            @else
                                <span class="song_label_new">{{ $i }} -</span>
                            @endif

                            <img src="{{ asset('storage/uploads') }}/{{ $song->thumbnail }}" class="song_thumbnail">
                        </div>

                        <div class="col-md-10 col-xs-10">
                            <div class="row song_detail_upper">
                                <div class="col-md-3 col-xs-3" title="{{ $song->song_title }}" style="width: 80%; color: #fff;">{{ $song_title }}</div>

                                <div class="col-md-9 col-xs-9 btn_container" id="vote_{{ $song->id }}">
                                    @php
                                        $up_vote = "";
                                        $down_vote = "";
                                        $liked = "";
                                        $liked_class = "like_btn";
                                        $liked_title = "Like";
                                        $thumbs_up = "thumbs_btn";
                                        $thumbs_down = "thumbs_btn";
                                        $thumbs_btn = "thumbs_btn";
                                    @endphp

                                    @if (array_key_exists($song->id, $vote_arr))
                                        @php
                                            $thumbs_btn = "";
                                            $vote = $vote_arr[$song->id]['vote'];
                                            if ($vote==1) {
                                                $up_vote = "background: #F3B106 !important; color: #fff !important;";
                                                $thumbs_up = "";
                                            } else {
                                                $down_vote = "background: #F3B106 !important; color: #fff !important;";
                                                $thumbs_down = "";
                                            }
                                        @endphp
                                    @endif

                                    @if (isset($song->liked) && $song->liked && $loginUser)
                                        @php
                                            $liked = "background: red !important; color: #fff !important;";
                                            $liked_class = "liked";
                                            $liked_title = "Liked";
                                        @endphp
                                    @endif

                                    <span class="share_song_btn" data-id="{{ $song->id }}" title="Share"><i class="fa fa-share-alt"></i></span>

                                    <div class="share_box share_box_{{ $song->id }}" data-id="{{ $song->id }}">
                                        <p class="share_icons">
                                            <span class="social_btn btn_facebook" title="Facebook" onclick="window.open('//www.facebook.com/sharer.php?u={{ route('songs.listen', $song->mp3_file) }}', '', 'width=600, height=800');"><i class="fa fa-facebook"></i></span>
                                            
                                            <a href="https://api.whatsapp.com/send?text={{ route('songs.listen', $song->mp3_file) }}" target="_blank">
                                                <span class="social_btn btn_whatsapp" title="WhatsApp"><i class="fa fa-whatsapp"></i></span>
                                            </a>

                                            <span class="social_btn btn_twitter" title="Twitter" onclick="window.open('//twitter.com/share?url={{ route('songs.listen', $song->mp3_file) }}', '', 'width=600, height=800');"><i class="fa fa-twitter"></i></span>
                                        </p>
                                    </div>

                                    <span class="{{ $thumbs_up }} vote_style up_vote" data-id="{{ $song->id }}" data-vote="1" title="Up Vote" style="{{ $up_vote  }}"><i class="fa fa-thumbs-up"></i> <span class="up_count">{{ $song->up_votes }}</span></span>

                                    <span class="{{ $thumbs_down }} vote_style down_vote" data-id="{{ $song->id }}" data-vote="0" title="Down Vote" style="{{ $down_vote  }}"><i class="fa fa-thumbs-down"></i> <span class="down_count">{{ $song->down_votes }}</span></span>
                                </div>
                            </div>

                            <div class="row">
                                <audio preload="auto" controls controlsList="nodownload noplaybackrate" class="audio_box" id="{{ $song->id }}" data-id="{{ $song->id }}">
                                    <source src="{{ asset('storage') }}/uploads/{{ $song->mp3_file }}" type="audio/mpeg">
                                </audio>
                            </div>
                        </div>
                    </div>

                    @php
                        $i++;
                    @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- <input type="hidden" name="get_link" class="get_link" value="{{ $_GET['title'] }}"> --}}
@endsection

@section('js')
<script>
    var isFrontend = true;
    var loginId = "{{ $login_id }}";
    var voteUrl = "{{ route('songs.vote') }}";
    var filterUrl = "{{ route('songs.filter') }}";
    var streamUrl = "{{ route('songs.stream') }}";
    var likeUrl = "{{ route('songs.like') }}";
</script>
@endsection

@section('pagejs')
<script src="{{ addPageJsLink('song.js') }}"></script>
@endsection