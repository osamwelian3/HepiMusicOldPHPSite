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
    <div class="main-panel">
        @if ($songs->count())
            <div class="right-panel-main">
                <div class="right-panel display_for_pc">
                    @foreach ($songs as $song)
                        <div class="row song_row">
                            <div class="song_details">
                                <div class="col-md-9 col-xs-9 song_img">
                                    <div class="col-md-2 col-xs-2 al_center">
                                        <img src="{{ asset('storage/uploads') }}/{{ $song->thumbnail }}" class="song_thumbnail">
                                    </div>

                                    @php
                                        $song_title = strlen($song->song_title) > 15 ? substr($song->song_title, 0, 15)."..." : $song->song_title;
                                    @endphp

                                    <div class="col-md-3 col-xs-3 al_center song_title_pc" title="{{ $song->song_title }}">{{ $song_title }}</div>
                                    <div class="col-md-3 col-xs-3 al_center song_title_mobile" title="{{ $song->song_title }}" style="display: none;">{{ $song->song_title }}</div>

                                    <div class="col-md-7 col-xs-7 al_center">
                                        <audio preload="auto" controls controlsList="nodownload noplaybackrate" class="audio_box" id="{{ $song->id }}" data-id="{{ $song->id }}">
                                            <source src="{{ asset('storage') }}/uploads/{{ $song->mp3_file }}" type="audio/mpeg">
                                        </audio>
                                    </div>
                                </div>

                                <div class="col-md-3 col-xs-3 al_center btn_container" id="vote_{{ $song->id }}" style="text-align: right;">
                                    @php
                                        $up_vote = "";
                                        $down_vote = "";
                                        $thumbs_up = "thumbs_btn";
                                        $thumbs_down = "remove_like";
                                    @endphp

                                    {{-- @if (array_key_exists($song->id, $vote_arr))
                                        @php
                                            $vote = $vote_arr[$song->id]['vote'];
                                            if ($vote==1) {
                                                $up_vote = "background: gray !important; color: #fff !important;";
                                                $thumbs_up = "";
                                            } else {
                                                $down_vote = "background: gray !important; color: #fff !important;";
                                                $thumbs_down = "";
                                            }
                                        @endphp
                                    @endif --}}

                                    <span class="share_song_btn" data-id="{{ $song->id }}" title="Share"><i class="fa fa-share-alt"></i></span>

                                    <div class="share_box share_box_{{ $song->id }}" data-id="{{ $song->id }}">
                                        <p class="share_icons">
                                            <span class="social_btn btn_facebook" title="Facebook" onclick="window.open('//www.facebook.com/sharer.php?u={{ asset('storage/uploads') }}/{{ $song->mp3_file }}', '', 'width=600, height=800');"><i class="fa fa-facebook"></i></span>
                                            
                                            <a href="https://api.whatsapp.com/send?text={{ asset('storage/uploads') }}/{{ $song->mp3_file }}" target="_blank">
                                                <span class="social_btn btn_whatsapp" title="WhatsApp"><i class="fa fa-whatsapp"></i></span>
                                            </a>

                                            <span class="social_btn btn_twitter" title="Twitter" onclick="window.open('//twitter.com/share?url={{ asset('storage/uploads') }}/{{ $song->mp3_file }}', '', 'width=600, height=800');"><i class="fa fa-twitter"></i></span>
                                        </p>
                                    </div>

                                    <span class="vote_style up_vote" data-id="{{ $song->id }}" data-vote="1" title="Up Vote" style="{{ $up_vote  }}"><i class="fa fa-thumbs-up"></i> <span class="up_count">{{ $song->up_votes }}</span></span>

                                    <span class="{{ $thumbs_down }} vote_style down_vote" data-id="{{ $song->id }}" data-vote="0" title="Down Vote" style="{{ $down_vote  }}"><i class="fa fa-thumbs-down"></i> <span class="down_count">{{ $song->down_votes }}</span></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="right-panel display_for_mobile" style="display: none;">
                    @foreach ($songs as $song)
                        @php
                            $song_title = strlen($song->song_title) > 12 ? substr($song->song_title, 0, 12)."..." : $song->song_title;
                        @endphp

                        <div class="row song_row">
                            <div class="col-md-2 col-xs-2">
                                <img src="{{ asset('storage/uploads') }}/{{ $song->thumbnail }}" class="song_thumbnail">
                            </div>

                            <div class="col-md-10 col-xs-10">
                                <div class="row song_detail_upper">
                                    <div class="col-md-3 col-xs-3" title="{{ $song->song_title }}" style="width: 80%; color: #fff;">{{ $song_title }}</div>

                                    <div class="col-md-9 col-xs-9 btn_container" id="vote_{{ $song->id }}">
                                        @php
                                            $up_vote = "";
                                            $down_vote = "";
                                            $thumbs_up = "thumbs_btn";
                                            $thumbs_down = "remove_like";
                                        @endphp

                                        {{-- @if (array_key_exists($song->id, $vote_arr))
                                            @php
                                                $vote = $vote_arr[$song->id]['vote'];
                                                if ($vote==1) {
                                                    $up_vote = "background: gray !important; color: #fff !important;";
                                                    $thumbs_up = "";
                                                } else {
                                                    $down_vote = "background: gray !important; color: #fff !important;";
                                                    $thumbs_down = "";
                                                }
                                            @endphp
                                        @endif --}}

                                        <span class="share_song_btn" data-id="{{ $song->id }}" title="Share"><i class="fa fa-share-alt"></i></span>

                                        <div class="share_box share_box_{{ $song->id }}" data-id="{{ $song->id }}">
                                            <p class="share_icons">
                                                <span class="social_btn btn_facebook" title="Facebook" onclick="window.open('//www.facebook.com/sharer.php?u={{ asset('storage/uploads') }}/{{ $song->mp3_file }}', '', 'width=600, height=800');"><i class="fa fa-facebook"></i></span>
                                                
                                                <a href="https://api.whatsapp.com/send?text={{ asset('storage/uploads') }}/{{ $song->mp3_file }}" target="_blank">
                                                    <span class="social_btn btn_whatsapp" title="WhatsApp"><i class="fa fa-whatsapp"></i></span>
                                                </a>

                                                <span class="social_btn btn_twitter" title="Twitter" onclick="window.open('//twitter.com/share?url={{ asset('storage/uploads') }}/{{ $song->mp3_file }}', '', 'width=600, height=800');"><i class="fa fa-twitter"></i></span>
                                            </p>
                                        </div>

                                        <span class="vote_style up_vote" data-id="{{ $song->id }}" data-vote="1" title="Up Vote" style="{{ $up_vote  }}"><i class="fa fa-thumbs-up"></i> <span class="up_count">{{ $song->up_votes }}</span></span>

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
                    @endforeach
                </div>
            </div>
        @else
            <h3 style="text-align: center; color: #fff;">No result found.</h3>
        @endif
    </div>
</div>

@endsection

@section('js')
<script>
    var isFrontend = true;
    var loginId = "{{ $login_id }}";
    var voteUrl = "{{ route('songs.vote') }}";
    var filterUrl = "{{ route('songs.filter') }}";
    var streamUrl = "{{ route('songs.stream') }}";
    var likeUrl = "{{ route('songs.like') }}";
    var removeLike = "{{ route('songs.remove_like') }}";
</script>
@endsection

@section('pagejs')
<script src="{{ addPageJsLink('song.js') }}"></script>
@endsection