@extends('backend.layouts.app')

@section('head')
    <title>Product Category List | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/jquery-treegrid/css/jquery.treegrid.css') }}">
    <link rel="stylesheet" href="{{ asset('global/fonts/glyphicons/glyphicons.css') }}">
@endsection

@section('title')
    Product Category List
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active"><a href="#">Master Data</a></li>
    <li class="breadcrumb-item active">Product Category</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body">
            @include('inc.success-notif')
            @include('inc.error-list')

            @can ('create-master-data')
                <div class="form-group">
                    <a href="{{ route('backend.master.product-category.create') }}" class="btn btn-primary">
                        <i class="icon wb-plus text" aria-hidden="true"></i> New Product Category
                    </a>
                </div>
            @endcan

            <table id="table" class="table table-bordered table-hover table-striped w100" cellspacing="0">
                <thead>
                    <tr class="text-center">
                        <td>Name</td>
                        <td>Code</td>
                        @can (['edit-master-data', 'delete-master-data'])
                            <td>Action</td>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productCategories as $productCategory)
                        <tr class="treegrid-{{ $productCategory['index_id'] }} {{ ($productCategory['is_grandparent']) ? '' : 'treegrid-parent-' . $productCategory['index_parent_id'] }}">
                            <td>{{ $productCategory['name'] }}</td>
                            <td class="text-center">{{ $productCategory['code'] }}</td>
                            @can (['edit-master-data', 'delete-master-data'])
                                <td class="text-center">
                                    @can ('edit-master-data')
                                        <a href="{{ route('backend.master.product-category.edit', $productCategory['id']) }}" class="btn btn-sm btn-icon text-primary tl-tip" data-toggle="tooltip" data-original-title="Edit"><i class="icon wb-edit" aria-hidden="true"></i></a>
                                    @endcan
                                    @can ('delete-master-data')
                                        <a class="btn btn-sm btn-icon text-danger tl-tip" data-href="{{ route('backend.master.product-category.destroy', $productCategory['id']) }}" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete"><i class="icon wb-trash" aria-hidden="true"></i></a>
                                    @endcan
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('footer')
    @include ('inc.confirm-delete-modal')

    <script src="{{ asset('global/vendor/jquery-treegrid/js/jquery.treegrid.js') }}"></script>
    <script src="{{ asset('global/vendor/jquery-treegrid/js/jquery.treegrid.bootstrap3.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#table').treegrid();
            $('.tl-tip').tooltip();
        });
    </script
@endsection