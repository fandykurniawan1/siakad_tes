@extends('backend.layouts.app')

@section('head')
    <title>Email Template | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/summernote/summernote.css') }}">
@endsection

@section('title')
    Email Template
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Email Template</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')
            @can ('create-master-data')
            <div class="row">
                <div class="col-xs-12">
                    <div class="m-b-15">
                        <a id="add-btn" class="btn btn-primary white">
                            <i class="icon wb-plus" aria-hidden="true"></i> New Email Template
                        </a>
                    </div>
                </div>
            </div>
            @endcan
            <table class="table table-bordered table-hover table-striped" cellspacing="0" id="datatable"></table>
        </div>
    </div>
    @can ('create-master-data')
    <div class="panel panel-bordered" {!! count($errors) == 0 ? "style='display: none;'" : '' !!} id="add-form">
        <div class="panel-heading">
            <h3 class="panel-title">Add New Email Template</h3>
        </div>
        <div class="panel-body">
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('backend.email.template.store') }}" method="POST">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="control-label">Name:</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label">Type:</label>
                        <select name="type" class="form-control select2" required>
                            <option value="">Select Email Template Type</option>
                            <option value="registration" {{ old('type') == 'registration' ? 'selected' : '' }}>Registration</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Content:</label>
                        @include('inc.email-variable', ['type' => 'general'])
                        <textarea id="content-create" name="content" required>{{ old('content') }}</textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <button type="button" id="cancel-btn" class="btn btn-danger">Cancel</button>
                        <button type="submit" class="btn btn-primary pull-right">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endcan
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')
    <script src="{{ asset('global/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('global/vendor/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('global/vendor/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('global/vendor/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('backend.email.template.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    { title: 'Template Name', data: 'name', name: 'name', class: 'text-center', defaultContent: '-' },
                    { title: 'Type', data: 'type', name: 'type', class: 'text-center', orderable: false, searchable: false },
                    { title: 'Action', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center' }
                ],
            });
            $('#add-btn').click(function(e) {
                $('#add-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('#add-form').offset().top - 100 // 66 for sticky bar height
                }, "slow");
            });
            $('#cancel-btn').click(function(e) {
                $('#add-form').toggle();
                jQuery("html, body").animate({
                    scrollTop: $('body').offset().top - 100 // 66 for sticky bar height
                }, "slow");
            });
            $('#content-create').summernote({
                height: 400
            });
            $('.select2').select2({
                'placeholder' : 'Select Email Template Type',
                'allowClear' : true,
                'width' : '100%'
            });
        });
    </script>
@endsection