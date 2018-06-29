@extends('backend.layouts.app')

@section('head')
    <title>Brand List | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/datatables-bootstrap/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/tables/datatable.css') }}">
@endsection

@section('title')
    Brand List
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active">Brand</li>
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
                            <i class="icon wb-plus" aria-hidden="true"></i> Add Brand
                        </a>
                    </div>
                </div>
            </div>
            @endcan
            <table class="table table-bordered table-hover table-striped" cellspacing="0" id="datatable"></table>
        </div>
    </div>
    @can ('create-master-data')
    <div class="panel">
        <div class="panel-body" id="add-form" {!! count($errors) == 0 ? "style='display: none;'" : '' !!}>
            <h3>Add New Brand</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('backend.master.brand.store') }}" method="POST">
                {!! csrf_field() !!}
                <div class="form-group">
                    <div class="row">
                        <div class="form-group col-md-12 col-lg-6">
                            <label class="control-label">Code <span class="text-danger">(Optional)</span></label>
                            <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <label class="control-label">Name</label>
                            <input type="text" name="name" id="table" class="form-control" value="{{ old('name') }}" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" id="cancel-btn" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').dataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    method: 'POST',
                    url : '{{ route('backend.master.brand.data') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns : [
                    { title: 'Code', data: 'code', name: 'code', defaultContent: '-', class: 'text-center' },
                    { title: 'Name', data: 'name', name: 'name', defaultContent: '-', class: 'text-center' },
                    { title: 'Action', data: 'action', name: 'action', searchable: false, orderable: false, class: 'text-center' }
                ],
                initComplete: function() {
                    $('.tl-tip').tooltip();
                    @if (count($errors) > 0)
                        jQuery("html, body").animate({
                            scrollTop: $('#add-form').offset().top - 100 // 66 for sticky bar height
                        }, "slow");
                    @endif
                }
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
        });
    </script>
@endsection