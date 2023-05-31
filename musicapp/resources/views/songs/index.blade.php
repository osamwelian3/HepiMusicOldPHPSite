@extends('layouts.app')
@section('content')
<div id="page-wrapper" style="min-height: 257px;">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Songs</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="active">Songs</li>
                </ol>
            </div>            
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <button type="button" class="btn btn-success m-b-10 pull-right add-new">Add Song</button>
                    <div class="table-responsive">
                        <table id="listTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Song Title</th>
                                    <th>Created Date</th>
                                    <th>Streams</th>
                                    <th>Votes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div id="add-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><span class="modal-lable-class">Add</span> Song</h4> 
            </div>
            <div class="modal-body">
                <form id="add-form" method="post" class="ps-3 pe-3" action="{{ route('songs.addupdate') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="0" id="edit-id">
                    <div id="add_error_message"></div>

                    <div class="row col-md-12">
                        <div class="col-md-12 mb-3">
                            <label for="category_id" class="control-label">Category:</label>
                            <select name="category_id" id="category_id" class="form-control category_id">
                                <option value="">Select Category</option>
                                @foreach ($all_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback error-category_id" role="alert"><strong></strong></span>
                        </div>
                    </div>

                    <div class="row col-md-12">              
                        <div class="col-md-12 mb-3">
                            <label for="song_title" class="control-label">Song Title:</label>
                            <input id="song_title" type="text" class="form-control song_title" name="song_title" placeholder="Song Title">
                            <span class="invalid-feedback error-song_title" role="alert"><strong></strong></span>
                        </div>
                    </div>

                    <div class="row col-md-12">
                        <div class="col-md-7 mb-3">
                            <label for="mp3_file" class="control-label">{{ __('MP3 File') }}:</label>
                            <input id="mp3_file" type="file" class="form-control mp3_file" name="mp3_file" accept="audio/mp3">
                            <span class="invalid-feedback error-mp3_file" role="alert"><strong></strong></span>
                        </div>
                        <div class="col-md-5 mb-3" style="display: flex; align-items: center;">
                            <div class="display_mp3"></div>
                        </div>
                    </div>

                    <div class="row col-md-12">
                        <div class="col-md-10 mb-3">
                            <label for="thumbnail" class="control-label">{{ __('Thumbnail') }}:</label>
                            <input id="thumbnail" type="file" class="form-control thumbnail" name="thumbnail" accept="image/png, image/jpg, image/jpeg">
                            <span class="invalid-feedback error-thumbnail" role="alert"><strong></strong></span>
                        </div>
                        <div class="col-md-2 mb-3" style="display: flex; align-items: center;">
                            <img src="{{ asset('backend/images/noimage1.jpg') }}" class="display_img" width="75px" style="height: auto;">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="vote-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><span class="modal-lable-class"></span></h4> 
            </div>
            <div class="modal-body">
                <form id="vote-form" method="post" class="ps-3 pe-3" action="{{ route('songs.edit_vote') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row col-md-12">              
                        <div class="col-md-12 mb-3">
                            <label for="song_votes" class="control-label vote_label"></label>
                            <input type="hidden" name="song_id" class="song_id" value="">
                            <input type="hidden" name="vote_type" class="vote_type" value="">
                            <input id="song_votes" type="number" class="form-control song_votes" name="song_votes" min="0">
                            <span class="invalid-feedback error-song_votes" role="alert"><strong></strong></span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    var isFrontend = false;
    var apiUrl = "{{ route('songs.list') }}";
    var detailUrl = "{{ route('songs.detail') }}";
    var deleteUrl = "{{ route('songs.delete') }}";
    var updateStatusUrl = "{{ url('/songs/updatefield') }}";
    var addUrl = $('#add-form').attr('action');
    var editVote = $('#vote-form').attr('action');
    var changeStatusUrl = "{{ route('songs.change_status') }}";
    var defaultImage = "{{ asset('backend/images/noimage1.jpg') }}";
    var defaultFile = "{{ asset('backend/images/no-mp3.webp') }}";
    var fileUrl = "{{ asset('storage') }}/uploads";
</script>
@endsection

@section('pagejs')
<script src="{{ addPageJsLink('song.js') }}"></script>
@endsection