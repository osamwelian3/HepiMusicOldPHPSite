@extends('layouts.app')
@section('content')
<div id="page-wrapper" style="min-height: 257px;">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Categories</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="active">Categories</li>
                </ol>
            </div>            
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <button type="button" class="btn btn-success m-b-10 pull-right add-new">Add Category</button>
                    <div class="table-responsive">
                        <table id="listTable" class="table table-hover">
                            <thead>
                                <tr>                                    
                                    <th>Category Name</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
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
                <h4 class="modal-title"><span class="modal-lable-class">Add</span> Category</h4> 
            </div>
            <div class="modal-body">
                <form id="add-form" method="post" class="ps-3 pe-3" action="{{ route('categories.addupdate') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="0" id="edit-id">
                    <div id="add_error_message"></div>

                    <div class="row col-md-12">
                        <div class="col-md-12 mb-3">
                            <label for="category_name" class="control-label">Category Name:</label>
                            <input id="category_name" type="text" class="form-control category_name" name="category_name" placeholder="Category Name">
                            <span class="invalid-feedback error-category_name" role="alert"><strong></strong></span>
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
    var apiUrl = "{{ route('categories.list') }}";
    var detailUrl = "{{ route('categories.detail') }}";
    var deleteUrl = "{{ route('categories.delete') }}";
    var updateStatusUrl = "{{ url('/categories/updatefield') }}";
    var addUrl = $('#add-form').attr('action');
    var changeStatusUrl = "{{ route('categories.change_status') }}";
</script>
@endsection

@section('pagejs')
<script src="{{ addPageJsLink('category.js') }}"></script>
@endsection