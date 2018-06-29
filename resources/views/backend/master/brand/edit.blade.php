@extends('backend.layouts.app')

@section('head')
    <title>Edit Brand | {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('global/vendor/dropify/dropify.min.css') }}">
@endsection

@section('title')
    Edit Brand
@endsection

@push('breadcrumb')
    <li class="breadcrumb-item active"><a href="{{ route('backend.master.brand.index') }}">Brand</a></li>
    <li class="breadcrumb-item active">Edit Brand</li>
@endpush

@section('content')
    <div class="panel">
        <div class="panel-body" id="add-form">
            <h3>Edit Brand</h3>
            @include('inc.error-list')
            <form class="form-horizontal" id="form" action="{{ route('backend.master.brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                {!! method_field('PUT') !!}
                {!! csrf_field() !!}
                <div class="row">
                    <div class="form-group col-md-12 col-lg-6">
                        <label class="control-label">Code <span class="text-danger">(Optional)</span></label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $brand->code) }}">
                    </div>
                    <div class="form-group col-md-12 col-lg-6">
                        <label class="control-label">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $brand->name) }}">
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ route('backend.master.brand.index') }}" class="btn btn-danger">Back</a>
                    <button type="Submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('global/vendor/dropify/dropify.min.js') }}"></script>
@endsection